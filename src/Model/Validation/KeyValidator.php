<?php
declare(strict_types=1);

namespace App\Model\Validation;

use Cake\Validation\Validator;

class KeyValidator extends Validator
{
    /**
     * Validates the a key is formatted properly.
     *
     * @param string $value The value to test.
     * @param array<string, mixed> $context The context to test the value within.
     * @return string|bool
     */
    public static function characters(string $value, array $context = []): string|bool
    {
        if (str_contains($value, ' ')) {
            return __('Value cannot have a space in it.');
        }

        return true;
    }
}
