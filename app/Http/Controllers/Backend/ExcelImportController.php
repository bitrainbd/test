<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;


class ExcelImportController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('import.index', compact('users'));
    }

    /**
     * Handle the uploaded Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
            ],
        ]);

        // Store temporarily so PhpSpreadsheet can read embedded images via real path
        $uploaded = $request->file('file');
        $tempPath = $uploaded->store('imports/temp', 'local');

        // Use Storage::path() — works correctly on Windows (no mixed slashes)
        $fullPath = \Illuminate\Support\Facades\Storage::disk('local')->path($tempPath);
        try {
            Excel::import(new UsersImport($fullPath), $fullPath);
        } finally {
            // Clean up temp file
            \Illuminate\Support\Facades\Storage::disk('local')->delete($tempPath);
        }

        return redirect()->route('import.get')
            ->with('success', 'Users imported successfully!');
    }

    /**
     * Delete all users (for demo/testing).
     */
    public function clear()
    {
        // Delete stored photos first
        foreach (User::whereNotNull('photo')->get() as $user) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
        }

        User::truncate();

        return redirect()->route('users.index')
            ->with('success', 'All users cleared.');
    }
}