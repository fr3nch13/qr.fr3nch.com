<?php
declare(strict_types=1);

namespace App\Model\Validation;

use Cake\Validation\Validator;

class QrValidator extends Validator
{
    /**
     * Validates the a key is formatted properly.
     *
     * @param string $value The value to test.
     * @param array<string, mixed> $context The context to test the value within.
     * @return string|bool
     */
    public static function qrKey(string $value, array $context = []): string|bool
    {
        if (str_contains($value, ' ')) {
            return __('Value cannot have a space in it.');
        }

        return true;
    }

    /**
     * Validates the URL fields.
     * I created the custom one, so I can include protocols
     * other than just http(s)
     *
     * @param string $value The value to test.
     * @param array<string, mixed> $context The context to test the value within.
     * @return string|bool
     */
    public static function qrUrl(string $value, array $context = []): string|bool
    {
        // Pretty lenient to allow app links
        // protocol://[string of non-space chars]
        if (!preg_match('%^[a-f0-9]\://[^\s]+$%iu', $value)) {
            return __('The URL is invalid.');
        }

        return true;
    }
}
