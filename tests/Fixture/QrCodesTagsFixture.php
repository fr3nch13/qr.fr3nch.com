<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * QrCodesTagsFixture
 */
class QrCodesTagsFixture extends TestFixture
{

    /**
     * @var array<int, array<string, mixed>> The data to insert
     */
    public $data = [
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
