<?php

namespace App\Models;

use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User model represents the application user and extends Laravel Authenticatable class.
 * Includes functionality for user authentication and attributes handling.
 */
class User extends Authenticatable
{
    use HasFactory, HasApiTokens; // Enables factories for testing and Sanctum for API token authentication.

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',               // Casts `is_active` to a boolean for consistency.
        'type' => UserTypeEnum::class,          // Maps the `type` attribute to the corresponding enum instance.
        'password' => 'hashed',                 // Automatically hashes the password when set.
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',                             // Hides the password from API responses and serialization.
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',                                 // The user's name.
        'username',                             // The user's username.
        'password',                             // The user's password.
        'avatar',                               // The URL or path to the user's avatar.
        'type',                                 // The user's type (e.g., Normal, Silver, Gold).
        'is_active',                            // Indicates whether the user is active.
    ];

    /**
     * Accessor for the `type` attribute.
     *
     * @return UserTypeEnum Returns the user's type as an enum instance.
     */
    public function getType(): UserTypeEnum
    {
        return $this->type;
    }

    /**
     * Mutator for the `type` attribute.
     * Accepts a string value of the enum and converts it to the corresponding enum instance value.
     *
     * @param string $value The string representation of the user type (e.g., 'Normal').
     * @return void
     */
    public function setTypeAttribute(string $value): void
    {
        $this->attributes['type'] = UserTypeEnum::fromString($value)->value; // Converts string to enum value.
    }
}
