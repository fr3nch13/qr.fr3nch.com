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
     * Define your data here.
     *
     * @param array<int, array<string, mixed>> $data The data to use to overwrite the default
     * @return array<int, array<string, int>> The data to insert
     */
    public function getData(array $data = []): array
    {
        $default = [
            [
                'id' => 1,
                'qr_code_id' => 1,
                'category_id' => 2,
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
