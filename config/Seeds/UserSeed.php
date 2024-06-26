<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * User seed.
 */
class UserSeed extends AbstractSeed
{
    use \App\Migrations\QrSeedTrait;

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $this->checkTable('users');
        $table = $this->table('users');

        $data = [
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => (new DefaultPasswordHasher())->hash('admin'),
                'is_admin' => 1,
                'is_active' => 1,
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'gravatar_email' => 'fr3nchllc@gmail.com',
            ],
            [
                'id' => 2,
                'name' => 'Regular',
                'email' => 'regular@example.com',
                'password' => (new DefaultPasswordHasher())->hash('regular'),
                'is_admin' => 0,
                'is_active' => 1,
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'name' => 'Delete Me',
                'email' => 'deleteme@example.com',
                'password' => (new DefaultPasswordHasher())->hash('deleteme'),
                'is_admin' => 0,
                'is_active' => 1,
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'name' => 'Inactive User',
                'email' => 'inactive@example.com',
                'password' => (new DefaultPasswordHasher())->hash('inactive'),
                'is_admin' => 0,
                'is_active' => 0,
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
            ],
        ];

        $table->insert($data)->save();
    }
}
