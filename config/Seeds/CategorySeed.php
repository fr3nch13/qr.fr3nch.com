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

        $data = (new \App\Migrations\Data\CategoriesData())->getData();
        // add or change data here for the seeding.

        $table->insert($data)->save();
    }
}
