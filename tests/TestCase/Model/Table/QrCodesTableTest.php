<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategoriesTable;
use App\Model\Table\QrCodesTable;
use App\Model\Table\SourcesTable;
use App\Model\Table\TagsTable;
use App\Model\Table\UsersTable;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Behavior\TimestampBehavior;
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
        /** @var \App\Model\Table\QrCodesTable $QrCodes */
        $QrCodes = $this->getTableLocator()->get('QrCodes', $config);
        $this->QrCodes = $QrCodes;
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
     * Tests the class name of the Table
     *
     * @return void
     * @uses \App\Model\Table\QrCodesTable::initialize()
     */
    public function testClassInstance(): void
    {
        $this->assertInstanceOf(QrCodesTable::class, $this->QrCodes);
    }

    /**
     * Testing a method.
     *
     * @return void
     * @uses \App\Model\Table\QrCodesTable::initialize()
     */
    public function testInitialize(): void
    {
        $this->assertSame('qr_codes', $this->QrCodes->getTable());
        $this->assertSame('name', $this->QrCodes->getDisplayField());
        $this->assertSame('id', $this->QrCodes->getPrimaryKey());
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
            $behavior = $this->QrCodes->behaviors()->get($name);
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
     * @uses \App\Model\Table\QrCodesTable::initialize()
     */
    public function testAssociations(): void
    {
        // get all of the associations
        $Associations = $this->QrCodes->associations();

        ////// foreach association.
        // make sure the association exists
        $this->assertNotNull($Associations->get('Users'));
        $this->assertInstanceOf(BelongsTo::class, $Associations->get('Users'));
        $this->assertInstanceOf(UsersTable::class, $Associations->get('Users')->getTarget());
        $Association = $this->QrCodes->Users;
        $this->assertSame('Users', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('Sources'));
        $this->assertInstanceOf(BelongsTo::class, $Associations->get('Sources'));
        $this->assertInstanceOf(SourcesTable::class, $Associations->get('Sources')->getTarget());
        $Association = $this->QrCodes->Sources;
        $this->assertSame('Sources', $Association->getName());
        $this->assertSame('source_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('Categories'));
        $this->assertInstanceOf(BelongsToMany::class, $Associations->get('Categories'));
        $this->assertInstanceOf(CategoriesTable::class, $Associations->get('Categories')->getTarget());
        $Association = $this->QrCodes->Categories;
        $this->assertSame('Categories', $Association->getName());
        $this->assertSame('CategoriesQrCodes', $Association->getThrough());
        $this->assertSame('qr_code_id', $Association->getForeignKey());
        $this->assertSame('category_id', $Association->getTargetForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('Tags'));
        $this->assertInstanceOf(BelongsToMany::class, $Associations->get('Tags'));
        $this->assertInstanceOf(TagsTable::class, $Associations->get('Tags')->getTarget());
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
        // test no set fields
        $entity = $this->QrCodes->newEntity([]);
        $expected = [
            'qrkey' => [
                '_required' => 'This field is required',
            ],
            'name' => [
                '_required' => 'This field is required',
            ],
            'description' => [
                '_required' => 'This field is required',
            ],
            'url' => [
                '_required' => 'This field is required',
            ],
            'bitly_id' => [
                '_required' => 'This field is required',
            ],
            'source_id' => [
                '_required' => 'This field is required',
            ],
            'user_id' => [
                '_required' => 'This field is required',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // test setting the fields after an empty entity.
        $entity->set('qrkey', 'qrkey');
        $entity->set('name', 'name');
        $entity->set('description', 'description');
        $entity->set('url', 'url');
        $entity->set('bitly_id', '1');
        $entity->set('source_id', '1');
        $entity->set('user_id', '1');

        $this->assertSame([], $entity->getErrors());

        // test existing fields
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'sownscribe',
            'name' => 'Sow & Scribe',
            'description' => 'description',
            'url' => 'https://www.amazon.com/path/to/product',
            'bitly_id' => '1',
            'source_id' => '1',
            'user_id' => '1',
        ]);

        $expected = [
            'qrkey' => [
                'unique' => 'This Key already exists.',
            ],
        ];

        $this->assertSame($expected, $entity->getErrors());

        // test max length
        $entity = $this->QrCodes->newEntity([
            'qrkey' => str_repeat('a', 256),
            'name' => str_repeat('a', 256),
            'description' => 'description',
            'url' => 'https://www.amazon.com/path/to/product',
            'bitly_id' => str_repeat('a', 256),
            'source_id' => 1, // int instead of a string, like above.
            'user_id' => 1, // int instead of a string, like above.
        ]);

        $expected = [
            'qrkey' => [
                'maxLength' => 'The provided value must be at most `255` characters long',
            ],
            'name' => [
                'maxLength' => 'The provided value must be at most `255` characters long',
            ],
            'bitly_id' => [
                'maxLength' => 'The provided value must be at most `255` characters long',
            ],
        ];

        $this->assertSame($expected, $entity->getErrors());

        // test space in key
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'sow n scribe',
            'name' => 'Sow & Scribe',
            'description' => 'description',
            'url' => 'https://www.amazon.com/path/to/product',
            'bitly_id' => 'bitly_id',
            'source_id' => '1',
            'user_id' => '1',
        ]);

        $expected = [
            'qrkey' => [
                'characters' => 'Value cannot have a space in it.',
            ],
        ];

        $this->assertSame($expected, $entity->getErrors());

        // test bad url
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'newkey',
            'name' => 'New Name',
            'description' => 'description',
            'url' => 'Not a URL',
            'bitly_id' => 'bitly_id',
            'source_id' => '1',
            'user_id' => '1',
        ]);

        $expected = [
            'url' => [
                'url' => 'The URL is invalid.',
            ],
        ];

        $this->assertSame($expected, $entity->getErrors());

        // test valid entity
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'newsource',
            'name' => 'new name',
            'description' => 'description',
            'url' => 'https://www.amazon.com/path/to/product',
            'bitly_id' => 'bitly_id',
            'source_id' => 1, // int instead of a string, like above.
            'user_id' => 1, // int instead of a string, like above.
        ]);

        $expected = [];

        $this->assertSame($expected, $entity->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\QrCodesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        // bad name, and user id
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'newsource',
            'name' => 'new name',
            'description' => 'description',
            'url' => 'https://www.amazon.com/path/to/product',
            'bitly_id' => 'bitly_id',
            'source_id' => 999,
            'user_id' => 999,
        ]);
        $result = $this->QrCodes->checkRules($entity);
        $this->assertFalse($result);
        $expected = [
            'source_id' => [
                '_existsIn' => 'Unknown Source',
            ],
            'user_id' => [
                '_existsIn' => 'Unknown User',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // check that we are passing the rules.
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'newsource',
            'name' => 'new name',
            'description' => 'description',
            'url' => 'https://www.amazon.com/path/to/product',
            'bitly_id' => 'bitly_id',
            'source_id' => 1,
            'user_id' => 1,
        ]);
        $result = $this->QrCodes->checkRules($entity);
        $this->assertTrue($result);
        $expected = [];
        $this->assertSame($expected, $entity->getErrors());
    }

    /**
     * The custom finder
     *
     * @return void
     */
    public function testFinderKey(): void
    {
        // test getting an existing record
        $qrCode = $this->QrCodes->find('key', key: 'sownscribe') ->first();
        $this->assertSame(1, $qrCode->id);

        // test getting a non-existant record
        $qrCode = $this->QrCodes->find('key', key: 'dontexist') ->first();
        $this->assertNull($qrCode);
    }
}
