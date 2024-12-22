<?php

use App\Http\Middleware\RejectAuthenticatedUsers;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Services\v1\Files\ImageLoader;

/**
 * Configure and bootstrap the Laravel application.
 * The configuration includes routing, middleware, and exception handling.
 */
return Application::configure(basePath: dirname(__DIR__))

    // Configure application routing
    ->withRouting(
        using: function () {
            // Register a route for serving images with the ImageLoader service.
            ImageLoader::register();

            // Define API routes for version 1 with the 'api' middleware.
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api.php'));

            // Define web routes with the 'web' middleware.
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
        // Load console commands for Artisan CLI.
        commands: __DIR__.'/../routes/console.php',
    )

    // Configure middleware aliases for the application.
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'guest' => RejectAuthenticatedUsers::class, // Alias 'guest' for RejectAuthenticatedUsers middleware.
        ]);
    })

    // Configure exception handling for the application.
    ->withExceptions(function (Exceptions $exceptions) {
        // Exception handling logic can be added here if needed.
    })

    // Finalize and create the application instance.
    ->create();
