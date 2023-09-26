<?php
declare(strict_types=1);

namespace App\Test\Fixture;

/**
 * QrCodesTagsFixture
 */
class QrCodesTagsFixture extends CoreFixture
{
    /**
     * Table property
     *
     * @var string
     */
    public string $table = 'qr_codes_tags';

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
                'tag_id' => 1,
            ],
            [
                'id' => 2,
                'qr_code_id' => 1,
                'tag_id' => 2,
            ],
            [
                'id' => 3,
                'qr_code_id' => 1,
                'tag_id' => 3,
            ],
            [
                'id' => 4,
                'qr_code_id' => 1,
                'tag_id' => 4,
            ],
        ];
        parent::init();
    }
}
