<?php
declare(strict_types=1);

use App\Test\Fixture\QrCodesTagsFixture;
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
        $table = $this->table('qr_codes_tags');

        $data = QrCodesTagsFixture::$data;
        // add or change data here for the seeding.

        $table->insert($data)->save();
    }
}
