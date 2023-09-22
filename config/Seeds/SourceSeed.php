<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Source seed.
 */
class SourceSeed extends AbstractSeed
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
        $this->checkTable('sources');

        $data = [
            [
                'id' => 1,
                'key' => 'amazon',
                'qr_code_key_field' => 'book',
                'name' => 'Amazon',
                'description' => 'Books for sale at Amazon',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'key' => 'etsy',
                'qr_code_key_field' => 'id',
                'name' => 'Etsy',
                'description' => 'Products for sale at my Etsy Store',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
        ];

        $table = $this->table('sources');
        $table->insert($data)->save();
    }
}
