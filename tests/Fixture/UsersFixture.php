<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\I18n\FrozenTime;

/**
 * UsersFixture
 */
class UsersFixture extends CoreFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->import = [
            'model' => 'Users',
        ];

        $this->records = [
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
        parent::init();
    }
}
