<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategoriesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CategoriesTable Test Case
 */
class CategoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CategoriesTable
     */
    protected $Categories;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
        'app.Categories',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Categories') ? [] : ['className' => CategoriesTable::class];
        $this->Categories = $this->getTableLocator()->get('Categories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Categories);

        parent::tearDown();
    }

    /**
     * Test Associations
     *
     * @return void
     * @uses \App\Model\Table\CategoriesTable::initialize()
     */
    public function testAssociations(): void
    {
        // get all of the associations
        $Associations = $this->Categories->associations();

        ////// foreach association.
        // make sure the association exists
        $this->assertNotNull($Associations->get('Users'));
        $this->assertInstanceOf(\Cake\ORM\Association\BelongsTo::class, $Associations->get('Users'));
        $this->assertInstanceOf(\App\Model\Table\UsersTable::class, $Associations->get('Users')->getTarget());
        $Association = $this->Categories->Users;
        $this->assertSame('Users', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('ParentCategories'));
        $this->assertInstanceOf(\Cake\ORM\Association\BelongsTo::class, $Associations->get('ParentCategories'));
        $this->assertInstanceOf(\App\Model\Table\CategoriesTable::class, $Associations->get('ParentCategories')->getTarget());
        $Association = $this->Categories->ParentCategories;
        $this->assertSame('ParentCategories', $Association->getName());
        $this->assertSame('parent_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('ChildCategories'));
        $this->assertInstanceOf(\Cake\ORM\Association\HasMany::class, $Associations->get('ChildCategories'));
        $this->assertInstanceOf(\App\Model\Table\CategoriesTable::class, $Associations->get('ChildCategories')->getTarget());
        $Association = $this->Categories->ChildCategories;
        $this->assertSame('ChildCategories', $Association->getName());
        $this->assertSame('parent_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(\Cake\ORM\Association\BelongsToMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(\App\Model\Table\QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
        $Association = $this->Categories->QrCodes;
        $this->assertSame('QrCodes', $Association->getName());
        $this->assertSame('CategoriesQrCodes', $Association->getThrough());
        $this->assertSame('category_id', $Association->getForeignKey());
        $this->assertSame('qr_code_id', $Association->getTargetForeignKey());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\CategoriesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\CategoriesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
