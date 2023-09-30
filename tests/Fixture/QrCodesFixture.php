<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\I18n\DateTime;

/**
 * QrCodesFixture
 */
class QrCodesFixture extends CoreFixture
{
    /**
     * Table property
     *
     * @var string
     */
    public string $table = 'qr_codes';

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
                'qrkey' => 'sownscribe',
                'name' => 'Sow & Scribe',
                'description' => 'The cute littly piggy journal/notebook',
                'created' => new DateTime(),
                'url' => 'https://amazon.com/path/to/details/page',
                'source_id' => 1,
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'qrkey' => 'witchinghour',
                'name' => 'The Witching Hour',
                'description' => 'A Halloween themed journal/notebook with a witch flying at night',
                'created' => new DateTime(),
                'url' => 'https://amazon.com/path/to/details/page2',
                'source_id' => 1,
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
