<?php

namespace App\Services\v1\Files;

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * ImageLoader provides functionality to register routes for loading images
 * and serving them as binary file responses.
 */
class ImageLoader
{
    /**
     * Register a route for loading images with a configurable prefix.
     *
     * @param string $routePrefix The prefix for the route (default is 'storage').
     * @return void
     */
    public static function register(string $routePrefix = 'storage'): void
    {
        // Dynamically register a route to serve images.
        Route::get("{$routePrefix}/{prefix}/{file}", function (string $prefix, string $file) {
            // Use the `webLoad` method to serve the file.
            return self::webLoad("{$prefix}/{$file}");
        });
    }

    /**
     * Serve an image file as a binary response.
     *
     * @param string $path The relative path to the file.
     * @param bool $ignorePublicPath Whether to ignore the default public storage path.
     * @return BinaryFileResponse The binary response for the requested file.
     */
    public static function webLoad(string $path, bool $ignorePublicPath = false): BinaryFileResponse
    {
        // If not ignoring the public path, prepend the storage path.
        if (!$ignorePublicPath) {
            $path = storage_path() . "/app/public/" . $path;
        }

        // Abort with a 404 response if the file does not exist.
        if (!file_exists($path)) {
            throw NotFoundHttpException::fromStatusCode(404,'unable to find the image file.');
        }

        // Serve the file as a binary response.
        return response()->file($path);
    }
}
