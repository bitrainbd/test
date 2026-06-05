<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * Images extracted from the sheet, keyed by row index (2-based).
     * Populated in __construct by pre-reading the file with PhpSpreadsheet.
     */
    private array $imagesByRow = [];
    private int $currentRow = 1; // heading row is row 1

    public function __construct(string $filePath)
    {   
        $this->extractImages($filePath);
    }

    /**
     * PhpSpreadsheet lets us read embedded images before the row-by-row import.
     * We store each image as a base64 blob keyed by the Excel row number.
     */
    private function extractImages(string $filePath): void
{
    $spreadsheet = IOFactory::load($filePath);
    $sheet       = $spreadsheet->getActiveSheet();

    foreach ($sheet->getDrawingCollection() as $drawing) {
        $rowIndex = (int) preg_replace('/[^0-9]/', '', $drawing->getCoordinates());

        if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing) {
            // Image is stored in memory (e.g. created programmatically)
            ob_start();
            $callable = $drawing->getRenderingFunction();
            $callable($drawing->getImageResource());
            $imageData = ob_get_clean();

        } elseif ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\Drawing) {
            // Image is embedded in the xlsx zip — extract it manually
            $zip = new \ZipArchive();
            if ($zip->open($filePath) === true) {
                // The path inside the zip looks like "xl/media/image1.png"
                $zipPath   = 'xl/media/' . basename($drawing->getPath());
                $imageData = $zip->getFromName($zipPath);
                $zip->close();
            }
        }

        if (!empty($imageData)) {
            $this->imagesByRow[$rowIndex] = $imageData;
        }
    }
}

    public function model(array $row): ?User
    {
        $this->currentRow++;
        // Skip completely empty rows
        if (empty(array_filter($row))) {
            return null;
        }

        $photoPath = null;
        if (isset($this->imagesByRow[$this->currentRow])) {
            $imageData = $this->imagesByRow[$this->currentRow];
            $filename  = 'photos/' . Str::uuid() . '.png';

            // Save to local public disk  →  storage/app/public/photos/
            Storage::disk('public')->put($filename, $imageData);
            $photoPath = $filename;
        }

        return new User([
            'name'       => $row['name']       ?? null,
            'email'      => $row['email']      ?? null,
            'phone'      => $row['phone']      ?? null,
            'avatar_url'      => $photoPath,
        ]);
    }
}
