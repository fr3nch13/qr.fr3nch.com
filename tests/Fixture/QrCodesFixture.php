<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use App\Migrations\Data\QrCodesData;
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
        $this->records = (new QrCodesData())->getData();
        parent::init();
    }
}
