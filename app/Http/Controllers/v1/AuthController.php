<?php

namespace App\Http\Controllers\v1;

use App\DTO\AuthCredentialsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\LoginRequest;
use App\Http\Resources\v1\Auth\LoginResource;
use App\Services\v1\Auth\LoginService;
use App\Services\v1\Auth\LogoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * AuthController handles user authentication actions, including login and logout.
 * It uses services for business logic and provides clean JSON responses.
 */
class AuthController extends Controller
{
    /**
     * Constructor to inject LoginService and LogoutService.
     *
     * @param LoginService $loginService Handles login-related logic.
     * @param LogoutService $logoutService Handles logout-related logic.
     */
    public function __construct(
        private readonly LoginService $loginService,   // Login service for handling authentication.
        private readonly LogoutService $logoutService // Logout service for managing user session termination.
    )
    {
    }

    /**
     * Login a user with validated credentials.
     *
     * @param LoginRequest $request The incoming request containing login data.
     * @return JsonResponse A JSON response with user details and a token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return response()->success( // Utilize a custom success response macro for consistency.
            new LoginResource($this->loginService->login(
                AuthCredentialsDTO::fromArray($request->validated()) // Create a DTO from validated input.
            ))
        );
    }

    /**
     * Logout the authenticated user.
     *
     * @param Request $request The incoming request containing the authenticated user.
     * @return JsonResponse A JSON response indicating the logout result.
     */
    public function logout(Request $request): JsonResponse
    {
        // Call the logout service to terminate the user's session.
        if ($this->logoutService->logout($request->user())) {
            return response()->success( // Respond with a success message if logout succeeds.
                'Logged out Successfully'
            );
        }

        // Respond with an error message if logout fails.
        return response()->error('Unable to Logout');
    }
}
