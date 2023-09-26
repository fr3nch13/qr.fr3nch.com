<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use App\Migrations\Data\CategoriesData;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoriesFixture
 */
class CategoriesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = (new CategoriesData())->getData();
        parent::init();
    }
}
