<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SourcesTable;
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
        $this->Sources = $this->getTableLocator()->get('Sources', $config);
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
            'Timestamp' => \Cake\ORM\Behavior\TimestampBehavior::class,
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
        $this->assertInstanceOf(\Cake\ORM\Association\BelongsTo::class, $Associations->get('Users'));
        $this->assertInstanceOf(\App\Model\Table\UsersTable::class, $Associations->get('Users')->getTarget());
        $Association = $this->Sources->Users;
        $this->assertSame('Users', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(\Cake\ORM\Association\HasMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(\App\Model\Table\QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
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
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SourcesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
