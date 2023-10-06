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
            [
                'id' => 3,
                'name' => 'Delete Me',
                'description' => 'Used for testing deleting',
                'created' => new DateTime(),
                // intentionally set to the regular users to test that they still can't edit/delete it.
                // also test that this gets reset on an edit by Admin.
                'user_id' => 2,
            ],
        ];
        parent::init();
    }
}
