<?php

namespace App\Response;

use Illuminate\Support\Facades\Response;

/**
 * SuccessResponseMacro defines and registers a custom `success` response macro
 * for providing a consistent structure for successful API responses.
 */
class SuccessResponseMacro
{
    /**
     * Registers the `success` macro on the `Response` facade.
     *
     * The macro provides a standardized JSON structure for successful responses.
     */
    public static function register(): void
    {
        Response::macro('success', function ($data = [], $message = null, $statusCode = 200) {
            // If a message is provided, include it in the response.
            if ($message) {
                return Response::json([
                    'success' => true,     // Indicates the request was successful.
                    'message' => $message, // A descriptive success message.
                    'data' => $data,       // The data payload.
                ], $statusCode);          // HTTP status code for the response.
            }

            // Default response structure if no message is provided.
            return Response::json([
                'success' => true,     // Indicates the request was successful.
                'data' => $data,       // The data payload.
            ], $statusCode);          // HTTP status code for the response.
        });
    }
}
