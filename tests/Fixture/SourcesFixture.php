<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\Console\ConsoleIo;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * SourcesFixture
 */
class SourcesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $io = new ConsoleIo();
        $io->out(__('--- Init Fixture: {0} ---', [self::class]));

        $this->import = [
            'model' => 'Sources',
        ];

        $this->records = [
            [
                'id' => 1,
                'key' => 'amazon',
                'name' => 'Amazon',
                'description' => 'Books for sale at Amazon',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'key' => 'etsy',
                'name' => 'Etsy',
                'description' => 'Products for sale at my Etsy Store',
                'created' => date('Y-m-d H:i:s'),
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
