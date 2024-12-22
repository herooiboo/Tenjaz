<?php

namespace App\Exceptions;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use App\Exceptions\Handlers\ValidationExceptionHandler;
use App\Exceptions\Handlers\GenericExceptionHandler;

/**
 * CustomExceptionHandler is the main exception handler for the application.
 * It ensures consistent error responses by delegating to specific handlers for validation and generic exceptions.
 * Provides clear error messages for users while simplifying debugging for developers.
 */
class CustomExceptionHandler implements ExceptionHandler
{
    /**
     * Constructor to inject exception handlers for validation and generic exceptions.
     *
     * @param ValidationExceptionHandler $validationHandler Handles validation-related exceptions.
     * @param GenericExceptionHandler $genericHandler Handles other generic exceptions.
     */
    public function __construct(
        protected ValidationExceptionHandler $validationHandler =  new ValidationExceptionHandler(),
        protected GenericExceptionHandler $genericHandler =  new GenericExceptionHandler(),
    ) {}

    /**
     * Render the exception into an HTTP response.
     *
     * @param Request $request The incoming HTTP request.
     * @param Throwable $e The exception to handle.
     * @return JsonResponse|Response The formatted response.
     */
    public function render($request, Throwable $e): JsonResponse|Response
    {
        // If the exception is a ValidationException, delegate to the validation handler.
        if ($e instanceof ValidationException) {
            return $this->validationHandler->handle($e);
        }

        // For other exceptions, delegate to the generic handler.
        return $this->genericHandler->handle($e);
    }

    /**
     * Determine if the exception should be reported.
     *
     * @param Throwable $e The exception to check.
     * @return bool Always true, indicating all exceptions should be reported.
     */
    public function shouldReport(Throwable $e): bool
    {
        return true;
    }

    /**
     * Report the exception (currently not implemented for this task).
     *
     * @param Throwable $e The exception to report.
     * @return void
     */
    public function report(Throwable $e): void
    {
        // Reporting logic can be implemented here, e.g., logging or sending to a monitoring tool like sentry.
        // Not required for this task.
    }

    /**
     * Render the exception for console output (currently not implemented for this task).
     *
     * @param mixed $output The console output stream.
     * @param Throwable $e The exception to render.
     * @return void
     */
    public function renderForConsole($output, Throwable $e): void
    {
        // Logic to render exceptions for console applications could be added here.
        // Not required for this task.
    }
}
