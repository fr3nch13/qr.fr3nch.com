<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use App\Migrations\Data\SourcesData;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * SourcesFixture
 */
class SourcesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = (new SourcesData())->getData();
        debug($this->records);
        parent::init();
    }
}
