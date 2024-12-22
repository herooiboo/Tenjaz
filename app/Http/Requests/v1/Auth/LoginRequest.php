<?php

namespace App\Http\Requests\v1\Auth;

use App\Enums\UserTypeEnum;
use App\Rules\ValidateEnumString;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * LoginRequest validates the incoming login request data.
 * Ensures only properly formatted and existing credentials are processed.
 */
class LoginRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required',        // The username field is mandatory.
                'string',          // It must be a valid string.
                'exists:users,username', // The username must exist in the 'users' table.
            ],
            'password' => [
                'required', // The password field is mandatory.
                'string',   // It must be a valid string.
            ],
        ];
    }
}
