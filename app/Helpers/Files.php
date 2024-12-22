<?php


use Illuminate\Support\Facades\Storage;

if (!function_exists('StorageUrl')) {
    /**
     * Generate the full URL for a file stored in the public storage disk.
     *
     * @param string|null $path The file path relative to the public storage folder.
     * @return string|null
     */
    function StorageUrl(?string $path): ?string
    {
        if (!$path) {
            return null; // Return null if no path is provided
        }
        return asset(Storage::url($path));
    }
}