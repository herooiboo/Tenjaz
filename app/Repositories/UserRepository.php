<?php

namespace App\Repositories;

use App\Contracts\Auth\AuthUserRepositoryInterface;
use App\Models\User;
use App\Services\v1\Files\ImageUploader;
use Illuminate\Database\Eloquent\Model;

/**
 * UserRepository manages user-specific operations and implements authentication-related methods
 * defined in the AuthUserRepositoryInterface.
 */
class UserRepository extends BaseRepository implements AuthUserRepositoryInterface
{
    /**
     * Potential enhancement:
     * If there are multiple user types requiring the same authentication logic,
     * consider wrapping AuthUserRepositoryInterface functions into a shared trait.
     */

    /**
     * Constructor to initialize the repository with the User model.
     *
     * @param User $user The user model instance.
     */
    public function __construct(User $user)
    {
        parent::__construct($user); // Call the parent constructor to set the model.
    }

    /**
     * Revoke the current token for the authenticated user.
     *
     * @return bool True if the token was successfully deleted, false otherwise.
     */
    public function revokeCurrentToken(): bool
    {
        return $this->model->currentAccessToken()->delete();
    }

    /**
     * Revoke all tokens for the authenticated user.
     *
     * @return bool True if all tokens were successfully deleted, false otherwise.
     */
    public function revokeAllTokens(): bool
    {
        return $this->model->tokens()->delete();
    }

    /**
     * Create a new user with additional processing for the avatar.
     *
     * @param array $data The user data to create.
     * @return Model The created user instance.
     */
    public function create(array $data): Model
    {
        if (isset($data['avatar'])) {
            // Upload the avatar and store the file path in the 'avatar' field.
            $data['avatar'] = ImageUploader::upload($data['avatar'], 'users', 'TENJAZ_', false);
        }

        // Call the parent `create` method to create the user.
        return $this->model->create($data);
    }

    /**
     * Update an existing user with additional processing for the avatar.
     *
     * @param int $id The ID of the user to update.
     * @param array $data The user data to update.
     * @return bool True if the update was successful, false otherwise.
     */
    public function update(int $id, array $data): bool
    {
        if (isset($data['avatar'])) {
            // Upload the avatar and store the file path in the 'avatar' field.
            $data['avatar'] = ImageUploader::upload($data['avatar'], 'users', 'TENJAZ_', false);
        }

        // Call the parent `update` method to update the user.
        return $this->model->findOrFail($id)->update($data);
    }
}
