<?php
declare(strict_types=1);

namespace App\Migrations\Data;

use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * Users records.
 */
class UsersData
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
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => (new DefaultPasswordHasher())->hash('admin'),
                'is_admin' => 1,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'name' => 'Regular',
                'email' => 'regular@example.com',
                'password' => (new DefaultPasswordHasher())->hash('regular'),
                'is_admin' => 0,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'name' => 'Delete Me',
                'email' => 'deleteme@example.com',
                'password' => (new DefaultPasswordHasher())->hash('deleteme'),
                'is_admin' => 0,
                'created' => date('Y-m-d H:i:s'),
            ],
        ];

        if (empty($data)) {
            $data = $default;
        }

        return $data;
    }
}
