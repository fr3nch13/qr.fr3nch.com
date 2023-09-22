<?php
declare(strict_types=1);

use App\Test\Fixture\UsersFixture;
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
        $table = $this->table('users');

        $data = (new UsersFixture())->data;
        // add or change data here for the seeding.

        $table->insert($data)->save();
    }
}
