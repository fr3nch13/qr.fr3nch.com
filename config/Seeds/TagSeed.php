<?php
declare(strict_types=1);

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

        $data = [
            [
                'id' => 1,
                'name' => 'Notebook',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Journal',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Amazon',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Pig',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
        ];

        $table = $this->table('tags');
        $table->insert($data)->save();
    }
}
