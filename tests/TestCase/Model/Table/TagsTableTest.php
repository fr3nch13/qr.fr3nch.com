<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TagsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TagsTable Test Case
 */
class TagsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TagsTable
     */
    protected $Tags;

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
        $config = $this->getTableLocator()->exists('Tags') ? [] : ['className' => TagsTable::class];
        $this->Tags = $this->getTableLocator()->get('Tags', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Tags);

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
        $Associations = $this->Tags->associations();

        ////// foreach association.
        // make sure the association exists
        $this->assertNotNull($Associations->get('Users'));
        $this->assertInstanceOf(\Cake\ORM\Association\BelongsTo::class, $Associations->get('Users'));
        $this->assertInstanceOf(\App\Model\Table\UsersTable::class, $Associations->get('Users')->getTarget());
        $Association = $this->Tags->Users;
        $this->assertSame('Users', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(\Cake\ORM\Association\BelongsToMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(\App\Model\Table\QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
        $Association = $this->Tags->QrCodes;
        $this->assertSame('QrCodes', $Association->getName());
        $this->assertSame('QrCodesTags', $Association->getThrough());
        $this->assertSame('tag_id', $Association->getForeignKey());
        $this->assertSame('qr_code_id', $Association->getTargetForeignKey());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TagsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
