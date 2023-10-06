<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\I18n\DateTime;

/**
 * QrImagesFixture
 */
class QrImagesFixture extends CoreFixture
{
    /**
     * Table property
     *
     * @var string
     */
    public string $table = 'qr_images';

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
                'name' => 'Front Cover',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => true,
                'imorder' => 0,
                'qr_code_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Open Pages',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => true,
                'imorder' => 1,
                'qr_code_id' => 1,
            ],
            [
                // inactive and owned by admin
                'id' => 3,
                'name' => 'Front Cover',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => false,
                'imorder' => 0,
                'qr_code_id' => 2,
            ],
            [
                // this entity is intentionally missing it's file for unit testing.
                'id' => 4,
                'name' => 'Open Pages',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => true,
                'imorder' => 1,
                'qr_code_id' => 2,
            ],
            [
                // owned by reqular.
                'id' => 5,
                'name' => 'In Hand',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => true,
                'imorder' => 0,
                'qr_code_id' => 3,
            ],
            [
                // owned by reqular.
                'id' => 6,
                'name' => 'Dimensions Top',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => true,
                'imorder' => 1,
                'qr_code_id' => 3,
            ],
            [
                // inactive and owned by reqular.
                'id' => 7,
                'name' => 'Dimensions Side',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => false,
                'imorder' => 2,
                'qr_code_id' => 3,
            ],
        ];
        parent::init();
    }
}
