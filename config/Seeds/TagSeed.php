<?php
declare(strict_types=1);

use App\Test\Fixture\TagsFixture;
use Migrations\AbstractSeed;

/**
 * Tag seed.
 */
class TagSeed extends AbstractSeed
{
    use \App\Migrations\QrSeedTrait;

    /**
     * The dependant records before this can be added.
     *
     * @return array<int, string> List of required seeds
     */
    public function getDependencies(): array
    {
        return [
            'UserSeed',
        ];
    }

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
        $this->checkTable('tags');
        $table = $this->table('tags');

        $data = TagsFixture::$data;
        // add or change data here for the seeding.

        $table->insert($data)->save();
    }
}
