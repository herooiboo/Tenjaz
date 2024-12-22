<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory; // Importing the base Factory class to create model instances.
use Illuminate\Support\Str; // Importing Str for string manipulation (e.g., generating slugs).

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 * Factory class for the `Product` model.
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     * This method specifies the default values for attributes of the `Product` model.
     *
     * @return array<string, mixed> An associative array representing the model's attributes.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true), // Generates a fake name with 3 random words as a string.
            'description' => $this->faker->paragraph(), // Generates a fake paragraph for the product description.
            'image' => $this->ensureMockupImageExists(), // Ensures a mockup image exists and assigns its path.
            'price' => $this->faker->randomFloat(2, 10, 1000), // Generates a random price between 10 and 1000 with 2 decimal places.
            'slug' => Str::slug($this->faker->unique()->words(3, true)), // Generates a unique slug based on 3 random words.
            'is_active' => $this->faker->boolean(90), // 90% chance of being active (true), 10% chance of being inactive (false).
        ];
    }

    /**
     * Ensures the mockup image exists in the storage directory for products.
     * If the image doesn't exist, it copies the mockup from a source directory.
     *
     * @return string The relative path to the mockup image in the public storage folder.
     */
    private function ensureMockupImageExists(): string
    {
        $sourcePath = storage_path('stamps/mockup.png'); // The source path for the mockup image.
        $destinationDirectory = storage_path('app/public/mockup'); // The target directory for storing product images.
        $destinationPath = $destinationDirectory . '/mockup.png'; // Full path for the mockup image in the target directory.

        // Check if the destination file already exists.
        if (!file_exists($destinationPath)) {
            // Ensure the target directory exists.
            if (!file_exists($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true); // Create the directory with full permissions if it doesn't exist.
            }

            // Copy the file from the source to the destination directory.
            if (file_exists($sourcePath)) {
                copy($sourcePath, $destinationPath); // Copy the mockup image from the source to the target location.
            } else {
                abort(404, 'Source mockup image not found.'); // Abort with a 404 error if the source image is missing.
            }
        }

        // Return the relative path to the mockup image for database storage.
        return 'mockup/mockup.png';
    }
}
