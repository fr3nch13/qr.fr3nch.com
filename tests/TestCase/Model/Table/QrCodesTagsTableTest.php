<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QrCodesTable;
use App\Model\Table\QrCodesTagsTable;
use App\Model\Table\TagsTable;
use Cake\ORM\Association\HasMany;
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
        /** @var \App\Model\Table\QrCodesTagsTable $QrCodesTags */
        $QrCodesTags = $this->getTableLocator()->get('QrCodesTags', $config);
        $this->QrCodesTags = $QrCodesTags;
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
     * Tests the class name of the Table
     *
     * @return void
     * @uses \App\Model\Table\QrCodesTagsTable::initialize()
     */
    public function testClassInstance(): void
    {
        $this->assertInstanceOf(QrCodesTagsTable::class, $this->QrCodesTags);
    }

    /**
     * Testing a method.
     *
     * @return void
     * @uses \App\Model\Table\QrCodesTagsTable::initialize()
     */
    public function testInitialize(): void
    {
        $this->assertSame('qr_codes_tags', $this->QrCodesTags->getTable());
        $this->assertSame('id', $this->QrCodesTags->getDisplayField());
        $this->assertSame('id', $this->QrCodesTags->getPrimaryKey());
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
        $this->assertInstanceOf(HasMany::class, $Associations->get('Tags'));
        $this->assertInstanceOf(TagsTable::class, $Associations->get('Tags')->getTarget());
        $Association = $this->QrCodesTags->Tags;
        $this->assertSame('Tags', $Association->getName());
        $this->assertSame('tag_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(HasMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
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
        // test no set fields
        $entity = $this->QrCodesTags->newEntity([]);
        $expected = [
            'tag_id' => [
                '_required' => 'This field is required',
            ],
            'qr_code_id' => [
                '_required' => 'This field is required',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // test setting the fields after an empty entity.
        $entity->set('tag_id', 'tag_id');
        $entity->set('qr_code_id', 'qr_code_id');

        $this->assertSame([], $entity->getErrors());

        // test valid entity
        $entity = $this->QrCodesTags->newEntity([
            'tag_id' => '1',
            'qr_code_id' => '1',
        ]);

        $expected = [];

        $this->assertSame($expected, $entity->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\QrCodesTagsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        // bad tag_id, and qr_code_id
        $entity = $this->QrCodesTags->newEntity([
            'tag_id' => 999,
            'qr_code_id' => 999,
        ]);
        $result = $this->QrCodesTags->checkRules($entity);
        $this->assertFalse($result);
        $expected = [
            'tag_id' => [
                '_existsIn' => 'Unknown Tag',
            ],
            'qr_code_id' => [
                '_existsIn' => 'Unknown QR Code',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // Check for unique tagging that already exists
        $entity = $this->QrCodesTags->newEntity([
            'tag_id' => 1,
            'qr_code_id' => 1,
        ]);
        $result = $this->QrCodesTags->checkRules($entity);
        $this->assertFalse($result);
        $expected = [
            'tags' => [
                '_isUnique' => 'This QR Code has already been tagged with this Tag',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // A valid entry
        $entity = $this->QrCodesTags->newEntity([
            'tag_id' => 2,
            'qr_code_id' => 2,
        ]);
        $result = $this->QrCodesTags->checkRules($entity);
        $this->assertTrue($result);
        $expected = [];
        $this->assertSame($expected, $entity->getErrors());
    }
}
