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
                'created' => new DateTime(),
                'qr_code_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Open Pages',
                'created' => new DateTime(),
                'qr_code_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Front Cover',
                'created' => new DateTime(),
                'qr_code_id' => 2,
            ],
            [
                'id' => 4,
                'name' => 'Open Pages',
                'created' => new DateTime(),
                'qr_code_id' => 2,
            ],
            [
                'id' => 5,
                'name' => 'In Hand',
                'created' => new DateTime(),
                'qr_code_id' => 3,
            ],
            [
                'id' => 6,
                'name' => 'Dimensions Top',
                'created' => new DateTime(),
                'qr_code_id' => 3,
            ],
            [
                'id' => 7,
                'name' => 'Dimensions Side',
                'created' => new DateTime(),
                'qr_code_id' => 2,
            ],
        ];
        parent::init();
    }
}
