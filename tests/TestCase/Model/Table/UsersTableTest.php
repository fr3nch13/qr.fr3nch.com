<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = $this->getTableLocator()->get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Tests the class name of the Table
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::initialize()
     */
    public function testClassInstance(): void
    {
        $this->assertInstanceOf(UsersTable::class, $this->Users);
    }

    /**
     * Testing a method.
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::initialize()
     */
    public function testInitialize(): void
    {
        $this->assertSame('users', $this->Users->getTable());
        $this->assertSame('name', $this->Users->getDisplayField());
        $this->assertSame('id', $this->Users->getPrimaryKey());
    }

    /**
     * Test the behaviors
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::initialize()
     */
    public function testBehaviors(): void
    {
        $behaviors = [
            'Timestamp' => \Cake\ORM\Behavior\TimestampBehavior::class,
        ];
        foreach ($behaviors as $name => $class) {
            $behavior = $this->Users->behaviors()->get($name);
            $this->assertNotNull($behavior, __('Behavior `{0}` is null.', [$name]));
            $this->assertInstanceOf($class, $behavior, __('Behavior `{0}` isn\'t an instance of {1}.', [
                $name,
                $class,
            ]));
        }
    }

    /**
     * Test Associations
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::initialize()
     */
    public function testAssociations(): void
    {
        // get all of the associations
        $Associations = $this->Users->associations();

        ////// foreach association.
        // make sure the association exists
        $this->assertNotNull($Associations->get('Categories'));
        $this->assertInstanceOf(\Cake\ORM\Association\HasMany::class, $Associations->get('Categories'));
        $this->assertInstanceOf(\App\Model\Table\CategoriesTable::class, $Associations->get('Categories')->getTarget());
        $Association = $this->Users->Categories;
        $this->assertSame('Categories', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(\Cake\ORM\Association\HasMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(\App\Model\Table\QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
        $Association = $this->Users->QrCodes;
        $this->assertSame('QrCodes', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('Sources'));
        $this->assertInstanceOf(\Cake\ORM\Association\HasMany::class, $Associations->get('Sources'));
        $this->assertInstanceOf(\App\Model\Table\SourcesTable::class, $Associations->get('Sources')->getTarget());
        $Association = $this->Users->Sources;
        $this->assertSame('Sources', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('Tags'));
        $this->assertInstanceOf(\Cake\ORM\Association\HasMany::class, $Associations->get('Tags'));
        $this->assertInstanceOf(\App\Model\Table\TagsTable::class, $Associations->get('Tags')->getTarget());
        $Association = $this->Users->Tags;
        $this->assertSame('Tags', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
