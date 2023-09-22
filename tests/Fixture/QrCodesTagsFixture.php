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
     * Define your data here.
     *
     * @param array<int, array<string, int>> The data to use to overwrite the default
     * @return array<int, array<string, int>> The data to insert
     */
    public function getData(array $data = []): array
    {
        $default = [
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

        if (empty($data)) {
            $data = $default;
        }

        return $data;
    }

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = $this->getData();
        parent::init();
    }
}
