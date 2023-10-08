<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QrCodesTable;
use App\Model\Table\TagsTable;
use App\Model\Table\UsersTable;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Behavior\TimestampBehavior;
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
        /** @var \App\Model\Table\TagsTable $Tags */
        $Tags = $this->getTableLocator()->get('Tags', $config);
        $this->Tags = $Tags;
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
     * Tests the class name of the Table
     *
     * @return void
     * @uses \App\Model\Table\TagsTable::initialize()
     */
    public function testClassInstance(): void
    {
        $this->assertInstanceOf(TagsTable::class, $this->Tags);
    }

    /**
     * Testing a method.
     *
     * @return void
     * @uses \App\Model\Table\TagsTable::initialize()
     */
    public function testInitialize(): void
    {
        $this->assertSame('tags', $this->Tags->getTable());
        $this->assertSame('name', $this->Tags->getDisplayField());
        $this->assertSame('id', $this->Tags->getPrimaryKey());
    }

    /**
     * Test the behaviors
     *
     * @return void
     * @uses \App\Model\Table\TagsTable::initialize()
     */
    public function testBehaviors(): void
    {
        $behaviors = [
            'Timestamp' => TimestampBehavior::class,
        ];
        foreach ($behaviors as $name => $class) {
            $behavior = $this->Tags->behaviors()->get($name);
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
     * @uses \App\Model\Table\TagsTable::initialize()
     */
    public function testAssociations(): void
    {
        // get all of the associations
        $Associations = $this->Tags->associations();

        ////// foreach association.
        // make sure the association exists
        $this->assertNotNull($Associations->get('Users'));
        $this->assertInstanceOf(BelongsTo::class, $Associations->get('Users'));
        $this->assertInstanceOf(UsersTable::class, $Associations->get('Users')->getTarget());
        $Association = $this->Tags->Users;
        $this->assertSame('Users', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(BelongsToMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
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
        // test no set fields
        $entity = $this->Tags->newEntity([]);
        $expected = [
            'name' => [
                '_required' => 'This field is required',
            ],
            'user_id' => [
                '_required' => 'This field is required',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // test setting the fields after an empty entity.
        $entity->set('name', 'uniquetag');
        $entity->set('user_id', '1');

        $this->assertSame([], $entity->getErrors());

        // test missing user_id
        $entity = $this->Tags->newEntity([
            'name' => 'uniquetag',
        ]);

        $expected = [
            'user_id' => [
                '_required' => 'This field is required',
            ],
        ];

        $this->assertSame($expected, $entity->getErrors());

        // test existing tag
        $entity = $this->Tags->newEntity([
            'name' => 'Notebook',
            'user_id' => '1',
        ]);

        $expected = [
            'name' => [
                'unique' => 'This Tag already exists.',
            ],
        ];

        $this->assertSame($expected, $entity->getErrors());

        // test max length
        $entity = $this->Tags->newEntity([
            'name' => str_repeat('a', 256),
            'user_id' => 1, // int instead of a string, like above.
        ]);

        $expected = [
            'name' => [
                'maxLength' => 'The provided value must be at most `255` characters long',
            ],
        ];

        $this->assertSame($expected, $entity->getErrors());

        // test valid entity
        $entity = $this->Tags->newEntity([
            'name' => 'newtag',
            'user_id' => 1,
        ]);

        $expected = [];

        $this->assertSame($expected, $entity->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\TagsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        // bad name, and user id
        $entity = $this->Tags->newEntity([
            'name' => 'Notebook',
            'user_id' => 999,
        ]);
        $result = $this->Tags->checkRules($entity);
        $this->assertFalse($result);
        $expected = [
            'name' => [
                'unique' => 'This Tag already exists.',
            ],
            'user_id' => [
                '_existsIn' => 'Unknown User',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // check that we are passing the rules.
        $entity = $this->Tags->newEntity([
            'name' => 'uniquetag',
            'user_id' => 1,
        ]);
        $result = $this->Tags->checkRules($entity);
        $this->assertTrue($result);
    }
}
