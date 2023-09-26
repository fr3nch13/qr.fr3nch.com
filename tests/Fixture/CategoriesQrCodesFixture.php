<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\I18n\FrozenTime;

/**
 * CategoriesQrCodesFixture
 */
class CategoriesQrCodesFixture extends CoreFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->import = [
            'model' => 'CategoriesQrCodes',
        ];

        $this->records = [
            [
                'id' => 1,
                'qr_code_id' => 1,
                'category_id' => 2,
            ],
        ];
        parent::init();
    }
}
