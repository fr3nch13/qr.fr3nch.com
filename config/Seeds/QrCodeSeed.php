<?php
declare(strict_types=1);

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
            'UserSeed',
            'SourceSeed',
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

        $data = [
            [
                'id' => 1,
                'qrkey' => 'sownscribe',
                'name' => 'Sow & Scribe',
                'description' => 'The cute littly piggy journal/notebook',
                'created' => date('Y-m-d H:i:s'),
                'url' => 'https://amazon.com/path/to/details/page',
                'bitly_id' => 'sownscribe',
                'source_id' => 1,
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'qrkey' => 'witchinghour',
                'name' => 'The Witching Hour',
                'description' => 'A Halloween themed journal/notebook with a witch flying at night',
                'created' => date('Y-m-d H:i:s'),
                'url' => 'https://amazon.com/path/to/details/page2',
                'bitly_id' => 'witchinghour',
                'source_id' => 1,
                'user_id' => 1,
            ],
        ];
        // add or change data here for the seeding.

        $table->insert($data)->save();
    }
}
