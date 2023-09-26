<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\I18n\FrozenTime;

/**
 * TagsFixture
 */
class TagsFixture extends CoreFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->import = [
            'model' => 'Tags',
        ];

        $this->records = [
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
        parent::init();
    }
}
