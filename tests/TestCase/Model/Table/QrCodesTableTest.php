<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Exception\ThumbException;
use App\Lib\GoogleQrGenerator;
use App\Lib\LogoOptions;
use App\Lib\PhpQrGenerator;
use App\Lib\QRImageWithLogo;
use App\Model\Table\QrCodesTable;
use App\Model\Table\SourcesTable;
use App\Model\Table\TagsTable;
use App\Model\Table\UsersTable;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\TestSuite\TestCase;
use chillerlan\QRCode\Output\QRCodeOutputException;
use chillerlan\QRCode\QRCode as ChillerlanQRCode;

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
        'app.QrCodes',
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

        $this->loadRoutes();
        Configure::write('debug', true);

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
        $entity->set('source_id', '1');
        $entity->set('user_id', '1');

        $this->assertSame([], $entity->getErrors());

        // test existing fields
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'sownscribe',
            'name' => 'Sow & Scribe',
            'description' => 'description',
            'url' => 'https://www.amazon.com/path/to/product',
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
        ];

        $this->assertSame($expected, $entity->getErrors());

        // test space in key and bad URL
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'sow n scribe',
            'name' => 'Sow & Scribe',
            'description' => 'description',
            'url' => 'https://www.amazon.com/path/to/product',
            'source_id' => '1',
            'user_id' => '1',
        ]);

        $expected = [
            'qrkey' => [
                'qrkey' => 'Value cannot have a space in it.',
            ],
        ];

        $this->assertSame($expected, $entity->getErrors());

        // test bad url
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'newkey',
            'name' => 'New Name',
            'description' => 'description',
            'url' => 'Not a URL',
            'source_id' => '1',
            'user_id' => '1',
        ]);

        $expected = [
            'url' => [
                'qrurl' => 'The URL is invalid.',
            ],
        ];

        $this->assertSame($expected, $entity->getErrors());

        // test valid entity
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'newsource',
            'name' => 'new name',
            'description' => 'description',
            'url' => 'https://www.amazon.com/path/to/product',
            'source_id' => 1, // int instead of a string, like above.
            'user_id' => 1, // int instead of a string, like above.
        ]);

        $expected = [];

        $this->assertSame($expected, $entity->getErrors());

        // test valid entity
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'newsource',
            'name' => 'new name',
            'description' => 'description',
            'url' => 'tel://17025551212',
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
            'qrkey' => 'newentity',
            'name' => 'new name',
            'description' => 'description',
            'url' => 'https://www.amazon.com/path/to/product',
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
        $qrCode = $this->QrCodes->find('key', key: 'sownscribe')->first();
        $this->assertSame(1, $qrCode->id);

        // test getting a non-existant record
        $qrCode = $this->QrCodes->find('key', key: 'dontexist')->first();
        $this->assertNull($qrCode);
    }

    /**
     * The custom finder
     *
     * @return void
     */
    public function testFinderOwnedBy(): void
    {
        // admin
        $admin = $this->QrCodes->Users->get(1);
        $qrCodes = $this->QrCodes->find('ownedBy', user: $admin);
        $this->assertSame(3, $qrCodes->count());

        // reqular
        $reqular = $this->QrCodes->Users->get(2);
        $qrCodes = $this->QrCodes->find('ownedBy', user: $reqular);
        $this->assertSame(2, $qrCodes->count());

        // deleteme
        $reqular = $this->QrCodes->Users->get(3);
        $qrCodes = $this->QrCodes->find('ownedBy', user: $reqular);
        $this->assertSame(0, $qrCodes->count());
    }

    /**
     * Test the image's file
     *
     * @return void
     */
    public function testEntityImagePath(): void
    {
        Configure::write('debug', true);
        $this->loadRoutes();

        $tmpdir = TMP . 'qr_codes';

        // a successful test, code exists.
        $entity = $this->QrCodes->get(1);
        $entityPath = $tmpdir . DS . $entity->id . '.png';
        $this->assertTrue(is_file($entityPath));
        $this->assertSame($entityPath, $entity->path);

        // test with a failed generation when envoking path
        $originalPath = Configure::read('App.paths.qr_codes');
        Configure::write('App.paths.qr_codes', TMP . 'dontexist');
        $this->assertNull($entity->path);
        Configure::write('App.paths.qr_codes', $originalPath);
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

        $tmpdir = Configure::read('App.paths.qr_codes');
        // make sure this setting exists.
        $this->assertNotNull($tmpdir);

        // a successful test, code exists.
        $entity = $this->QrCodes->get(1);
        $entityPath = $tmpdir . DS . $entity->id . '.png';
        $this->assertTrue(is_file($entityPath));
        $this->assertSame($entityPath, $entity->path);

        // test the 3 different thumbnail sizes.
        // the small thumbnail
        $thumbPathSm = $tmpdir . DS . '1-thumb-sm.png';
        // make sure it doesn't exist
        if (is_file($thumbPathSm)) {
            unlink($thumbPathSm);
        }
        $this->assertFalse(is_file($thumbPathSm));
        $this->assertSame($thumbPathSm, $entity->path_sm);
        $this->assertTrue(is_file($thumbPathSm));

        // the medium thumbnail
        $thumbPathMd = $tmpdir . DS . '1-thumb-md.png';
        // make sure it doesn't exist
        if (is_file($thumbPathMd)) {
            unlink($thumbPathMd);
        }
        $this->assertFalse(is_file($thumbPathMd));
        $this->assertSame($thumbPathMd, $entity->path_md);
        $this->assertTrue(is_file($thumbPathMd));

        // the large thumbnail
        $thumbPathLg = $tmpdir . DS . '1-thumb-lg.png';
        // make sure it doesn't exist
        if (is_file($thumbPathLg)) {
            unlink($thumbPathLg);
        }
        $this->assertFalse(is_file($thumbPathLg));
        $this->assertSame($thumbPathLg, $entity->path_lg);
        $this->assertTrue(is_file($thumbPathLg));

        $this->QrCodes->delete($entity);

        $this->assertFalse(is_file($thumbPathSm));
        $this->assertFalse(is_file($thumbPathMd));
        $this->assertFalse(is_file($thumbPathLg));
        $this->assertFalse(is_file($entityPath));
    }

    /**
     * Test bad thumbnail size.
     *
     * @return void
     * @uses \App\Model\Entity\User
     */
    public function testThumbBadSize(): void
    {
        Configure::write('debug', true);

        $tmpdir = TMP . 'qr_images_test';
        Configure::write('App.paths.qr_images', $tmpdir);

        $entity = $this->QrCodes->get(1);
        $entityPath = $tmpdir . DS . $entity->id . '.png';
        $this->assertTrue(is_file($entityPath));
        $this->assertSame($entityPath, $entity->path);

        // the thumbnail bad size
        $this->expectException(ThumbException::class);
        $this->expectExceptionMessage('Unknown size option');
        $result = $entity->getPathThumb('bad');
        debug($result);
    }

    /**
     * Test Implemented Generator
     *
     * @return void
     */
    public function testImplementedGenerator1(): void
    {
        // existing entity
        $path = Configure::read('App.paths.qr_codes') . DS . '1.png';
        if (file_exists($path)) {
            unlink($path);
        }

        // test when trying to get the image path.
        $this->assertFalse(is_readable($path));
        $qrCodeImagePath = $this->QrCodes->getQrImagePath(1);
        $this->assertTrue(is_readable($path));
        $this->assertSame($path, $qrCodeImagePath);
        // test not regenerating it.
        $qrCodeImagePath = $this->QrCodes->getQrImagePath(1);
        $this->assertSame($path, $qrCodeImagePath);

        $entity = $this->QrCodes->get(1);

        // test the AfterSave and regenerate
        unlink($path);
        $this->assertFalse(is_readable($path));
        $entity->name = 'Updated Name';
        $this->QrCodes->save($entity);
        $this->assertTrue(is_readable($path));
    }

    /**
     * Test Implemented Generator
     *
     * @return void
     */
    public function testImplementedGenerator2(): void
    {
        // test existing qr_code with missing image
        // test that it gets generated.
        $path = Configure::read('App.paths.qr_codes') . DS . '3.png';
        if (file_exists($path)) {
            unlink($path);
        }
        $this->assertFalse(is_readable($path));

        $entity = $this->QrCodes->get(3);
        $this->assertSame($path, $entity->path);
        $this->assertTrue(is_readable($path));
    }

    /**
     * Test Implemented Generator
     *
     * @return void
     */
    public function testImplementedGenerator3(): void
    {
        // new entity, check that it gets generated on a new save.
        $path = Configure::read('App.paths.qr_codes') . DS . '6.png';
        if (file_exists($path)) {
            unlink($path);
        }
        $this->assertFalse(is_readable($path));
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'newentity6',
            'name' => 'new name 6',
            'description' => 'description',
            'url' => 'https://www.amazon.com/path/to/product',
            'source_id' => 1,
            'user_id' => 1,
        ]);
        $result = $this->QrCodes->checkRules($entity);
        $this->assertTrue($result);
        $expected = [];
        $this->assertSame($expected, $entity->getErrors());

        // save the new entity
        $this->QrCodes->save($entity);
        $this->assertTrue(is_readable($path));
    }

    /**
     * Test Implemented Generator
     *
     * @return void
     */
    public function testImplementedGenerator4(): void
    {
        // test nonexistant entity
        $this->expectException(RecordNotFoundException::class);
        $path = Configure::read('App.paths.qr_codes') . DS . '10.png';
        if (file_exists($path)) {
            unlink($path);
        }
        $this->assertFalse(is_readable($path));
        $qrCodeImagePath = $this->QrCodes->getQrImagePath(10);
        $this->assertTrue(is_readable($path));
        $this->assertSame($path, $qrCodeImagePath);
    }

    /**
     * Test Implemented Generator
     *
     * @return void
     */
    public function testImplementedGenerator5(): void
    {
        // test existing qr_code with missing image
        // test that it gets generated.
        $path = Configure::read('App.paths.qr_codes') . DS . '3.png';
        if (file_exists($path)) {
            unlink($path);
        }
        $this->assertFalse(is_readable($path));

        $entity = $this->QrCodes->get(3);
        $this->assertSame($path, $entity->path);
        $this->assertTrue(is_readable($path));
        unlink($path);
    }

    /**
     * Test Implemented Generator
     *
     * @return void
     */
    public function testImplementedGenerator6(): void
    {
        $originalPath = Configure::read('App.paths.qr_codes');
        Configure::write('App.paths.qr_codes', TMP . 'dontexist');

        // new entity, success, but can't generate code..
        $path = Configure::read('App.paths.qr_codes') . DS . '7.png';
        if (file_exists($path)) {
            unlink($path);
        }
        $this->assertFalse(is_readable($path));
        $entity = $this->QrCodes->newEntity([
            'qrkey' => 'newentity7',
            'name' => 'new name',
            'description' => 'description',
            'url' => 'https://www.amazon.com/path/to/product',
            'source_id' => 1,
            'user_id' => 1,
        ]);
        $result = $this->QrCodes->checkRules($entity);
        $this->assertTrue($result);
        $expected = [];
        $this->assertSame($expected, $entity->getErrors());

        // test with a failed generation when envoking path
        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('Unable to create QR Code.');
        $this->QrCodes->save($entity);
        $this->assertFalse(is_readable($path));
        $this->assertNull($entity->path);
        Configure::write('App.paths.qr_codes', $originalPath);
    }

    /**
     * Test Implemented Generator
     *
     * @return void
     */
    public function testImplementedGenerator7(): void
    {
        // existing entity
        $path = Configure::read('App.paths.qr_codes') . DS . '1.png';

        // test regenerating the image
        $this->assertTrue(is_readable($path));
        $qrCodeImagePath = $this->QrCodes->getQrImagePath(1, true);
        $this->assertTrue(is_readable($path));
        $this->assertSame($path, $qrCodeImagePath);
    }

    /**
     * Test Implemented Generator
     *
     * @return void
     */
    public function testImplementedGenerator8(): void
    {
        // existing entity
        $path = Configure::read('App.paths.qr_codes') . DS . '1.png';
        $this->assertTrue(is_readable($path));

        $originalPath = Configure::read('App.paths.qr_codes');
        Configure::write('App.paths.qr_codes', TMP . 'dontexist');

        // test regenerating the image
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Unable to find the QR Image for the QR Code `Sow & Scribe`');
        $this->QrCodes->getQrImagePath(1, true);
        $this->assertFalse(is_readable($path));

        Configure::write('App.paths.qr_codes', $originalPath);
    }

    /**
     * Test GoogleQrGenerator
     *
     * @return void
     */
    public function testGoogleQrGenerator(): void
    {
        $this->loadRoutes();
        Configure::write('debug', true);

        // existing entity
        $path = Configure::read('App.paths.qr_codes') . DS . '1.png';
        if (file_exists($path)) {
            unlink($path);
        }
        $this->assertFalse(is_readable($path));
        // test the Generator Directly
        $entity = $this->QrCodes->get(1);

        $QR = new GoogleQrGenerator($entity);
        $this->assertSame('https://localhost/f/sownscribe', $QR->data);
        $this->assertSame('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs=200x200&chl=https%3A%2F%2Flocalhost%2Ff%2Fsownscribe', $QR->compileUrl());
        $this->assertTrue($QR->save());
        $this->assertTrue(is_readable($path));
    }

    /**
     * Tests the PhpQrGenerator Library.
     *
     * @return void
     */
    public function testPhpQrGenerator(): void
    {
        $this->loadRoutes();
        Configure::write('debug', true);

        // test the Generator Directly
        $path = Configure::read('App.paths.qr_codes') . DS . '2.png';
        if (file_exists($path)) {
            unlink($path);
        }
        $this->assertFalse(is_readable($path));
        $entity = $this->QrCodes->get(2);
        $QR = new PhpQrGenerator($entity);
        $QR->generate();
        $this->assertTrue(is_readable($path));
    }

    /**
     * Tests the QRImageWithLogo Library.
     *
     * @return void
     */
    public function testQRImageWithLogoBadPaths(): void
    {
        $this->loadRoutes();
        Configure::write('debug', true);

        // test the QRImageWithLogo itself
        $this->expectException(QRCodeOutputException::class);
        $this->expectExceptionMessage('invalid logo');

        $options = new LogoOptions();
        $QR = new ChillerlanQRCode($options);
        $qrOutputInterface = new QRImageWithLogo($options, $QR->getMatrix('dataishere'));
        $qrOutputInterface->dump(
            '/bad/file/path',
            '/bar/logo/path'
        );
    }

    /**
     * Tests the QRImageWithLogo Library.
     *
     * @return void
     */
    public function testQRImageWithLogoNullPaths(): void
    {
        $this->loadRoutes();
        Configure::write('debug', true);

        // test the QRImageWithLogo itself
        $this->expectException(QRCodeOutputException::class);
        $this->expectExceptionMessage('logo is not set');

        $options = new LogoOptions();
        $QR = new ChillerlanQRCode($options);
        $qrOutputInterface = new QRImageWithLogo($options, $QR->getMatrix('dataishere'));
        $qrOutputInterface->dump(
            null,
            null
        );
    }
}
