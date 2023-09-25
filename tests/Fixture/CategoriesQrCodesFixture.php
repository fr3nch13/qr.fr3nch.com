<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoriesQrCodesFixture
 */
class CategoriesQrCodesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = (new \App\Migrations\Data\CategoriesQrCodesData())->getData();
        parent::init();
    }
}
