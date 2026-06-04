<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    const CACHE_KEY = 'app_settings';

    const CACHE_TTL = 86400;

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::allCached();
 
        if (!array_key_exists($key, $settings)) {
            return $default;
        }
 
        $row = $settings[$key];
 
        return match ($row['type']) {
            'json'    => json_decode($row['value'], true) ?? $default,
            'boolean' => (bool) $row['value'],
            'file'    => $row['value'] ? Storage::url($row['value']) : $default,
            default   => $row['value'] ?? $default,
        };
    }

    public static function set(string $key, mixed $value): void
    {
        $existing = static::where('key', $key)->first();
        $type     = $existing?->type ?? 'text';
 
        if ($type === 'json' && is_array($value)) {
            $value = json_encode($value);
        }
 
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $existing?->group ?? 'general']
        );
 
        static::clearCache();
    }


    public static function setMany(array $data): void
    {
        foreach ($data as $key => $value) {
            static::set($key, $value);
        }
    }

    public static function storeFile(string $key, \Illuminate\Http\UploadedFile $file, string $disk = 'public'): string
    {
        $existing = static::where('key', $key)->first();
 
        // Delete old file if present
        if ($existing && $existing->value) {
            Storage::disk($disk)->delete($existing->value);
        }
 
        $path = $file->store("settings/{$key}", $disk);
 
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $path, 'type' => 'file', 'group' => $existing?->group ?? 'appearance']
        );
 
        static::clearCache();
 
        return $path;
    }

    public static function deleteFile(string $key, string $disk = 'public'): void
    {
        $row = static::where('key', $key)->first();
 
        if ($row && $row->value) {
            Storage::disk($disk)->delete($row->value);
            $row->update(['value' => null]);
            static::clearCache();
        }
    }

    public static function group(string $group): array
    {
        $all = static::allCached();
 
        return collect($all)
            ->filter(fn($row) => $row['group'] === $group)
            ->mapWithKeys(fn($row, $key) => [$key => $row['value']])
            ->toArray();
    }


    public static function allCached(): array
    {
        return Cache::remember(static::CACHE_KEY, static::CACHE_TTL, function () {
            return static::all()
                ->keyBy('key')
                ->map(fn($s) => [
                    'value' => $s->value,
                    'type'  => $s->type,
                    'group' => $s->group,
                ])
                ->toArray();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget(static::CACHE_KEY);
    }



}
