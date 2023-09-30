<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategoriesTable;
use App\Model\Table\QrCodesTable;
use App\Model\Table\UsersTable;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
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
        /** @var \App\Model\Table\CategoriesTable $Categories */
        $Categories = $this->getTableLocator()->get('Categories', $config);
        $this->Categories = $Categories;
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
     * Tests the class name of the Table
     *
     * @return void
     * @uses \App\Model\Table\CategoriesTable::initialize()
     */
    public function testClassInstance(): void
    {
        $this->assertInstanceOf(CategoriesTable::class, $this->Categories);
    }

    /**
     * Testing a method.
     *
     * @return void
     * @uses \App\Model\Table\CategoriesTable::initialize()
     */
    public function testInitialize(): void
    {
        $this->assertSame('categories', $this->Categories->getTable());
        $this->assertSame('name', $this->Categories->getDisplayField());
        $this->assertSame('id', $this->Categories->getPrimaryKey());
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
            'Timestamp' => TimestampBehavior::class,
        ];
        foreach ($behaviors as $name => $class) {
            $behavior = $this->Categories->behaviors()->get($name);
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
     * @uses \App\Model\Table\CategoriesTable::initialize()
     */
    public function testAssociations(): void
    {
        // get all of the associations
        $Associations = $this->Categories->associations();

        ////// foreach association.
        // make sure the association exists
        $this->assertNotNull($Associations->get('Users'));
        $this->assertInstanceOf(BelongsTo::class, $Associations->get('Users'));
        $this->assertInstanceOf(UsersTable::class, $Associations->get('Users')->getTarget());
        $Association = $this->Categories->Users;
        $this->assertSame('Users', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('ParentCategories'));
        $this->assertInstanceOf(BelongsTo::class, $Associations->get('ParentCategories'));
        $this->assertInstanceOf(CategoriesTable::class, $Associations->get('ParentCategories')->getTarget());
        $Association = $this->Categories->ParentCategories;
        $this->assertSame('ParentCategories', $Association->getName());
        $this->assertSame('parent_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('ChildCategories'));
        $this->assertInstanceOf(HasMany::class, $Associations->get('ChildCategories'));
        $this->assertInstanceOf(CategoriesTable::class, $Associations->get('ChildCategories')->getTarget());
        $Association = $this->Categories->ChildCategories;
        $this->assertSame('ChildCategories', $Association->getName());
        $this->assertSame('parent_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(BelongsToMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
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
        // test no set fields
        $entity = $this->Categories->newEntity([]);
        $expected = [
            'name' => [
                '_required' => 'This field is required',
            ],
            'description' => [
                '_required' => 'This field is required',
            ],
            'user_id' => [
                '_required' => 'This field is required',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // test setting the fields after an empty entity.
        $entity->set('name', 'name');
        $entity->set('description', 'description');
        $entity->set('user_id', '1');

        $this->assertSame([], $entity->getErrors());

        // test max length
        $entity = $this->Categories->newEntity([
            'name' => str_repeat('a', 256),
            'description' => 'description',
            'user_id' => 1, // int instead of a string, like above.
        ]);

        $expected = [
            'name' => [
                'maxLength' => 'The provided value must be at most `255` characters long',
            ],
        ];

        $this->assertSame($expected, $entity->getErrors());

        // test valid entity
        $entity = $this->Categories->newEntity([
            'name' => 'new name',
            'description' => 'description',
            'user_id' => 1,
        ]);

        $expected = [];

        $this->assertSame($expected, $entity->getErrors());

        // test valid entity with parent
        $entity = $this->Categories->newEntity([
            'name' => 'new name',
            'description' => 'description',
            'parent_id' => 1,
            'user_id' => 1,
        ]);

        $expected = [];

        $this->assertSame($expected, $entity->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\CategoriesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        // bad parent, and user id
        $entity = $this->Categories->newEntity([
            'name' => 'new name',
            'description' => 'description',
            'parent_id' => 999,
            'user_id' => 999,
        ]);
        $result = $this->Categories->checkRules($entity);
        $this->assertFalse($result);
        $expected = [
            'parent_id' => [
                '_existsIn' => 'This Parent Category doesn\'t exist.',
            ],
            'user_id' => [
                '_existsIn' => 'Unknown User',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // check that we are passing the rules.
        $entity = $this->Categories->newEntity([
            'name' => 'new name',
            'description' => 'description',
            'parent_id' => 1,
            'user_id' => 1,
        ]);
        $result = $this->Categories->checkRules($entity);
        $this->assertTrue($result);
        $expected = [];
        $this->assertSame($expected, $entity->getErrors());
    }
}
