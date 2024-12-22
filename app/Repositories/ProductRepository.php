<?php

namespace App\Repositories;

use App\Models\Product;
use App\Services\v1\Files\ImageUploader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * ProductRepository extends the BaseRepository to manage product-specific operations.
 * Adds additional logic for handling product images and slugs during creation and update.
 */
class ProductRepository extends BaseRepository
{
    /**
     * Constructor to initialize the repository with the Product model.
     *
     * @param Product $product The product model instance.
     */
    public function __construct(Product $product)
    {
        parent::__construct($product); // Call the parent constructor to set the model.
    }

    /**
     * Create a new product with additional processing for image and slug.
     *
     * @param array $data The product data to create.
     * @return Model The created product instance.
     */
    public function create(array $data): Model
    {
        // Upload the product image and store the file path in the 'image' field.
        $data['image'] = ImageUploader::upload($data['image'], 'products', 'TENJAZ_', false);

        // Generate a slug from the product name and store it in the 'slug' field.
        $data['slug'] = Str::slug($data['name']);

        // Call the parent `create` method to create the product.
        return $this->model->create($data);
    }

    /**
     * Update an existing product with additional processing for image and slug.
     *
     * @param int $id The ID of the product to update.
     * @param array $data The product data to update.
     * @return bool True if the update was successful, false otherwise.
     */
    public function update(int $id, array $data): bool
    {
        // If a new image is provided, upload it and update the 'image' field.
        if (isset($data['image'])) {
            $data['image'] = ImageUploader::upload($data['image'], 'products', 'TENJAZ_', false);
        }

        // If the product name is provided, generate a new slug and update the 'slug' field.
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Call the parent `update` method to update the product.
        return $this->model->findOrFail($id)->update($data);
    }
}
