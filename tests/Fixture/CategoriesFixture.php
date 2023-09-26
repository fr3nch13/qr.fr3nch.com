<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\I18n\FrozenTime;

/**
 * CategoriesFixture
 */
class CategoriesFixture extends CoreFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->import = [
            'model' => 'Categories',
        ];

        $this->records = [
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
            [
                'id' => 3,
                'name' => 'Charms',
                'description' => 'JLittle Charms',
                'parent_id' => null,
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
