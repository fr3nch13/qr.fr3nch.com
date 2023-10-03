<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QrCodesTable;
use App\Model\Table\QrImagesTable;
use App\Model\Table\TagsTable;
use Cake\ORM\Association\HasMany;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\QrImagesTable Test Case
 */
class QrImagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\QrImagesTable
     */
    protected $QrImages;

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
        'app.QrImages',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('QrImages') ? [] : ['className' => QrImagesTable::class];
        /** @var \App\Model\Table\QrImagesTable $QrImages */
        $QrImages = $this->getTableLocator()->get('QrImages', $config);
        $this->QrImages = $QrImages;
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->QrImages);

        parent::tearDown();
    }

    /**
     * Tests the class name of the Table
     *
     * @return void
     * @uses \App\Model\Table\QrImagesTable::initialize()
     */
    public function testClassInstance(): void
    {
        $this->assertInstanceOf(QrImagesTable::class, $this->QrImages);
    }

    /**
     * Testing a method.
     *
     * @return void
     * @uses \App\Model\Table\QrImagesTable::initialize()
     */
    public function testInitialize(): void
    {
        $this->assertSame('qr_codes_tags', $this->QrImages->getTable());
        $this->assertSame('id', $this->QrImages->getDisplayField());
        $this->assertSame('id', $this->QrImages->getPrimaryKey());
    }

    /**
     * Test Associations
     *
     * @return void
     * @uses \App\Model\Table\QrImagesTable::initialize()
     */
    public function testAssociations(): void
    {
        // get all of the associations
        $Associations = $this->QrImages->associations();

        ////// foreach association.
        // make sure the association exists
        $this->assertNotNull($Associations->get('Tags'));
        $this->assertInstanceOf(HasMany::class, $Associations->get('Tags'));
        $this->assertInstanceOf(TagsTable::class, $Associations->get('Tags')->getTarget());
        $Association = $this->QrImages->Tags;
        $this->assertSame('Tags', $Association->getName());
        $this->assertSame('tag_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(HasMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
        $Association = $this->QrImages->QrCodes;
        $this->assertSame('QrCodes', $Association->getName());
        $this->assertSame('qr_code_id', $Association->getForeignKey());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\QrImagesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        // test no set fields
        $entity = $this->QrImages->newEntity([]);
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
        $entity = $this->QrImages->newEntity([
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
     * @uses \App\Model\Table\QrImagesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        // bad tag_id, and qr_code_id
        $entity = $this->QrImages->newEntity([
            'tag_id' => 999,
            'qr_code_id' => 999,
        ]);
        $result = $this->QrImages->checkRules($entity);
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
        $entity = $this->QrImages->newEntity([
            'tag_id' => 1,
            'qr_code_id' => 1,
        ]);
        $result = $this->QrImages->checkRules($entity);
        $this->assertFalse($result);
        $expected = [
            'tags' => [
                '_isUnique' => 'This QR Code has already been tagged with this Tag',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // A valid entry
        $entity = $this->QrImages->newEntity([
            'tag_id' => 2,
            'qr_code_id' => 2,
        ]);
        $result = $this->QrImages->checkRules($entity);
        $this->assertTrue($result);
        $expected = [];
        $this->assertSame($expected, $entity->getErrors());
    }
}
