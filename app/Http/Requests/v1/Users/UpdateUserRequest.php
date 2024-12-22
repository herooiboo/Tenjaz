<?php

namespace App\Http\Requests\v1\Users;

use App\Enums\UserTypeEnum;
use App\Rules\ValidateEnumString;
use App\Services\v1\Files\ImageUploader;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateUserRequest validates the incoming data for updating a user.
 * Ensures that optional fields are provided in the correct format when updated.
 */
class UpdateUserRequest extends FormRequest
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
                'sometimes',                // Validation applies only if the field is present.
                'string',                   // Must be a valid string.
                'max:255',                  // Maximum length is 255 characters.
            ],
            'username' => [
                'sometimes',                // Validation applies only if the field is present.
                'string',                   // Must be a valid string.
                'max:255',                  // Maximum length is 255 characters.
                'unique:users,username,' . $this->route('user'), // Must be unique except for the current user.
            ],
            'password' => [
                'sometimes',                // Validation applies only if the field is present.
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
                'sometimes',                // Validation applies only if the field is present.
                new ValidateEnumString(UserTypeEnum::class), // Must be a valid value from the UserTypeEnum.
            ],
            'is_active' => [
                'sometimes',                // Validation applies only if the field is present.
                'boolean',                  // Must be true or false if provided.
            ],
        ];
    }
}
