<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\I18n\DateTime;

/**
 * UsersFixture
 */
class UsersFixture extends CoreFixture
{
    /**
     * Table property
     *
     * @var string
     */
    public string $table = 'users';

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->io->out(__('--- Init Fixture: {0} ---', [self::class]));

        $this->records = [
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => (new DefaultPasswordHasher())->hash('admin'),
                'is_admin' => 1,
                'is_active' => 1,
                'created' => new DateTime(),
            ],
            [
                'id' => 2,
                'name' => 'Regular',
                'email' => 'regular@example.com',
                'password' => (new DefaultPasswordHasher())->hash('regular'),
                'is_admin' => 0,
                'is_active' => 1,
                'created' => new DateTime(),
            ],
            [
                'id' => 3,
                'name' => 'Delete Me',
                'email' => 'deleteme@example.com',
                'password' => (new DefaultPasswordHasher())->hash('deleteme'),
                'is_admin' => 0,
                'is_active' => 1,
                'created' => new DateTime(),
            ],
            [
                'id' => 4,
                'name' => 'Inactive User',
                'email' => 'inactive@example.com',
                'password' => (new DefaultPasswordHasher())->hash('inactive'),
                'is_admin' => 0,
                'is_active' => 0,
                'created' => new DateTime(),
            ],
        ];
        parent::init();
    }
}
