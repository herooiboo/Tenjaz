<?php

namespace App\Exceptions\Handlers;

use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * GenericExceptionHandler is responsible for handling exceptions
 * and providing a consistent JSON response across the application.
 * Includes debug information if the app is in debug mode.
 */
class GenericExceptionHandler
{
    /**
     * Handle the given exception and return a formatted JSON response.
     *
     * @param Throwable $exception The exception to handle.
     * @return JsonResponse A JSON response with error details.
     */
    public function handle(Throwable $exception): JsonResponse
    {
        // Determine the HTTP status code:
        // - Use the exception's status code if it's an HttpException.
        // - Default to 500 for other exception types.
        $statusCode = $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException
            ? $exception->getStatusCode()
            : 500;

        // Extract the exception message, or use a generic message if not available.
        $message = $exception->getMessage() ?: 'Something Went Wrong.';

        // If the app is in debug mode, include detailed debug information.
        $debug = config('app.debug') ? [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ] : [];

        // Return a JSON response with error details:
        // - `success` indicates the request failed.
        // - `message` provides a human-readable error message.
        // - `data` is empty (no additional data to include for errors).
        // - `debug` contains debug details (only if in debug mode).
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => [],
            'debug' => $debug,
        ], $statusCode);
    }
}
