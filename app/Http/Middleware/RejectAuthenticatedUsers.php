<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RejectAuthenticatedUsers middleware prevents access to certain routes
 * for users who are already authenticated.
 */
class RejectAuthenticatedUsers
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request The current request instance.
     * @param \Closure $next The next middleware to call.
     * @param string|null $guard Optional guard to check the authenticated user.
     * @return mixed The response after processing the middleware.
     */
    public function handle(Request $request, Closure $next, ?string $guard = null)
    {
        // Check if the user is authenticated for the specified guard.
        if ($request->user($guard)) {
            // If authenticated, return an error response with a 401 Unauthorized status.
            return response()->error('Unauthorized Access', 401);
        }

        // If not authenticated, pass the request to the next middleware or controller.
        return $next($request);
    }
}
