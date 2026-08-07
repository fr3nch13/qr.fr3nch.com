<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\UsersController Test Case
 *
 * Tests that the proper http method is being used.
 *
 * @uses \App\Controller\UsersController
 */
class CrudTest extends BaseControllerTest
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
    }

    /**
     * Test login method
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLogin(): void
    {
        //$this->loginUserAdmin();
        // test this without being logged in this is tested in PolicyTest

        // get
        $this->get('http://localhost:8080/users/login');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/login');

        // post
        $this->post('http://localhost:8080/users/login');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/login');

        // patch
        $this->patch('http://localhost:8080/users/login');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('http://localhost:8080/users/login');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('http://localhost:8080/users/login');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test logout method
     *
     * @return void
     * @uses \App\Controller\UsersController::logout()
     */
    public function testLogout(): void
    {
        // get
        $this->loginUserAdmin();
        $this->get('http://localhost:8080/users/logout');
        $this->assertRedirectEquals('/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // post
        $this->loginUserAdmin();
        $this->post('http://localhost:8080/users/logout');
        $this->assertRedirectEquals('/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->loginUserAdmin();
        $this->patch('http://localhost:8080/users/logout');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->loginUserAdmin();
        $this->put('http://localhost:8080/users/logout');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->loginUserAdmin();
        $this->delete('http://localhost:8080/users/logout');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
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

        // test get
        $this->get('http://localhost:8080/users/profile/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/profile');

        // post
        $this->post('http://localhost:8080/users/profile/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('http://localhost:8080/users/profile/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('http://localhost:8080/users/profile/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('http://localhost:8080/users/profile/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }
}
