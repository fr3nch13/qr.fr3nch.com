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
     * @var array<int, array<string, mixed>> The data to insert
     */
    public $data = [
        [
            'id' => 1,
            'qr_code_id' => 1,
            'category_id' => 2,
        ],
    ];

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = $this->data;
        parent::init();
    }
}
