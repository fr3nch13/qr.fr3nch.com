<?php
declare(strict_types=1);

namespace App\Migrations\Data;

use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * CategoriesQrCodes records.
 */
class CategoriesQrCodesData
{
    /**
     * Define your data here.
     *
     * @param array<int, array<string, mixed>> $data The data to use to overwrite the default
     * @return array<int, array<string, mixed>> The data to insert
     */
    public function getData(array $data = []): array
    {
        $default = [
            [
                'id' => 1,
                'qr_code_id' => 1,
                'category_id' => 2,
            ],
        ];

        if (empty($data)) {
            $data = $default;
        }

        return $data;
    }
}
