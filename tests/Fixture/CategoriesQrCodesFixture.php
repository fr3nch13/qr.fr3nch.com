<?php
declare(strict_types=1);

namespace App\Test\Fixture;

/**
 * CategoriesQrCodesFixture
 */
class CategoriesQrCodesFixture extends CoreFixture
{
    /**
     * Table property
     *
     * @var string
     */
    public string $table = 'categories_qr_codes';

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
                'qr_code_id' => 1,
                'category_id' => 2,
            ],
        ];
        parent::init();
    }
}
