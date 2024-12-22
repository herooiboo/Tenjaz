<?php

namespace App\Services\v1\Files;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * ImageUploader handles file uploads, including optional WebP conversion.
 */
class ImageUploader
{
    /**
     * @var array<string> Valid file extensions for image uploads.
     */
    protected static array $validExtensions = ['jpeg', 'jpg', 'png', 'gif'];

    /**
     * @var array<string> File extensions that can be converted to WebP.
     */
    protected static array $webpSupportedExtensions = ['jpeg', 'jpg', 'png'];

    /**
     * Upload a file to a specific folder with optional WebP conversion.
     *
     * @param UploadedFile $file The uploaded file.
     * @param string $folder The destination folder.
     * @param string $prefix Optional prefix for the filename.
     * @param bool $convertToWebP Whether to convert the image to WebP format.
     * @return string The file path of the uploaded or converted file.
     */
    public static function upload(UploadedFile $file, string $folder, string $prefix = '', bool $convertToWebP = true): string
    {
        $extension = Str::lower($file->getClientOriginalExtension()); // Get the file extension.
        $filename = uniqid($prefix) . '.' . $extension; // Generate a unique filename.

        // Validate the file extension.
        if (in_array($extension, self::$validExtensions)) {
            if ($convertToWebP && in_array($extension, self::$webpSupportedExtensions)) {
                $webpFilename = uniqid($prefix) . '.webp';
                self::convertToWebP($file->getRealPath(), $folder, $webpFilename); // Convert to WebP.
                $filename = $webpFilename;
            } else {
                self::uploadDirectly($file->getRealPath(), $folder, $filename); // Upload without conversion.
            }
        } else {
            abort(400, 'Not Supported Image of ' . $file->getClientOriginalName()); // Invalid file type.
        }

        return $folder . '/' . $filename; // Return the file path.
    }

    /**
     * Upload multiple files to a specific folder with optional WebP conversion.
     *
     * @param array $filesArray An array of UploadedFile instances.
     * @param string $folder The destination folder.
     * @param bool $convertToWebP Whether to convert images to WebP.
     * @return array The file paths of the uploaded files.
     */
    public static function multiUpload(array $filesArray, string $folder, bool $convertToWebP = true): array
    {
        $uploadedFilesArray = [];

        foreach ($filesArray as $fileKey => $file) {
            if ($file) {
                $uploadedFilename = self::upload($file, $folder, $fileKey, $convertToWebP);
                $uploadedFilesArray[$fileKey] = $uploadedFilename;
            }
        }

        return $uploadedFilesArray;
    }

    /**
     * Convert an image to WebP format.
     *
     * @param string $imagePath The source image path.
     * @param string $folder The destination folder.
     * @param string $webpFilename The WebP file name.
     */
    private static function convertToWebP(string $imagePath, string $folder, string $webpFilename): void
    {
        $folderPath = storage_path("app/public/{$folder}");
        self::createFolderIfNotExist($folderPath); // Ensure the folder exists.

        $image = imagecreatefromstring(file_get_contents($imagePath)); // Create image resource.

        if ($image !== false) {
            if (!imageistruecolor($image)) { // Convert to true color if needed.
                $trueColorImage = imagecreatetruecolor(imagesx($image), imagesy($image));
                imagecopy($trueColorImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                imagedestroy($image);
                $image = $trueColorImage;
            }

            $webpPath = $folderPath . '/' . $webpFilename;

            if (imagewebp($image, $webpPath)) { // Save as WebP.
                imagedestroy($image);
            } else {
                abort(500, 'WebP Conversion Failed'); // Conversion error.
            }
        }
    }

    /**
     * Upload a file directly without conversion.
     *
     * @param string $imagePath The source file path.
     * @param string $folder The destination folder.
     * @param string $filename The destination file name.
     */
    private static function uploadDirectly(string $imagePath, string $folder, string $filename): void
    {
        $folderPath = storage_path("app/public/{$folder}");
        self::createFolderIfNotExist($folderPath); // Ensure the folder exists.

        $uploadedImagePath = $folderPath . '/' . $filename;

        if (!rename($imagePath, $uploadedImagePath)) {
            abort(500, 'Failed to upload image'); // Upload error.
        }
    }

    /**
     * Create a folder if it does not exist.
     *
     * @param string $folder The folder path.
     */
    private static function createFolderIfNotExist(string $folder): void
    {
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true); // Create folder recursively with full permissions.
        }
    }

    /**
     * Get the list of valid extensions.
     *
     * @return array The valid file extensions.
     */
    public static function getValidExtensions(): array
    {
        return self::$validExtensions;
    }

    /**
     * Set valid extensions dynamically.
     *
     * @param array $extensions The valid extensions.
     */
    public static function setValidExtensions(array $extensions): void
    {
        self::$validExtensions = $extensions;
    }

    /**
     * Get the list of WebP-supported extensions.
     *
     * @return array The WebP-supported extensions.
     */
    public static function getWebpSupportedExtensions(): array
    {
        return self::$webpSupportedExtensions;
    }

    /**
     * Set WebP-supported extensions dynamically.
     *
     * @param array $extensions The supported extensions.
     */
    public static function setWebpSupportedExtensions(array $extensions): void
    {
        self::$webpSupportedExtensions = $extensions;
    }
}
