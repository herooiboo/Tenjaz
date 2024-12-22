<?php

namespace App\Providers;

use App\Exceptions\CustomExceptionHandler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

/**
 * ExceptionServiceProvider ensures consistent error handling across the application
 * by binding the custom exception handler to Laravel's ExceptionHandler contract.
 */
class ExceptionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * This method binds the `CustomExceptionHandler` to the `ExceptionHandler` interface,
     * ensuring that all exceptions are handled by the custom handler.
     */
    public function register(): void
    {
        $this->app->singleton(ExceptionHandler::class, function () {
            // Use a singleton to ensure only one instance of the custom handler is created.
            return new CustomExceptionHandler();
        });
    }

    /**
     * Bootstrap services.
     *
     * This method is used to perform any actions required during the application bootstrapping.
     * Currently, no additional bootstrapping logic is required for this provider.
     */
    public function boot(): void
    {
        //
    }
}
