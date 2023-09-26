<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoriesQrCodesFixture
 */
class CategoriesQrCodesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
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
