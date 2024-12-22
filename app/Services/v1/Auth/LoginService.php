<?php

namespace App\Services\v1\Auth;

use App\Contracts\Auth\AuthUserRepositoryInterface;
use App\DTO\AuthCredentialsDTO;
use App\DTO\AuthenticatedUserDTO;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * LoginService handles user authentication and token generation.
 * It extends AuthService for common authentication functionality.
 */
class LoginService extends AuthService
{
    /**
     * @var AuthUserRepositoryInterface The repository for user authentication data.
     */
    private AuthUserRepositoryInterface $authUserRepository;

    /**
     * Constructor to initialize the LoginService with the default UserRepository.
     *
     * @throws BindingResolutionException If the UserRepository binding fails.
     */
    public function __construct()
    {
        $this->authUserRepository = app()->make(UserRepository::class);
    }

    /**
     * Set a custom AuthUserRepository.
     *
     * @param AuthUserRepositoryInterface|null $repository The repository to use.
     * @return static Returns the instance for method chaining.
     */
    public function setAuthUserRepository(?AuthUserRepositoryInterface $repository): static
    {
        $this->authUserRepository = $repository;
        return $this;
    }

    /**
     * Handle user login.
     *
     * @param AuthCredentialsDTO $credentials The user credentials DTO.
     * @return AuthenticatedUserDTO An authenticated user DTO containing user info and token.
     * @throws ValidationException If the credentials are invalid or the user is inactive.
     */
    public function login(AuthCredentialsDTO $credentials): AuthenticatedUserDTO
    {
        // Find the user by the lookup field (e.g., username or email).
        $user = $this->authUserRepository->findByField($this->getLookupField(), $credentials->getAuthFieldValue());

        // Validate if the user is active.
        $this->validateUserActivation($user);

        // Validate the provided password against the stored hash.
        $this->validatePasswordWithUser($user, $credentials);

        // Generate an authentication token for the user.
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return an authenticated user DTO containing the user and token.
        return new AuthenticatedUserDTO($user, $token);
    }

    /**
     * Validate if the user is active.
     *
     * @param Authenticatable $user The user to validate.
     * @throws ValidationException If the user is inactive.
     */
    public function validateUserActivation(Authenticatable $user): void
    {
        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'user' => ['This user is inactive.'],
            ]);
        }
    }

    /**
     * Validate the user's password.
     *
     * @param Authenticatable $user The user to validate.
     * @param AuthCredentialsDTO $credentials The credentials DTO.
     * @throws ValidationException If the password is incorrect or the user does not exist.
     */
    public function validatePasswordWithUser(Authenticatable $user, AuthCredentialsDTO $credentials): void
    {
        if (!$user || !Hash::check($credentials->getPassword(), $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }
    }
}
