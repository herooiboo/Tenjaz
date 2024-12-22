<?php

namespace App\Providers;

use App\Response\ErrorResponseMacro;
use App\Response\SuccessResponseMacro;
use Illuminate\Support\ServiceProvider;

/**
 * ResponseMacroServiceProvider registers custom response macros for success and error responses.
 * This ensures a consistent structure for API responses throughout the application.
 *
 */
class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstraps custom response macros during application initialization.
     *
     * The macros are registered to the `Response` facade, allowing them to be
     * used globally within the application.
     */
    public function boot(): void
    {
        // Register the success response macro for standardized success responses.
        SuccessResponseMacro::register();

        // Register the error response macro for standardized error responses.
        ErrorResponseMacro::register();
    }
}
