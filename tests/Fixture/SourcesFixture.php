<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\I18n\FrozenTime;

/**
 * SourcesFixture
 */
class SourcesFixture extends CoreFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->table = 'sources';

        $this->records = [
            [
                'id' => 1,
                'name' => 'Amazon',
                'description' => 'Books for sale at Amazon',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Etsy',
                'description' => 'Products for sale at my Etsy Store',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
