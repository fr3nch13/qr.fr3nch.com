<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QrCodesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\QrCodesTable Test Case
 */
class QrCodesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\QrCodesTable
     */
    protected $QrCodes;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
        'app.Sources',
        'app.Categories',
        'app.QrCodes',
        'app.CategoriesQrCodes',
        'app.Tags',
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
        $config = $this->getTableLocator()->exists('QrCodes') ? [] : ['className' => QrCodesTable::class];
        $this->QrCodes = $this->getTableLocator()->get('QrCodes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->QrCodes);

        parent::tearDown();
    }

    /**
     * Test Associations
     *
     * @return void
     * @uses \App\Model\Table\QrCodesTable::initialize()
     */
    public function testAssociations(): void
    {
        // get all of the associations
        $Associations = $this->QrCodes->associations();

        ////// foreach association.
        // make sure the association exists
        $this->assertNotNull($Associations->get('Users'));
        $this->assertInstanceOf(\Cake\ORM\Association\BelongsTo::class, $Associations->get('Users'));
        $this->assertInstanceOf(\App\Model\Table\UsersTable::class, $Associations->get('Users')->getTarget());
        $Association = $this->QrCodes->Users;
        $this->assertSame('Users', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('Sources'));
        $this->assertInstanceOf(\Cake\ORM\Association\BelongsTo::class, $Associations->get('Sources'));
        $this->assertInstanceOf(\App\Model\Table\SourcesTable::class, $Associations->get('Sources')->getTarget());
        $Association = $this->QrCodes->Sources;
        $this->assertSame('Sources', $Association->getName());
        $this->assertSame('source_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('Categories'));
        $this->assertInstanceOf(\Cake\ORM\Association\BelongsToMany::class, $Associations->get('Categories'));
        $this->assertInstanceOf(\App\Model\Table\CategoriesTable::class, $Associations->get('Categories')->getTarget());
        $Association = $this->QrCodes->Categories;
        $this->assertSame('Categories', $Association->getName());
        $this->assertSame('CategoriesQrCodes', $Association->getThrough());
        $this->assertSame('qr_code_id', $Association->getForeignKey());
        $this->assertSame('category_id', $Association->getTargetForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('Tags'));
        $this->assertInstanceOf(\Cake\ORM\Association\BelongsToMany::class, $Associations->get('Tags'));
        $this->assertInstanceOf(\App\Model\Table\TagsTable::class, $Associations->get('Tags')->getTarget());
        $Association = $this->QrCodes->Tags;
        $this->assertSame('Tags', $Association->getName());
        $this->assertSame('QrCodesTags', $Association->getThrough());
        $this->assertSame('qr_code_id', $Association->getForeignKey());
        $this->assertSame('tag_id', $Association->getTargetForeignKey());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\QrCodesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\QrCodesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
