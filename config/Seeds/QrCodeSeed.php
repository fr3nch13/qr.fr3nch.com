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
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'url' => 'https://amazon.com/path/to/details/page',
                'is_active' => true,
                'source_id' => 1,
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'qrkey' => 'witchinghour',
                'name' => 'The Witching Hour',
                'description' => 'A Halloween themed journal/notebook with a witch flying at night',
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'url' => 'https://amazon.com/path/to/details/page2',
                'is_active' => true,
                'source_id' => 1,
                'user_id' => 1,
            ],
            [
                'id' => 3,
                'qrkey' => '3dmericaflag',
                'name' => 'American Flag 3D printed.',
                'description' => 'The American flag 3D printed in TPU, so it\'s flexible.',
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'url' => 'https://www.etsy.com/listing/1539113524/american-flag-3d-printed',
                'is_active' => true,
                'source_id' => 2,
                'user_id' => 1,
            ],
            [
                'id' => 4,
                'qrkey' => 'inactive',
                'name' => 'Inactive Code',
                'description' => 'This QR Code is inactive',
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'url' => 'https://google.com',
                'is_active' => false,
                'source_id' => 2, // etsy
                'user_id' => 2, // regular
            ],
            [
                // this one also has no qr_images
                'id' => 5,
                'qrkey' => 'inactiveadmin',
                'name' => 'Inactive Code - Admin',
                'description' => 'This QR Code is inactive',
                'created' => new DateTime(),
                'url' => 'https://google.com',
                'is_active' => false,
                'source_id' => 1, // amazon
                'user_id' => 1, // admin
            ],
        ];
        // add or change data here for the seeding.

        $table->insert($data)->save();
    }
}
