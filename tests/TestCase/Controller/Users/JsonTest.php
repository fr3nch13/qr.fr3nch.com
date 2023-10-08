<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\UsersController Test Case
 *
 * Test that the Json output is correct.
 * Specifically that fields are there that should be,
 * and not there that shouldn't be,
 *
 * @uses \App\Controller\UsersController
 */
class JsonTest extends BaseControllerTest
{
    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->requestAsJson();
    }

    /**
     * Test login method
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLogin(): void
    {
        // a get
        $this->get('https://localhost/users/login');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['result']));
        $this->assertTrue(empty($content['result']));

        $this->assertTrue(isset($content['errors']));
        $this->assertFalse(empty($content['errors']));

        $expected = [
            'Login credentials not found',
        ];
        $this->assertSame($expected, $content['errors']);

        // a post fail
        $this->post('https://localhost/users/login.json', []);
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['result']));
        $this->assertTrue(empty($content['result']));

        $this->assertTrue(isset($content['errors']));
        $this->assertFalse(empty($content['errors']));

        $expected = [
            'Login credentials not found',
        ];
        $this->assertSame($expected, $content['errors']);

        // a post login fail
        $this->post('https://localhost/users/login.json', [
            'email' => 'admin@example.com',
            'password' => 'badpassword',
        ]);
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['result']));
        $this->assertTrue(empty($content['result']));

        $this->assertTrue(isset($content['errors']));
        $this->assertFalse(empty($content['errors']));

        $expected = [
            'Login credentials not found',
        ];
        $this->assertSame($expected, $content['errors']);

        // a post success
        $this->post('https://localhost/users/login.json', [
            'email' => 'admin@example.com',
            'password' => 'admin',
        ]);
                $this->assertRedirectEquals('https://localhost/');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');

        $this->logoutUser();

        // a post success
        $this->post('https://localhost/users/login.json?redirect=%2Ftags', [
            'email' => 'admin@example.com',
            'password' => 'admin',
        ]);
                $this->assertRedirectEquals('https://localhost/tags');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\UsersController::index()
     */
    public function testIndex(): void
    {
        $this->loginUserAdmin();
        $this->get('https://localhost/users.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['users']));
        $this->assertCount(3, $content['users']);

        $item = $content['users'][1];
        $this->assertSame(2, $item['id']);
        $this->assertFalse(isset($item['password']));
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\UsersController::view()
     */
    public function testView(): void
    {
        $this->loginUserAdmin();
        $this->get('https://localhost/users/view/2.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['user']));
        $this->assertSame(2, $content['user']['id']);
        $this->assertFalse(isset($content['user']['password']));
    }

    /**
     * Test profile method
     *
     * @return void
     * @uses \App\Controller\UsersController::profile()
     */
    public function testProfile(): void
    {
        $this->loginUserAdmin();
        $this->get('https://localhost/users/profile/2.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['user']));
        $this->assertSame(2, $content['user']['id']);
        $this->assertFalse(isset($content['user']['password']));
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\UsersController::add()
     */
    public function testAdd(): void
    {
        $this->loginUserAdmin();
        // a get
        $this->get('https://localhost/users/add.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['user']));
        $this->assertTrue(empty($content['user']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        // a post fail
        $this->post('https://localhost/users/add.json', []);
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['user']));
        $this->assertTrue(empty($content['user']));

        $this->assertTrue(isset($content['errors']));
        $this->assertFalse(empty($content['errors']));

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
        $this->assertSame($expected, $content['errors']);

        // a post success
        $this->post('https://localhost/users/add.json', [
            'name' => 'New JSON User',
            'email' => 'newjsonuser@example.com',
            'password' => 'password',
        ]);
        $this->assertRedirectEquals('https://localhost/users/view/4.json');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\UsersController::edit()
     */
    public function testEdit(): void
    {
        $this->loginUserAdmin();
        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/users/edit/1.json');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['user']));
        $this->assertFalse(empty($content['user']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        // a put success
        $this->put('https://localhost/users/edit/1.json', [
            'name' => 'Updated JSON User',
        ]);
        $this->assertRedirectEquals('https://localhost/users/view/1.json');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
