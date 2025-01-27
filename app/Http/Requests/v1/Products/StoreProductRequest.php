<?php

namespace App\Http\Requests\v1\Products;

use App\Services\v1\Files\ImageUploader;
use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreProductRequest validates incoming data for creating a new product.
 * Ensures all required fields are provided and properly formatted.
 */
class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool Always returns true, allowing all requests to proceed to validation.
     */
    public function authorize(): bool
    {
        return true; // Authorization is always allowed for this request.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed> The validation rules for the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',                 // The name field is mandatory.
                'string',                   // Must be a valid string.
                'max:255',                  // Cannot exceed 255 characters.
                'unique:products,name',     // Must be unique in the products table.
            ],
            'description' => [
                'nullable',                 // Optional field; can be null.
                'string',                   // If provided, it must be a string.
            ],
            'image' => [
                'required',                 // The image field is mandatory.
                'file',                     // Must be a file.
                'image',                    // Must be an image file.
                'mimes:' . implode(',', ImageUploader::getValidExtensions()), // Allowed file types as defined in ImageUploader.
                'max:2048',                 // Maximum file size is 2MB.
            ],
            'price' => [
                'required',                 // The price field is mandatory.
                'numeric',                  // Must be a valid number.
                'min:0',                    // Minimum value is 0.
            ],
            'is_active' => [
                'boolean',                  // Must be true or false if provided.
            ],
        ];
    }
}
