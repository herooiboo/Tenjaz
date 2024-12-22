<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * ValidateEnumString is a custom validation rule to validate strings
 * against the allowed cases of a specified enum class.
 */
class ValidateEnumString implements ValidationRule
{
    /**
     * @var array<string> $allowedValues The list of allowed enum string values.
     */
    private array $allowedValues;

    /**
     * Constructor to initialize the validation rule with an enum class.
     *
     * @param string $enumClass The fully qualified class name of the enum.
     * @throws \InvalidArgumentException If the provided class is not a valid enum.
     */
    public function __construct(string $enumClass)
    {
        // Ensure the provided class is a valid enum.
        if (!enum_exists($enumClass)) {
            throw new \InvalidArgumentException("Invalid enum class: {$enumClass}");
        }

        // Extract the string representation of each enum case.
        $this->allowedValues = array_map(
            fn($case) => $case->toString(), // Map each case to its string representation.
            $enumClass::cases()            // Get all cases of the enum.
        );
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute The name of the attribute being validated.
     * @param mixed $value The value of the attribute being validated.
     * @param Closure(string): PotentiallyTranslatedString $fail Callback to indicate validation failure.
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value is not in the list of allowed enum values.
        if (!in_array($value, $this->allowedValues, true)) {
            // Trigger the failure callback with a translated error message.
            $fail(__('The :attribute must be one of the following: :values.', [
                'attribute' => $attribute,                  // The name of the invalid attribute.
                'values' => implode(', ', $this->allowedValues), // List of allowed values.
            ]));
        }
    }
}
