<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QrCodesTable;
use App\Model\Table\QrImagesTable;
use Cake\Core\Configure;
use Cake\ORM\Association\BelongsTo;
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
        $this->assertSame('qr_images', $this->QrImages->getTable());
        $this->assertSame('name', $this->QrImages->getDisplayField());
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

        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(BelongsTo::class, $Associations->get('QrCodes'));
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
            'name' => [
                '_required' => 'This field is required',
            ],
            'qr_code_id' => [
                '_required' => 'This field is required',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // test setting the fields after an empty entity.
        $entity->set('name', 'New Name');
        $entity->set('qr_code_id', 'qr_code_id');

        $this->assertSame([], $entity->getErrors());

        // test valid entity
        $entity = $this->QrImages->newEntity([
            'name' => 'New Image',
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
        // qr_code_id
        $entity = $this->QrImages->newEntity([
            'name' => 'New Image',
            'qr_code_id' => 999,
        ]);
        $result = $this->QrImages->checkRules($entity);
        $this->assertFalse($result);
        $expected = [
            'qr_code_id' => [
                '_existsIn' => 'Unknown QR Code',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // A valid entry
        $entity = $this->QrImages->newEntity([
            'name' => 'New Image',
            'qr_code_id' => 1,
        ]);
        $result = $this->QrImages->checkRules($entity);
        $this->assertTrue($result);
        $expected = [];
        $this->assertSame($expected, $entity->getErrors());
    }

    /**
     * Test the image's file
     *
     * @return void
     */
    public function testEntityImagePath(): void
    {
        Configure::write('debug', true);

        // backup the existing stuff.
        $tmpdir = TMP . 'qr_images';
        $moved = false;
        if (is_dir($tmpdir)) {
            // save the existing content
            $moved = TMP . 'qr_images_bak';
            sleep(1); // let things settle for a secound.
            rename($tmpdir, $moved);
        }

        // copy the assets in place
        if (!is_dir($tmpdir)) {
            mkdir($tmpdir);
        }
        $this->cpy(TESTS . 'assets' . DS . 'qr_images', $tmpdir);

        // test the paths here.
        // this one has an image file.
        $entity = $this->QrImages->get(1);
        $entityPath = $tmpdir . DS . $entity->qr_code_id . DS . $entity->id . '.' . $entity->ext;
        $this->assertTrue(is_file($entityPath));
        $this->assertSame($entityPath, $entity->path);

        // this one is missing an image file.
        $entity = $this->QrImages->get(4);
        $entityPath = $tmpdir . DS . $entity->qr_code_id . DS . $entity->id . '.' . $entity->ext;
        $this->assertFalse(is_file($entityPath));
        $this->assertNull($entity->path);

        // put the existing stuff back.
        if ($moved) {
            $this->rrmdir($tmpdir);
            rename($moved, $tmpdir);
        }
    }

    /**
     * Copies assets into place
     *
     * @param string $source The directory to copy
     * @param string $dest The directory to copy the source to
     * @return void
     */
    private function cpy(string $source, string $dest): void
    {
        if (is_dir($source)) {
            $dir_handle = opendir($source);
            if ($dir_handle) {
                while ($file = readdir($dir_handle)) {
                    if ($file != '.' && $file != '..') {
                        if (is_dir($source . DS . $file)) {
                            if (!is_dir($dest . DS . $file)) {
                                mkdir($dest . DS . $file);
                            }
                            $this->cpy($source . DS . $file, $dest . DS . $file);
                        } else {
                            copy($source . DS . $file, $dest . DS . $file);
                        }
                    }
                }
                closedir($dir_handle);
            }
        } else {
            copy($source, $dest);
        }
    }

    /**
     * Removes copied assets
     *
     * @param string $dir The directory to recursivly remove
     * @return void
     */
    private function rrmdir(string $dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            if ($objects) {
                foreach ($objects as $object) {
                    if ($object != '.' && $object != '..') {
                        if (is_dir($dir . DS . $object) && !is_link($dir . DS . $object)) {
                            $this->rrmdir($dir . DS . $object);
                        } else {
                            unlink($dir . DS . $object);
                        }
                    }
                }
            }
            rmdir($dir);
        }
    }
}
