<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TagsFixture
 */
class TagsFixture extends TestFixture
{

    /**
     * @var array<int, array<string, mixed>> The data to insert
     */
    public static $data = [
        [
            'id' => 1,
            'name' => 'Notebook',
            'created' => date('Y-m-d H:i:s'),
            'user_id' => 1,
        ],
        [
            'id' => 2,
            'name' => 'Journal',
            'created' => date('Y-m-d H:i:s'),
            'user_id' => 1,
        ],
        [
            'id' => 3,
            'name' => 'Amazon',
            'created' => date('Y-m-d H:i:s'),
            'user_id' => 1,
        ],
        [
            'id' => 4,
            'name' => 'Pig',
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
        $this->records = self::$data;
        parent::init();
    }
}
