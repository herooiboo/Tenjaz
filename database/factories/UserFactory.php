<?php

namespace Database\Factories;

use App\Enums\UserTypeEnum; // Importing the UserTypeEnum for assigning user types.
use Illuminate\Database\Eloquent\Factories\Factory; // Base Factory class for creating model instances.
use Illuminate\Support\Facades\Hash; // Importing Hash for password hashing.
use Illuminate\Support\Str; // Importing Str for string manipulation.

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 * Factory class for the `User` model.
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     * Allows a shared password for all users in testing.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     * Specifies the default attributes for `User` model instances created by the factory.
     *
     * @return array The default state as an associative array.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(), // Generates a fake name for the user.
            'username' => $this->faker->unique()->userName(), // Generates a unique fake username.
            'password' => bcrypt('password'), // Sets a default hashed password ('password') for testing.
            'avatar' => $this->ensureMockupImageExists(), // Ensures a default mockup image exists and uses it as the avatar.
            'type' => $this->faker->randomElement([ // Randomly assigns a user type.
                UserTypeEnum::Normal->toString(), // Normal user type.
                UserTypeEnum::Silver->toString(), // Silver user type.
                UserTypeEnum::Gold->toString(), // Gold user type.
            ]),
            'is_active' => $this->faker->boolean(90), // Sets a 90% chance of the user being active.
        ];
    }

    /**
     * Ensure a mockup image exists in the target directory for user avatars.
     * If the mockup does not exist, it copies the file to the correct location.
     *
     * @return string The relative path to the mockup image.
     */
    private function ensureMockupImageExists(): string
    {
        $sourcePath = storage_path('stamps/mockup.png'); // Define the source path for the mockup image.
        $destinationDirectory = storage_path('app/public/mockup'); // Define the target directory for user avatars.
        $destinationPath = $destinationDirectory . '/mockup.png'; // Full path to the target mockup image.

        // Check if the destination file already exists.
        if (!file_exists($destinationPath)) {
            // Ensure the target directory exists.
            if (!file_exists($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true); // Create the directory with full permissions.
            }

            // Copy the file from the source to the destination.
            if (file_exists($sourcePath)) {
                copy($sourcePath, $destinationPath); // Copy the mockup image to the target location.
            } else {
                abort(404, 'Source mockup image not found.'); // Abort with a 404 error if the source file is missing.
            }
        }

        // Return the relative path for use in the factory.
        return 'mockup/mockup.png';
    }
}
