<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\I18n\DateTime;

/**
 * TagsFixture
 */
class TagsFixture extends CoreFixture
{
    /**
     * Table property
     *
     * @var string
     */
    public string $table = 'tags';

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
                'name' => 'Notebook',
                'created' => new DateTime(),
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Journal',
                'created' => new DateTime(),
                'user_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Delete Me',
                'created' => new DateTime(),
                'user_id' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Pig',
                'created' => new DateTime(),
                'user_id' => 2, // regular user
            ],
            [
                'id' => 5,
                'name' => 'Amazon',
                'created' => new DateTime(),
                'user_id' => 2, // regular user
            ],
        ];
        parent::init();
    }
}
