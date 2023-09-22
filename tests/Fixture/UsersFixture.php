<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{

    /**
     * @var array<int, array<string, mixed>> The data to insert
     */
    public $data = [
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

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = $this->data;
        parent::init();
    }
}
