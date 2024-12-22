<?php

namespace App\DTO;

use Illuminate\Contracts\Auth\Authenticatable;

// A Data Transfer Object (DTO) for encapsulating authentication credentials
// DTOs are used to transfer data between layers in a structured way.

class AuthCredentialsDTO
{
    /**
     * Constructor to initialize authentication credentials.
     *
     * @param string $authFieldValue The authentication field value (e.g., username or email).
     * @param string $password The user's password.
     */
    public function __construct(
        private string $authFieldValue,
        private string $password
    )
    {
    }

    /**
     * Create an instance of AuthCredentialsDTO from an array.
     *
     * @param array $data The input data array containing 'username' and 'password'.
     * @return AuthCredentialsDTO A new instance of the DTO with the provided data.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            authFieldValue: $data['username'] ?? '',
            password: $data['password'] ?? ''
        );
    }

    /**
     * Get the authentication field value.
     *
     * @return string The value of the authentication field.
     */
    public function getAuthFieldValue(): string
    {
        return $this->authFieldValue;
    }

    /**
     * Set the authentication field value.
     *
     * @param string $authFieldValue The new value for the authentication field.
     * @return AuthCredentialsDTO The current instance for method chaining.
     */
    public function setAuthFieldValue(string $authFieldValue): static
    {
        $this->authFieldValue = $authFieldValue;
        return $this;
    }

    /**
     * Set the password.
     *
     * @param string $password The new password value.
     * @return AuthCredentialsDTO The current instance for method chaining.
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }
    /**
     * Get the password.
     *
     * @return string The user's password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

}