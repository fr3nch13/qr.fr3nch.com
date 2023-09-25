<?php
declare(strict_types=1);

namespace App\Migrations\Data;

use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * Sources records.
 */
class SourcesData
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
                'key' => 'amazon',
                'name' => 'Amazon',
                'description' => 'Books for sale at Amazon',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'key' => 'etsy',
                'name' => 'Etsy',
                'description' => 'Products for sale at my Etsy Store',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
        ];

        if (empty($data)) {
            $data = $default;
        }

        return $data;
    }
}
