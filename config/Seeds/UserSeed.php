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
                'created' => new DateTime(),
            ],
            [
                'id' => 2,
                'name' => 'Regular',
                'email' => 'regular@example.com',
                'password' => (new DefaultPasswordHasher())->hash('regular'),
                'is_admin' => 0,
                'created' => new DateTime(),
            ],
            [
                'id' => 3,
                'name' => 'Delete Me',
                'email' => 'deleteme@example.com',
                'password' => (new DefaultPasswordHasher())->hash('deleteme'),
                'is_admin' => 0,
                'created' => new DateTime(),
            ],
        ];

        $table->insert($data)->save();
    }
}
