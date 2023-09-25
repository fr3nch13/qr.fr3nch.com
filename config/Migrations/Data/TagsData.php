<?php
declare(strict_types=1);

namespace App\Migrations\Data;

use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * Tags records.
 */
class TagsData
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
                'name' => 'Notebook',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Journal',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Amazon',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Pig',
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
