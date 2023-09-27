<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\I18n\DateTime;

/**
 * SourcesFixture
 */
class SourcesFixture extends CoreFixture
{
    /**
     * Table property
     *
     * @var string
     */
    public string $table = 'sources';

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->io->out(__('--- Init Fixture: {0} ---', [self::class]));

        $this->records = [
            [
                'id' => 1,
                'name' => 'Amazon',
                'description' => 'Books for sale at Amazon',
                'created' => new DateTime(),
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Etsy',
                'description' => 'Products for sale at my Etsy Store',
                'created' => new DateTime(),
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
