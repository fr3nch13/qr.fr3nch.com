<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Define your data here.
     *
     * @param array<int, array<string, mixed>> The data to use to overwrite the default
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
                'is_admin' => true,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'name' => 'Regular',
                'email' => 'regular@example.com',
                'password' => (new DefaultPasswordHasher())->hash('admin'),
                'is_admin' => true,
                'created' => date('Y-m-d H:i:s'),
            ],
        ];

        if (empty($data)) {
            $data = $default;
        }

        return $data;
    }

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = $this->getData();
        parent::init();
    }
}
