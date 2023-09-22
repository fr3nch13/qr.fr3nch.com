<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use App\Seeds\QrCodeSeed;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * QrCodesFixture
 */
class QrCodesFixture extends TestFixture
{
    /**
     * Define your data here.
     *
     * @param array<int, array<string, mixed>> The data to use to overwrite the default
     * @return array<int, array<string, mixed>> The data to insert
     */
    public function getData(array $data = []): array
    {
        $default = [
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
        ];

        if (empty($data)) {
            $data = $default;
        }

        return $data;
    }

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = $this->getData();
        parent::init();
    }
}
