<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use App\Migrations\Data\UsersData;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = (new UsersData())->getData();
        parent::init();
    }
}
