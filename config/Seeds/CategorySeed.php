<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Category seed.
 */
class CategorySeed extends AbstractSeed
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
        $this->checkTable('categories');
        $table = $this->table('categories');

        $data = [
            [
                'id' => 1,
                'name' => 'Books',
                'description' => 'List of available books',
                'parent_id' => null,
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Journals',
                'description' => 'Journals/Notebooks',
                'parent_id' => 1,
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Charms',
                'description' => 'JLittle Charms',
                'parent_id' => null,
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
        ];
        // add or change data here for the seeding.

        $table->insert($data)->save();
    }
}
