<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * CategoryQrCode seed.
 */
class CategoryQrCodeSeed extends AbstractSeed
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
            'CategorySeed',
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
        $this->checkTable('categories_qr_codes');

        $data = [
            [
                'id' => 1,
                'qr_code_id' => 1,
                'category_id' => 2,
            ],
        ];

        $table = $this->table('categories_qr_codes');
        $table->insert($data)->save();
    }
}
