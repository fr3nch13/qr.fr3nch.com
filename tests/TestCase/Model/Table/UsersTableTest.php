<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Application;
use App\Model\Entity\User;
use App\Model\Table\QrCodesTable;
use App\Model\Table\SourcesTable;
use App\Model\Table\TagsTable;
use App\Model\Table\UsersTable;
use Cake\Http\ServerRequest;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

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
        $config = $this->getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        /** @var \App\Model\Table\UsersTable $Users */
        $Users = $this->getTableLocator()->get('Users', $config);
        $this->Users = $Users;
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Tests the class name of the Table
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::initialize()
     */
    public function testClassInstance(): void
    {
        $this->assertInstanceOf(UsersTable::class, $this->Users);
    }

    /**
     * Testing a method.
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::initialize()
     */
    public function testInitialize(): void
    {
        $this->assertSame('users', $this->Users->getTable());
        $this->assertSame('name', $this->Users->getDisplayField());
        $this->assertSame('id', $this->Users->getPrimaryKey());
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
            $behavior = $this->Users->behaviors()->get($name);
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
     * @uses \App\Model\Table\UsersTable::initialize()
     */
    public function testAssociations(): void
    {
        // get all of the associations
        $Associations = $this->Users->associations();

        ////// foreach association.
        // make sure the association exists
        $this->assertNotNull($Associations->get('QrCodes'));
        $this->assertInstanceOf(HasMany::class, $Associations->get('QrCodes'));
        $this->assertInstanceOf(QrCodesTable::class, $Associations->get('QrCodes')->getTarget());
        $Association = $this->Users->QrCodes;
        $this->assertSame('QrCodes', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('Sources'));
        $this->assertInstanceOf(HasMany::class, $Associations->get('Sources'));
        $this->assertInstanceOf(SourcesTable::class, $Associations->get('Sources')->getTarget());
        $Association = $this->Users->Sources;
        $this->assertSame('Sources', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());

        // make sure the association exists
        $this->assertNotNull($Associations->get('Tags'));
        $this->assertInstanceOf(HasMany::class, $Associations->get('Tags'));
        $this->assertInstanceOf(TagsTable::class, $Associations->get('Tags')->getTarget());
        $Association = $this->Users->Tags;
        $this->assertSame('Tags', $Association->getName());
        $this->assertSame('user_id', $Association->getForeignKey());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        // test no set fields
        $user = $this->Users->newEntity([]);
        $expected = [
            'name' => [
                '_required' => 'This field is required',
            ],
            'email' => [
                '_required' => 'This field is required',
            ],
            'password' => [
                '_required' => 'This field is required',
            ],
        ];
        $this->assertSame($expected, $user->getErrors());

        // test setting the fields after an empty entity.
        $user->set('name', 'test user');
        $user->set('email', 'test@example.com');
        $user->set('password', 'password');

        $this->assertSame([], $user->getErrors());

        // test empty fields
        $user = $this->Users->newEntity([
            'name' => '',
            'email' => '',
            'password' => '',
        ]);

        $expected = [
            'name' => [
                '_empty' => 'The Name is required, and can not be empty.',
            ],
            'email' => [
                '_empty' => 'The Email is required, and can not be empty.',
            ],
            'password' => [
                '_empty' => 'The Password is required, and can not be empty.',
            ],
        ];

        $this->assertSame($expected, $user->getErrors());

        // test bad email, short password
        $user = $this->Users->newEntity([
            'name' => 'test',
            'email' => 'test',
            'password' => 'test',
        ]);

        $expected = [
            'email' => [
                'email' => 'The provided value must be an e-mail address',
            ],
            'password' => [
                'minLength' => 'The provided value must be at least `8` characters long',
            ],
        ];

        $this->assertSame($expected, $user->getErrors());

        // test max length
        $user = $this->Users->newEntity([
            'name' => str_repeat('a', 256),
            'email' => str_repeat('a', 256) . '@test.com',
            'password' => str_repeat('a', 256),
        ]);

        $expected = [
            'name' => [
                'maxLength' => 'The provided value must be at most `255` characters long',
            ],
            'email' => [
                'maxLength' => 'The provided value must be at most `255` characters long',
            ],
            'password' => [
                'maxLength' => 'The provided value must be at most `255` characters long',
            ],
        ];

        $this->assertSame($expected, $user->getErrors());

        // test unique email
        $user = $this->Users->newEntity([
            'name' => 'test user',
            'email' => 'admin@example.com',
            'password' => 'testtest',
        ]);

        $expected = [
            'email' => [
                'unique' => 'This Email already exists.',
            ],
        ];

        $this->assertSame($expected, $user->getErrors());

        // test valid entity
        $user = $this->Users->newEntity([
            'name' => 'test user',
            'email' => 'test@test.com',
            'password' => 'testtest',
        ]);

        $expected = [];

        $this->assertSame($expected, $user->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\UsersTable::buildRules()
     */
    public function testBuildRules(): void
    {
        // check the rules thow errors
        $entity = $this->Users->newEntity([
            'name' => 'required',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $result = $this->Users->checkRules($entity);
        // will be true as it doesn't even pass entity validation above.
        $this->assertTrue($result);

        $expected = [
            'email' => [
                'unique' => 'This Email already exists.',
            ],
        ];
        $this->assertSame($expected, $entity->getErrors());

        // check that we are passing the rules.
        $entity = $this->Users->newEntity([
            'password' => 'password',
            'name' => 'required',
            'email' => 'required@required.com',
        ]);
        $result = $this->Users->checkRules($entity);
        $this->assertTrue($result);
    }

    /**
     * Test the entity itself
     *
     * @return void
     * @uses \App\Model\Entity\User
     */
    public function testEntity(): void
    {
        // test the password hashing
        $entity = $this->Users->newEntity([
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
        ]);
        $this->assertSame(60, strlen($entity->password));
        $this->assertMatchesRegularExpression('/^\$[\/\d\w\.\$]+$/', $entity->password);

        // test the password hashing
        $entity = $this->Users->newEntity([
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => '',
        ]);
        $this->assertNull($entity->password);
    }

    /**
     * Test the entity itself
     *
     * @return void
     * @uses \App\Model\Entity\User
     */
    public function testEntityAdmin(): void
    {
        // admin user
        $admin = $this->Users->get(1);
        // regular user
        $regular = $this->Users->get(2);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($regular->isAdmin());
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
        $admin = $this->Users->get(1);
        // regular user
        $regular = $this->Users->get(2);

        // get a qr code they own.
        $admins_code = $this->Users->QrCodes->get(1);
        // don't own
        $regulars_code = $this->Users->QrCodes->get(4);

        $this->assertTrue($admin->isMe($admins_code));
        $this->assertFalse($admin->isMe($regulars_code));

        $this->assertFalse($regular->isMe($admins_code));
        $this->assertTrue($regular->isMe($regulars_code));

        // pull it from the App\Application()
        $App = new Application(CONFIG);
        $admin->setAuthorization($App->getAuthorizationService(new ServerRequest()));

        // test auth.
        $this->assertTrue($admin->can('edit', $admins_code));
        $this->assertTrue($admin->can('edit', $regulars_code));
        $this->assertSame(1, $admin->getIdentifier());
        $this->assertInstanceOf(User::class, $admin->getOriginalData());
        $this->assertSame(1, $admin->getOriginalData()->id);
    }
}
