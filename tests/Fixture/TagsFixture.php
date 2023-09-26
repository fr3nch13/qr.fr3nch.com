<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use App\Migrations\Data\TagsData;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * TagsFixture
 */
class TagsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = (new TagsData())->getData();
        parent::init();
    }
}
