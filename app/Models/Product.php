<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Product represents the product entity in the database.
 * It defines the attributes that can be mass-assigned and their data types.
 */
class Product extends Model
{
    use HasFactory; // Enables the use of Laravel's factory for seeding and testing.

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string> List of fillable attributes.
     */
    protected $fillable = [
        'name',          // The name of the product.
        'description',   // A brief description of the product.
        'image',         // Path to the product's image.
        'price',         // Price of the product.
        'slug',          // SEO-friendly identifier for the product.
        'is_active',     // Indicates whether the product is active.
    ];

    /**
     * The attributes that should be cast to specific data types.
     *
     * @var array<string, string> Attribute casts.
     */
    protected $casts = [
        'is_active' => 'boolean',  // Casts the `is_active` attribute to a boolean.
        'price' => 'decimal:2',   // Casts the `price` attribute to a decimal with two decimal places.
    ];
}
