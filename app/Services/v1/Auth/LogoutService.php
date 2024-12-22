<?php

namespace App\Services\v1\Auth;

use App\Contracts\Auth\AuthUserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * LogoutService handles the revocation of authentication tokens for the user.
 * Provides functionality for logging out from a single session or all sessions.
 */
class LogoutService extends AuthService
{
    /**
     * @var AuthUserRepositoryInterface The repository for managing user authentication tokens.
     */
    private AuthUserRepositoryInterface $authUserRepository;

    /**
     * Constructor injection with default repository binding.
     *
     * @throws BindingResolutionException If the repository binding fails.
     */
    public function __construct()
    {
        // Resolve the default implementation for AuthUserRepositoryInterface.
        $this->authUserRepository = app()->make(UserRepository::class);
    }

    /**
     * Set a custom authentication user repository dynamically.
     *
     * @param AuthUserRepositoryInterface|null $repository The custom repository to use.
     * @return static Returns the instance for method chaining.
     */
    public function setAuthUserRepository(?AuthUserRepositoryInterface $repository): static
    {
        if ($repository) {
            $this->authUserRepository = $repository;
        }
        return $this;
    }

    /**
     * Revoke the current user's token using the repository.
     *
     * @param Authenticatable $user The user whose current token is to be revoked.
     * @return bool True if the token was successfully revoked, false otherwise.
     */
    public function logout(Authenticatable $user): bool
    {
        // Use the repository to revoke the current token for the given user.
        return $this->authUserRepository->setModel($user)->revokeCurrentToken();
    }

    /**
     * Revoke all tokens for the user using the repository.
     *
     * @param Authenticatable $user The user whose tokens are to be revoked.
     * @return bool True if all tokens were successfully revoked, false otherwise.
     */
    public function logoutAll(Authenticatable $user): bool
    {
        // Use the repository to revoke all tokens for the given user.
        return $this->authUserRepository->setModel($user)->revokeAllTokens();
    }
}
