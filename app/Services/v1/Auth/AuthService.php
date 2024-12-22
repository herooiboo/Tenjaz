<?php

namespace App\Services\v1\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use InvalidArgumentException;

/**
 * AuthService is an abstract class that provides base functionality
 * for authentication-related services.
 */
abstract class AuthService
{
    /**
     * @var Authenticatable The authentication model instance.
     */
    protected Authenticatable $authModel;

    /**
     * @var string The field used to lookup users during authentication (default: 'username').
     */
    protected string $lookupField = 'username';

    /**
     * @var string The authentication guard to use (default: 'sanctum').
     */
    protected string $guard = 'sanctum';

    /**
     * Set the authentication model.
     *
     * @param Authenticatable $model The model used for authentication.
     * @return static Returns the instance for method chaining.
     */
    public function setAuthModel(Authenticatable $model): static
    {
        $this->authModel = $model;
        return $this;
    }

    /**
     * Get the authentication model.
     *
     * @return Authenticatable|false Returns the model or `false` if not set.
     */
    public function getAuthModel(): Authenticatable|false
    {
        return $this->authModel ?? false;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return Authenticatable|null Returns the authenticated user or `null` if not authenticated.
     */
    protected function getAuthUser(): ?Authenticatable
    {
        return auth($this->guard)->user();
    }

    /**
     * Set the authentication guard.
     *
     * @param string $guard The guard to use for authentication.
     * @return static Returns the instance for method chaining.
     */
    public function setGuard(string $guard): static
    {
        $this->guard = $guard;
        return $this;
    }

    /**
     * Get the authentication guard or throw an exception if not set.
     *
     * @return string The authentication guard.
     * @throws InvalidArgumentException If the guard is not set.
     */
    public function getGuardOrFail(): string
    {
        if (!empty($this->guard)) {
            return $this->guard;
        }

        throw new InvalidArgumentException('Guard not found.');
    }

    /**
     * Set the lookup field for authentication (e.g., username, email).
     *
     * @param string $lookupField The field to use for user lookup.
     * @return static Returns the instance for method chaining.
     */
    public function setLookupField(string $lookupField): static
    {
        $this->lookupField = $lookupField;
        return $this;
    }

    /**
     * Get the lookup field or throw an exception if not set.
     *
     * @return string The lookup field for authentication.
     * @throws InvalidArgumentException If the lookup field is not set.
     */
    public function getLookupField(): string
    {
        if (!empty($this->lookupField)) {
            return $this->lookupField;
        }

        throw new InvalidArgumentException('Lookup field not found.');
    }
}
