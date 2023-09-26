<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QrCodesTable;
use App\Model\Table\SourcesTable;
use App\Model\Table\UsersTable;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SourcesTable Test Case
 */
class SourcesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SourcesTable
     */
    protected $Sources;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
        'app.Sources',
        'app.QrCodes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Sources') ? [] : ['className' => SourcesTable::class];
        /** @var \App\Model\Table\SourcesTable $Sources */
        $Sources = $this->getTableLocator()->get('Sources', $config);
        $this->Sources = $Sources;
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Sources);

        parent::tearDown();
    }

    /**
     * Tests the class name of the Table
     *
     * @return void
     * @uses \App\Model\Table\SourcesTable::initialize()
     */
    public function testClassInstance(): void
    {
        $this->assertInstanceOf(SourcesTable::class, $this->Sources);
    }

    /**
     * Testing a method.
     *
     * @return void
     * @uses \App\Model\Table\SourcesTable::initialize()
     */
    public function testInitialize(): void
    {
        $this->assertSame('sources', $this->Sources->getTable());
        $this->assertSame('name', $this->Sources->getDisplayField());
        $this->assertSame('id', $this->Sources->getPrimaryKey());
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
            $behavior = $this->Sources->behaviors()->get($name);
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
     * @uses \App\Model\Table\SourcesTable::initialize()
     */
    public function testAssociations(): void
    {
        // get all of the associations
        $Associations = $this->Sources->associations();

        ////// foreach association.
        // make sure the association exists
        $this->assertNotNull($Associations->get('Users'));
        $this->assertInstanceOf(BelongsTo::class, $Associations->get('Users'));
        $this->assertInstanceOf(UsersTable::class, $Associations->get('Users')->getTarget());
        $Association = $this->Sources->Users;
        $this->assertSame('Users', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(HasMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
        $Association = $this->Sources->QrCodes;
        $this->assertSame('QrCodes', $Association->getName());
        $this->assertSame('source_id', $Association->getForeignKey());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SourcesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        // test no set fields
        $entity = $this->Sources->newEntity([]);
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

        // test existing fields
        $entity = $this->Sources->newEntity([
            'name' => 'Etsy',
            'description' => 'description',
            'user_id' => '1',
        ]);

        $expected = [
            'name' => [
                'unique' => 'This Name already exists.',
            ],
        ];

        $this->assertSame($expected, $entity->getErrors());

        // test max length
        $entity = $this->Sources->newEntity([
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
        $entity = $this->Sources->newEntity([
            'name' => 'new name',
            'description' => 'description',
            'user_id' => 1,
        ]);

        $expected = [];

        $this->assertSame($expected, $entity->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SourcesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        // bad name, and user id
        $entity = $this->Sources->newEntity([
            'name' => 'new name',
            'description' => 'description',
            'user_id' => 999,
        ]);
        $result = $this->Sources->checkRules($entity);
        $this->assertFalse($result);
        $expected = [
            'user_id' => [
                '_existsIn' => 'Unknown User',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // check that we are passing the rules.
        $entity = $this->Sources->newEntity([
            'name' => 'new name',
            'description' => 'description',
            'user_id' => 1,
        ]);
        $result = $this->Sources->checkRules($entity);
        $this->assertTrue($result);
        $expected = [];
        $this->assertSame($expected, $entity->getErrors());
    }

    /**
     * Test the entity itself
     *
     * @return void
     * @uses \App\Model\Entity\Source
     */
    public function testEntity(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
