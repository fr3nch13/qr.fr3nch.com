<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\I18n\FrozenTime;

/**
 * QrCodesTagsFixture
 */
class QrCodesTagsFixture extends CoreFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->import = [
            'model' => 'QrCodesTags',
        ];

        $this->records = [
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
        parent::init();
    }
}
