<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategoriesQrCodesTable;
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
        $this->CategoriesQrCodes = $this->getTableLocator()->get('CategoriesQrCodes', $config);
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
        $this->assertInstanceOf(\Cake\ORM\Association\HasMany::class, $Associations->get('Categories'));
        $this->assertInstanceOf(\App\Model\Table\CategoriesTable::class, $Associations->get('Categories')->getTarget());
        $Association = $this->CategoriesQrCodes->Categories;
        $this->assertSame('Categories', $Association->getName());
        $this->assertSame('category_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(\Cake\ORM\Association\HasMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(\App\Model\Table\QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
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
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\CategoriesQrCodesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
