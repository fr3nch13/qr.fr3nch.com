<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\Console\ConsoleIo;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * QrCodesFixture
 */
class QrCodesFixture extends TestFixture
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
            'model' => 'QrCodes',
        ];

        $this->records = [
            [
                'id' => 1,
                'key' => 'sownscribe',
                'name' => 'Sow & Scribe',
                'description' => 'The cute littly piggy journal/notebook',
                'created' => date('Y-m-d H:i:s'),
                'url' => 'https://amazon.com/path/to/details/page',
                'bitly_id' => 'sownscribe',
                'source_id' => 1,
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'key' => 'witchinghour',
                'name' => 'The Witching Hour',
                'description' => 'A Halloween themed journal/notebook with a witch flying at night',
                'created' => date('Y-m-d H:i:s'),
                'url' => 'https://amazon.com/path/to/details/page2',
                'bitly_id' => 'witchinghour',
                'source_id' => 1,
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
