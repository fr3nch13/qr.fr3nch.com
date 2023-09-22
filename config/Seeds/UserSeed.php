<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Migrations\AbstractSeed;

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

        $data = [
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'changeme@example.com',
                'password' => (new DefaultPasswordHasher())->hash('admin'),
                'is_admin' => true,
                'created' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
