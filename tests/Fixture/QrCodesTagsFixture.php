<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use App\Migrations\Data\QrCodesTagsData;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * QrCodesTagsFixture
 */
class QrCodesTagsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = (new QrCodesTagsData())->getData();
        parent::init();
    }
}
