<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategoriesQrCodesTable;
use App\Model\Table\CategoriesTable;
use App\Model\Table\QrCodesTable;
use Cake\ORM\Association\HasMany;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CategoriesQrCodesTable Test Case
 */
class CategoriesQrCodesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CategoriesQrCodesTable
     */
    protected $CategoriesQrCodes;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
        'app.Categories',
        'app.Sources',
        'app.QrCodes',
        'app.CategoriesQrCodes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('CategoriesQrCodes') ? [] : ['className' => CategoriesQrCodesTable::class];
        /** @var \App\Model\Table\CategoriesQrCodesTable $CategoriesQrCodes */
        $CategoriesQrCodes = $this->getTableLocator()->get('CategoriesQrCodes', $config);
        $this->CategoriesQrCodes = $CategoriesQrCodes;
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->CategoriesQrCodes);

        parent::tearDown();
    }

    /**
     * Tests the class name of the Table
     *
     * @return void
     * @uses \App\Model\Table\CategoriesQrCodesTable::initialize()
     */
    public function testClassInstance(): void
    {
        $this->assertInstanceOf(CategoriesQrCodesTable::class, $this->CategoriesQrCodes);
    }

    /**
     * Testing a method.
     *
     * @return void
     * @uses \App\Model\Table\CategoriesQrCodesTable::initialize()
     */
    public function testInitialize(): void
    {
        $this->assertSame('categories_qr_codes', $this->CategoriesQrCodes->getTable());
        $this->assertSame('id', $this->CategoriesQrCodes->getDisplayField());
        $this->assertSame('id', $this->CategoriesQrCodes->getPrimaryKey());
    }

    /**
     * Test Associations
     *
     * @return void
     * @uses \App\Model\Table\CategoriesQrCodesTable::initialize()
     */
    public function testAssociations(): void
    {
        // get all of the associations
        $Associations = $this->CategoriesQrCodes->associations();

        ////// foreach association.
        // make sure the association exists
        $this->assertNotNull($Associations->get('Categories'));
        $this->assertInstanceOf(HasMany::class, $Associations->get('Categories'));
        $this->assertInstanceOf(CategoriesTable::class, $Associations->get('Categories')->getTarget());
        $Association = $this->CategoriesQrCodes->Categories;
        $this->assertSame('Categories', $Association->getName());
        $this->assertSame('category_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(HasMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
        $Association = $this->CategoriesQrCodes->QrCodes;
        $this->assertSame('QrCodes', $Association->getName());
        $this->assertSame('qr_code_id', $Association->getForeignKey());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\CategoriesQrCodesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        // test no set fields
        $entity = $this->CategoriesQrCodes->newEntity([]);
        $expected = [
            'category_id' => [
                '_required' => 'This field is required',
            ],
            'qr_code_id' => [
                '_required' => 'This field is required',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // test setting the fields after an empty entity.
        $entity->set('category_id', 'category_id');
        $entity->set('qr_code_id', 'qr_code_id');

        $this->assertSame([], $entity->getErrors());

        // test valid entity
        $entity = $this->CategoriesQrCodes->newEntity([
            'category_id' => '1',
            'qr_code_id' => '1',
        ]);

        $expected = [];

        $this->assertSame($expected, $entity->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\CategoriesQrCodesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        // bad category_id, and qr_code_id
        $entity = $this->CategoriesQrCodes->newEntity([
            'category_id' => 999,
            'qr_code_id' => 999,
        ]);
        $result = $this->CategoriesQrCodes->checkRules($entity);
        $this->assertFalse($result);
        $expected = [
            'category_id' => [
                '_existsIn' => 'Unknown Category',
            ],
            'qr_code_id' => [
                '_existsIn' => 'Unknown QR Code',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // Check for unique tagging that already exists
        $entity = $this->CategoriesQrCodes->newEntity([
            'category_id' => 2,
            'qr_code_id' => 1,
        ]);
        $result = $this->CategoriesQrCodes->checkRules($entity);
        $this->assertFalse($result);
        $expected = [
            'category' => [
                '_isUnique' => 'This QR Code has already been added to this Category',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // A valid entry
        $entity = $this->CategoriesQrCodes->newEntity([
            'category_id' => 2,
            'qr_code_id' => 2,
        ]);
        $result = $this->CategoriesQrCodes->checkRules($entity);
        $this->assertTrue($result);
        $expected = [];
        $this->assertSame($expected, $entity->getErrors());
    }

    /**
     * Test the entity itself
     *
     * @return void
     * @uses \App\Model\Entity\CategoriesQrCode
     */
    public function testEntity(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
