<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Application;
use App\Model\Table\QrCodesTable;
use App\Model\Table\QrImagesTable;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
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
     * TODO: Test uploading of images
     */

    /**
     * Test the image's file
     *
     * @return void
     */
    public function testEntityImagePath(): void
    {
        Configure::write('debug', true);

        $tmpdir = Configure::read('App.paths.qr_images');
        // make sure this setting exists.
        $this->assertNotNull($tmpdir);

        // for tests, we use this dir so we don't mess with the actual dir
        // we also copy over the assets in QrImagesFixture::insert()

        $tmpdir = TMP . 'qr_images_test';
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
    }

    /**
     * Test the image's file
     *
     * @return void
     */
    public function testEntityImageThumb(): void
    {
        Configure::write('debug', true);
        $this->loadRoutes();

        $tmpdir = Configure::read('App.paths.qr_images');
        // make sure this setting exists.
        $this->assertNotNull($tmpdir);

        // for tests, we use this dir so we don't mess with the actual dir
        // we also copy over the assets in QrImagesFixture::insert()

        $tmpdir = TMP . 'qr_images_test';

        // make sure the image actually exists.
        $entity = $this->QrImages->get(1);
        $entityPath = $tmpdir . DS . $entity->qr_code_id . DS . $entity->id . '.' . $entity->ext;
        $this->assertTrue(is_file($entityPath));
        $this->assertSame($entityPath, $entity->path);

        // test the 3 different thumbnail sizes.
        // the small thumbnail
        $thumbPathSm = $tmpdir . DS . $entity->qr_code_id . DS . $entity->id . '-thumb-sm.' . $entity->ext;
        // make sure it doesn't exist
        if (is_file($thumbPathSm)) {
            unlink($thumbPathSm);
        }
        $this->assertFalse(is_file($thumbPathSm));
        $this->assertSame($thumbPathSm, $entity->path_sm);
        $this->assertTrue(is_file($thumbPathSm));

        // the medium thumbnail
        $thumbPathMd = $tmpdir . DS . $entity->qr_code_id . DS . $entity->id . '-thumb-md.' . $entity->ext;
        // make sure it doesn't exist
        if (is_file($thumbPathMd)) {
            unlink($thumbPathMd);
        }
        $this->assertFalse(is_file($thumbPathMd));
        $this->assertSame($thumbPathMd, $entity->path_md);
        $this->assertTrue(is_file($thumbPathMd));

        // the large thumbnail
        $thumbPathLg = $tmpdir . DS . $entity->qr_code_id . DS . $entity->id . '-thumb-lg.' . $entity->ext;
        // make sure it doesn't exist
        if (is_file($thumbPathLg)) {
            unlink($thumbPathLg);
        }
        $this->assertFalse(is_file($thumbPathLg));
        $this->assertSame($thumbPathLg, $entity->path_lg);
        $this->assertTrue(is_file($thumbPathLg));
    }

    /**
     * Test the entity itself
     *
     * @return void
     * @uses \App\Model\Entity\User
     */
    public function testEntityOwnership(): void
    {
        // admin user
        $admin = $this->QrImages->QrCodes->Users->get(1);
        // regular user
        $regular = $this->QrImages->QrCodes->Users->get(2);

        // get a qr image they own.
        $admins_image = $this->QrImages->get(1, contain: ['QrCodes']);
        // don't own
        $regulars_image = $this->QrImages->get(5, contain: ['QrCodes']);

        $this->assertTrue($admin->isMe($admins_image->qr_code));
        $this->assertFalse($admin->isMe($regulars_image->qr_code));

        $this->assertFalse($regular->isMe($admins_image->qr_code));
        $this->assertTrue($regular->isMe($regulars_image->qr_code));

        // pull it from the App\Application()
        $App = new Application(CONFIG);
        $admin->setAuthorization($App->getAuthorizationService(new ServerRequest()));
        $regular->setAuthorization($App->getAuthorizationService(new ServerRequest()));

        // test auth.
        $this->assertTrue($admin->can('edit', $admins_image));
        $this->assertTrue($admin->can('edit', $regulars_image));

        $this->assertFalse($regular->can('edit', $admins_image));
        $this->assertTrue($regular->can('edit', $regulars_image));

        // missing qr_code
        $admins_image = $this->QrImages->get(1);
        $regulars_image = $this->QrImages->get(5);

        $this->assertFalse($regular->can('edit', $admins_image));
        $this->assertFalse($regular->can('edit', $regulars_image));
    }
}
