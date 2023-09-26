<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\I18n\DateTime;

/**
 * CategoriesFixture
 */
class CategoriesFixture extends CoreFixture
{
    /**
     * Table property
     *
     * @var string
     */
    public string $table = 'categories';

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
                'name' => 'Books',
                'description' => 'List of available books',
                'parent_id' => null,
                'created' => new DateTime(),
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Journals',
                'description' => 'Journals/Notebooks',
                'parent_id' => 1,
                'created' => new DateTime(),
                'user_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Charms',
                'description' => 'JLittle Charms',
                'parent_id' => null,
                'created' => new DateTime(),
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
