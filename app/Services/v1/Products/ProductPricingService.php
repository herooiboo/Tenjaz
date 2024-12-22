<?php

namespace App\Services\v1\Products;

/**
 * ProductPricingService calculates product prices based on user type and applicable discounts.
 */
class ProductPricingService
{
    /**
     * Adjust the product price based on the authenticated user's discount.
     *
     * @param float $price The original product price.
     * @return float The adjusted price after applying the user's discount.
     */
    public static function handle(float $price): float
    {
        // Retrieve the authenticated user's type, if available.
        if ($userType = auth()?->user()?->getType()) {
            // Apply the user's discount percentage and round to 2 decimal places.
            return round($price * (1 - $userType->getDiscountPercentage()), 2);
        }

        // If no authenticated user, return the original price rounded to 2 decimal places.
        return round($price, 2);
    }
}
