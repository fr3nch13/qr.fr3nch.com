<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QrCodesTagsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\QrCodesTagsTable Test Case
 */
class QrCodesTagsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\QrCodesTagsTable
     */
    protected $QrCodesTags;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
        'app.Sources',
        'app.Tags',
        'app.QrCodes',
        'app.QrCodesTags',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('QrCodesTags') ? [] : ['className' => QrCodesTagsTable::class];
        $this->QrCodesTags = $this->getTableLocator()->get('QrCodesTags', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->QrCodesTags);

        parent::tearDown();
    }

    /**
     * Test Associations
     *
     * @return void
     * @uses \App\Model\Table\QrCodesTagsTable::initialize()
     */
    public function testAssociations(): void
    {
        // get all of the associations
        $Associations = $this->QrCodesTags->associations();

        ////// foreach association.
        // make sure the association exists
        $this->assertNotNull($Associations->get('Tags'));
        $this->assertInstanceOf(\Cake\ORM\Association\HasMany::class, $Associations->get('Tags'));
        $this->assertInstanceOf(\App\Model\Table\TagsTable::class, $Associations->get('Tags')->getTarget());
        $Association = $this->QrCodesTags->Tags;
        $this->assertSame('Tags', $Association->getName());
        $this->assertSame('tag_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(\Cake\ORM\Association\HasMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(\App\Model\Table\QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
        $Association = $this->QrCodesTags->QrCodes;
        $this->assertSame('QrCodes', $Association->getName());
        $this->assertSame('qr_code_id', $Association->getForeignKey());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\QrCodesTagsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\QrCodesTagsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
