<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * QrCodeTag seed.
 */
class QrCodeTagSeed extends AbstractSeed
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
            'TagSeed',
            'QrCodeSeed',
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
        $this->checkTable('qr_codes_tags');

        $data = [
            [
                'id' => 1,
                'qr_code_id' => 1,
                'tag_id' => 1,
            ],
            [
                'id' => 2,
                'qr_code_id' => 1,
                'tag_id' => 2,
            ],
            [
                'id' => 3,
                'qr_code_id' => 1,
                'tag_id' => 3,
            ],
            [
                'id' => 4,
                'qr_code_id' => 1,
                'tag_id' => 4,
            ],
        ];

        $table = $this->table('qr_codes_tags');
        $table->insert($data)->save();
    }
}
