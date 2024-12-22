<?php

namespace App\DTO;

use Illuminate\Contracts\Auth\Authenticatable;

    // A Data Transfer Object (DTO) for encapsulating authenticated user details.
    // This class provides a structured way to manage authenticated user data and their token.
class AuthenticatedUserDTO
{
    /**
     * Constructor to initialize the authenticated user details.
     *
     * @param Authenticatable $authenticatable The authenticated user instance.
     * @param string $token The authentication token associated with the user.
     */
    public function __construct(
        private Authenticatable $authenticatable,
        private string $token
    )
    {
    }

    /**
     * Get the authenticated user instance.
     *
     * @return Authenticatable The user object implementing the Authenticatable interface.
     */
    public function getAuthenticatedUser(): Authenticatable
    {
        return $this->authenticatable;
    }

    /**
     * Set the authenticated user instance.
     *
     * @param Authenticatable $authenticatable The new authenticated user instance.
     * @return AuthenticatedUserDTO The current instance for method chaining.
     */
    public function setAuthenticatedUser(Authenticatable $authenticatable): static
    {
        $this->authenticatable = $authenticatable;
        return $this;
    }

    /**
     * Get the authentication token.
     *
     * @return string The user's authentication token.
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set the authentication token.
     *
     * @param string $token The new token value.
     * @return AuthenticatedUserDTO The current instance for method chaining.
     */
    public function setToken(string $token): static
    {
        $this->token = $token;
        return $this;
    }
}