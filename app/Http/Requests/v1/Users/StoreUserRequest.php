<?php

namespace App\Http\Requests\v1\Users;

use App\Enums\UserTypeEnum;
use App\Rules\ValidateEnumString;
use App\Services\v1\Files\ImageUploader;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreUserRequest validates the incoming data for creating a new user.
 * Ensures that all required fields are present and correctly formatted.
 */
class StoreUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string> The validation rules for the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',                 // The name field is mandatory.
                'string',                   // Must be a valid string.
                'max:255',                  // Maximum length is 255 characters.
            ],
            'username' => [
                'required',                 // The username field is mandatory.
                'string',                   // Must be a valid string.
                'max:255',                  // Maximum length is 255 characters.
                'unique:users',             // Must be unique in the users table.
            ],
            'password' => [
                'required',                 // The password field is mandatory.
                'string',                   // Must be a valid string.
                'min:8',                    // Minimum length is 8 characters.
            ],
            'avatar' => [
                'sometimes',                // Validation applies only if the field is present.
                'nullable',                 // Optional field; can be null.
                'file',                     // Must be a file.
                'image',                    // Must be an image file.
                'mimes:' . implode(',', ImageUploader::getValidExtensions()), // Allowed file types as defined in ImageUploader.
                'max:2048',                 // Maximum file size is 2MB.
            ],
            'type' => [
                'required',                 // The type field is mandatory.
                new ValidateEnumString(UserTypeEnum::class), // Must be a valid value from the UserTypeEnum.
            ],
            'is_active' => [
                'boolean',                  // Must be true or false if provided.
            ],
        ];
    }
}
