<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SourcesFixture
 */
class SourcesFixture extends TestFixture
{

    /**
     * @var array<int, array<string, mixed>> The data to insert
     */
    public $data = [
        [
            'id' => 1,
            'key' => 'amazon',
            'qr_code_key_field' => 'book',
            'name' => 'Amazon',
            'description' => 'Books for sale at Amazon',
            'created' => date('Y-m-d H:i:s'),
            'user_id' => 1,
        ],
        [
            'id' => 2,
            'key' => 'etsy',
            'qr_code_key_field' => 'id',
            'name' => 'Etsy',
            'description' => 'Products for sale at my Etsy Store',
            'created' => date('Y-m-d H:i:s'),
            'user_id' => 1,
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
