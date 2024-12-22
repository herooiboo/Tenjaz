<?php

namespace App\Enums;

// Define an enum for user types in the system.
// Enums provide a clean and type-safe way to manage predefined values.
enum UserTypeEnum: int
{
    case Normal = 1; // Default user type with no discounts.
    case Silver = 2; // Silver user with moderate discounts.
    case Gold = 3;   // Gold user with maximum discounts.

    /**
     * Convert the enum value to its string representation.
     *
     * @return string The string representation of the user type.
     */
    public function toString(): string
    {
        return match ($this) {
            self::Normal => 'Normal', // Map the Normal case to 'Normal'.
            self::Silver => 'Silver', // Map the Silver case to 'Silver'.
            self::Gold => 'Gold',     // Map the Gold case to 'Gold'.
        };
    }

    /**
     * Create an enum instance from a string.
     *
     * @param string $name The string name of the user type.
     * @return ?UserTypeEnum The corresponding enum instance or null if invalid.
     */
    public static function fromString(string $name): ?self
    {
        return match ($name) {
            'Normal' => self::Normal, // Map 'Normal' string to Normal enum.
            'Silver' => self::Silver, // Map 'Silver' string to Silver enum.
            'Gold' => self::Gold,     // Map 'Gold' string to Gold enum.
            default => null,          // Return null for invalid strings.
        };
    }

    /**
     * Get the discount percentage associated with the user type.
     *
     * @return float The discount percentage as a decimal (e.g., 0.1 for 10%).
     */
    public function getDiscountPercentage(): float
    {
        return match ($this) {
            self::Normal => 0,      // Normal users get no discount.
            self::Silver => 0.1,    // Silver users get a 10% discount.
            self::Gold => 0.15,     // Gold users get a 15% discount.
        };
    }
}
