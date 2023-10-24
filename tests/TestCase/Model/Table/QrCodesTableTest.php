<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Lib\PhpQrGenerator;
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
        'app.QrImages',
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

        // a successful test, code generates and file exists.
        $entity = $this->QrCodes->get(1);
        $entityPath = $tmpdir . DS . $entity->id . '-dark.svg';
        $this->assertSame($entityPath, $entity->path);
        $this->assertTrue(is_file($entityPath));
        $entityPath = $tmpdir . DS . $entity->id . '-dark.svg';
        $this->assertSame($entityPath, $entity->path_dark);
        $this->assertTrue(is_file($entityPath));
        $entityPath = $tmpdir . DS . $entity->id . '-light.svg';
        $this->assertSame($entityPath, $entity->path_light);
        $this->assertTrue(is_file($entityPath));

        // test with a failed generation when envoking path
        $originalPath = Configure::read('App.paths.qr_codes');
        Configure::write('App.paths.qr_codes', TMP . 'dontexist');
        $this->assertNull($entity->path);
        $this->assertNull($entity->path_dark);
        $this->assertNull($entity->path_light);
        Configure::write('App.paths.qr_codes', $originalPath);
    }

    /**
     * Test Implemented Generator
     *
     * @return void
     */
    public function testImplementedGenerator1(): void
    {
        // existing entity
        $path_dark = Configure::read('App.paths.qr_codes') . DS . '1-dark.svg';
        if (file_exists($path_dark)) {
            unlink($path_dark);
        }
        $this->assertFalse(is_readable($path_dark));

        $path_light = Configure::read('App.paths.qr_codes') . DS . '1-light.svg';
        if (file_exists($path_light)) {
            unlink($path_light);
        }
        $this->assertFalse(is_readable($path_light));

        // test when trying to get the image path.
        $qrCodeImagePath = $this->QrCodes->getQrImagePath(1);
        $this->assertTrue(is_readable($path_dark));
        $this->assertTrue(is_readable($path_light));
        $this->assertSame($path_dark, $qrCodeImagePath);
        $qrCodeImagePath = $this->QrCodes->getQrImagePath(1, true);
        $this->assertSame($path_light, $qrCodeImagePath);

        // test not regenerating it.
        $qrCodeImagePath = $this->QrCodes->getQrImagePath(1);
        $this->assertSame($path_dark, $qrCodeImagePath);
        $qrCodeImagePath = $this->QrCodes->getQrImagePath(1, true);
        $this->assertSame($path_light, $qrCodeImagePath);

        $entity = $this->QrCodes->get(1);

        // test the AfterSave and regenerate
        unlink($path_dark);
        unlink($path_light);
        $this->assertFalse(is_readable($path_dark));
        $this->assertFalse(is_readable($path_light));
        $entity->name = 'Updated Name';
        $this->QrCodes->save($entity);
        $this->assertTrue(is_readable($path_dark));
        $this->assertTrue(is_readable($path_light));
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
        $path_dark = Configure::read('App.paths.qr_codes') . DS . '3-dark.svg';
        if (file_exists($path_dark)) {
            unlink($path_dark);
        }
        $this->assertFalse(is_readable($path_dark));
        $path_light = Configure::read('App.paths.qr_codes') . DS . '3-light.svg';
        if (file_exists($path_light)) {
            unlink($path_light);
        }
        $this->assertFalse(is_readable($path_light));

        $entity = $this->QrCodes->get(3);
        $this->assertSame($path_dark, $entity->path_dark);
        $this->assertTrue(is_readable($path_dark));
        $this->assertSame($path_light, $entity->path_light);
        $this->assertTrue(is_readable($path_light));
        $this->assertSame($path_dark, $entity->path);
        $this->assertTrue(is_readable($path_dark));
    }

    /**
     * Test Implemented Generator
     *
     * @return void
     */
    public function testImplementedGenerator3(): void
    {
        // new entity, check that it gets generated on a new save.
        $path_dark = Configure::read('App.paths.qr_codes') . DS . '6-dark.svg';
        if (file_exists($path_dark)) {
            unlink($path_dark);
        }
        $this->assertFalse(is_readable($path_dark));
        $path_light = Configure::read('App.paths.qr_codes') . DS . '6-light.svg';
        if (file_exists($path_light)) {
            unlink($path_light);
        }
        $this->assertFalse(is_readable($path_light));

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
        $this->assertTrue(is_readable($path_dark));
        $this->assertTrue(is_readable($path_light));
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
        $path_dark = Configure::read('App.paths.qr_codes') . DS . '10-dark.svg';
        if (file_exists($path_dark)) {
            unlink($path_dark);
        }
        $this->assertFalse(is_readable($path_dark));
        $path_light = Configure::read('App.paths.qr_codes') . DS . '10-light.svg';
        if (file_exists($path_light)) {
            unlink($path_light);
        }
        $this->assertFalse(is_readable($path_light));

        $qrCodeImagePath = $this->QrCodes->getQrImagePath(10);
        $this->assertTrue(is_readable($path_dark));
        $this->assertSame($path_dark, $qrCodeImagePath);

        $qrCodeImagePath = $this->QrCodes->getQrImagePath(10, true);
        $this->assertTrue(is_readable($path_light));
        $this->assertSame($path_light, $qrCodeImagePath);
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
        $path_dark = Configure::read('App.paths.qr_codes') . DS . '7-dark.svg';
        if (file_exists($path_dark)) {
            unlink($path_dark);
        }
        $this->assertFalse(is_readable($path_dark));
        $path_light = Configure::read('App.paths.qr_codes') . DS . '7-light.svg';
        if (file_exists($path_light)) {
            unlink($path_light);
        }
        $this->assertFalse(is_readable($path_dark));

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
        $this->assertFalse(is_readable($path_dark));
        $this->assertFalse(is_readable($path_light));
        $this->assertNull($entity->path);
        $this->assertNull($entity->path_dark);
        $this->assertNull($entity->path_light);
        Configure::write('App.paths.qr_codes', $originalPath);
    }

    /**
     * Test Implemented Generator
     *
     * @return void
     */
    public function testImplementedGenerator7(): void
    {
        // dark
        // existing entity
        $path_dark = Configure::read('App.paths.qr_codes') . DS . '1-dark.svg';
        $this->assertTrue(is_readable($path_dark));

        // test regenerating the image
        $qrCodeImagePath = $this->QrCodes->getQrImagePath(1, false, true);
        $this->assertTrue(is_readable($path_dark));
        $this->assertSame($path_dark, $qrCodeImagePath);

        // light
        // existing entity
        $path_light= Configure::read('App.paths.qr_codes') . DS . '1-light.svg';
        $this->assertTrue(is_readable($path_light));

        // test regenerating the image
        $qrCodeImagePath = $this->QrCodes->getQrImagePath(1, true, true);
        $this->assertTrue(is_readable($path_light));
        $this->assertSame($path_light, $qrCodeImagePath);
    }

    /**
     * Test Implemented Generator
     *
     * @return void
     */
    public function testImplementedGenerator8(): void
    {
        // existing entity
        $path_dark = Configure::read('App.paths.qr_codes') . DS . '1-dark.svg';
        $this->assertTrue(is_readable($path_dark));
        $path_light = Configure::read('App.paths.qr_codes') . DS . '1-light.svg';
        $this->assertTrue(is_readable($path_light));

        $originalPath = Configure::read('App.paths.qr_codes');
        Configure::write('App.paths.qr_codes', TMP . 'dontexist');

        // test regenerating the image
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Unable to find the QR Image for the QR Code `Sow & Scribe`');
        $this->QrCodes->getQrImagePath(1, true);
        $this->assertFalse(is_readable($path_dark));
        $this->assertFalse(is_readable($path_light));

        Configure::write('App.paths.qr_codes', $originalPath);
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
        $path_dark = Configure::read('App.paths.qr_codes') . DS . '2-dark.svg';
        if (file_exists($path_dark)) {
            unlink($path_dark);
        }
        $this->assertFalse(is_readable($path_dark));
        $path_light = Configure::read('App.paths.qr_codes') . DS . '2-light.svg';
        if (file_exists($path_light)) {
            unlink($path_light);
        }
        $this->assertFalse(is_readable($path_light));

        $entity = $this->QrCodes->get(2);
        $QR = new PhpQrGenerator($entity);
        $QR->generate();
        $this->assertTrue(is_readable($path_dark));
        $this->assertTrue(is_readable($path_light));
    }
}
