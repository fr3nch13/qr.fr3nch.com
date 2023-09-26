<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Console\ConsoleIo;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $io = new ConsoleIo();
        $io->out(__('--- Init Fixture: {0} ---', [self::class]));

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
