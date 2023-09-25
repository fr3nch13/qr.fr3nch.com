<?php
declare(strict_types=1);

namespace App\Test\Fixture;

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
        $this->records = (new \App\Migrations\Data\QrCodesTagsData())->getData();
        parent::init();
    }
}
