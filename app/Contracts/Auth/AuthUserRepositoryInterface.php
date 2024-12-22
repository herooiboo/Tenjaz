<?php

namespace App\Contracts\Auth;

use Illuminate\Support\Collection;

// Define the interface for authentication-related repository methods.
// This promotes consistency and contract-driven development.
interface AuthUserRepositoryInterface
{
    /**
     * Revoke the current token of the authenticated user.
     *
     * @return bool True if the current token is successfully revoked, false otherwise.
     */
    public function revokeCurrentToken(): bool;

    /**
     * Revoke all tokens of the authenticated user.
     *
     * @return bool True if all tokens are successfully revoked, false otherwise.
     */
    public function revokeAllTokens(): bool;
}