<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\I18n\DateTime;

/**
 * QrCodesFixture
 */
class QrCodesFixture extends CoreFixture
{
    /**
     * Table property
     *
     * @var string
     */
    public string $table = 'qr_codes';

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->io->out(__('--- Init Fixture: {0} ---', [self::class]));

        $this->records = [
            [
                'id' => 1,
                'qrkey' => 'sownscribe',
                'name' => 'Sow & Scribe',
                'description' => 'The cute littly piggy journal/notebook',
                'created' => new DateTime(),
                'url' => 'https://amazon.com/path/to/details/page',
                'is_active' => true,
                'source_id' => 1, // amazon
                'user_id' => 1, // admin user
            ],
            [
                'id' => 2,
                'qrkey' => 'witchinghour',
                'name' => 'The Witching Hour',
                'description' => 'A Halloween themed journal/notebook with a witch flying at night',
                'created' => new DateTime(),
                'url' => 'https://amazon.com/path/to/details/page2',
                'is_active' => true,
                'source_id' => 1, // amazon
                'user_id' => 1, // admin user
            ],
            [
                'id' => 3,
                'qrkey' => '3dmericaflag',
                'name' => 'American Flag Charm',
                'description' => 'The American flag 3D printed in TPU, so it\'s flexible.',
                'created' => new DateTime(),
                'url' => 'https://www.etsy.com/listing/1539113524/american-flag-3d-printed',
                'is_active' => true,
                'source_id' => 2, // etsy
                'user_id' => 2, // regular
            ],
            [
                // this one also has no qr_images
                'id' => 4,
                'qrkey' => 'inactive',
                'name' => 'Inactive Code',
                'description' => 'This QR Code is inactive',
                'created' => new DateTime(),
                'url' => 'https://google.com',
                'is_active' => false,
                'source_id' => 2, // etsy
                'user_id' => 2, // regular
            ],
        ];
        parent::init();
    }
}
