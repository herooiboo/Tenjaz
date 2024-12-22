<?php

namespace App\Http\Requests\v1\Products;

use App\Services\v1\Files\ImageUploader;
use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateProductRequest validates incoming data for updating a product.
 * Ensures that optional fields are provided in the correct format when updated.
 */
class UpdateProductRequest extends FormRequest
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
                'sometimes',                 // Validation applies only if the field is present.
                'nullable',                  // Optional field; can be null.
                'string',                    // If provided, it must be a string.
                'max:255',                   // Cannot exceed 255 characters.
                'unique:products,name,' . $this->product->id, // Must be unique except for the current product.
            ],
            'description' => [
                'sometimes',                 // Validation applies only if the field is present.
                'nullable',                  // Optional field; can be null.
                'string',                    // If provided, it must be a string.
            ],
            'image' => [
                'sometimes',                 // Validation applies only if the field is present.
                'file',                      // Must be a file.
                'image',                     // Must be an image file.
                'mimes:' . implode(',', ImageUploader::getValidExtensions()), // Allowed file types as defined in ImageUploader.
                'max:2048',                  // Maximum file size is 2MB.
            ],
            'price' => [
                'sometimes',                 // Validation applies only if the field is present.
                'nullable',                  // Optional field; can be null.
                'numeric',                   // If provided, it must be a number.
                'min:0',                     // Minimum value is 0.
            ],
            'is_active' => [
                'sometimes',                 // Validation applies only if the field is present.
                'boolean',                   // Must be true or false if provided.
            ],
        ];
    }
}
