<?php

namespace App\Exceptions\Handlers;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * ValidationExceptionHandler is responsible for handling validation exceptions.
 * It provides a structured response with validation error details,
 * ensuring users receive clear feedback when input validation fails.
 */
class ValidationExceptionHandler
{
    /**
     * Handle the given validation exception and return a formatted JSON response.
     *
     * @param ValidationException $exception The validation exception to handle.
     * @return JsonResponse A JSON response with validation error details.
     */
    public function handle(ValidationException $exception): JsonResponse
    {
        // Return a JSON response with validation error details:
        // - `success` indicates the validation failed.
        // - `message` provides a human-readable error message.
        // - `data` contains validation error messages keyed by field name.
        // - `debug` is empty because validation errors don't require debug info.
        return response()->json([
            'success' => false,
            'message' => $exception->getMessage(), // The validation error message.
            'data' => $exception->validator->errors()->toArray(), // Validation errors as an array.
            'debug' => [], // No debug info for validation errors.
        ], 422); // Use 422 (Unprocessable Entity) as the HTTP status code for validation errors.
    }
}
