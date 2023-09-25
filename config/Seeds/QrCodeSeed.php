<?php
declare(strict_types=1);

use App\Test\Fixture\QrCodesFixture;
use Migrations\AbstractSeed;

/**
 * QrCode seed.
 */
class QrCodeSeed extends AbstractSeed
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
            'SourceSeed',
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
        $this->checkTable('qr_codes');
        $table = $this->table('qr_codes');

        $data = (new \App\Migrations\Data\QrCodesData())->getData();
        // add or change data here for the seeding.

        $table->insert($data)->save();
    }
}
