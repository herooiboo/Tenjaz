<?php

namespace App\Response;

use Illuminate\Support\Facades\Response;

/**
 * ErrorResponseMacro defines and registers a custom `error` response macro
 * for providing a consistent structure for error responses in API responses.
 */
class ErrorResponseMacro
{
    /**
     * Registers the `error` macro on the `Response` facade.
     *
     * The macro provides a standardized JSON structure for error responses.
     */
    public static function register(): void
    {
        Response::macro('error', function ($message = null, $statusCode = 500, $data = []) {
            // If additional data is provided, include it in the response.
            if ($data != []) {
                return Response::json([
                    'success' => false,   // Indicates the request failed.
                    'message' => $message, // The error message to provide context.
                    'data' => $data,       // Additional error details or metadata.
                ], $statusCode);          // HTTP status code for the response.
            }

            // Default response structure if no additional data is provided.
            return Response::json([
                'success' => false,   // Indicates the request failed.
                'message' => $message, // The error message to provide context.
            ], $statusCode);          // HTTP status code for the response.
        });
    }
}
