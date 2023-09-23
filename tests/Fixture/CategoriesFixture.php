<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoriesFixture
 */
class CategoriesFixture extends TestFixture
{
    /**
     * Define your data here.
     *
     * @param array<int, array<string, mixed>> $data The data to use to overwrite the default
     * @return array<int, array<string, mixed>> The data to insert
     */
    public function getData(array $data = []): array
    {
        $default = [
            [
                'id' => 1,
                'name' => 'Books',
                'description' => 'List of available books',
                'parent_id' => null,
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Journals',
                'description' => 'Journals/Notebooks',
                'parent_id' => 1,
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
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
