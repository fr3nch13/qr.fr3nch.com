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
        $this->get('https://localhost/users/login');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/login');

        // post
        $this->post('https://localhost/users/login');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/login');

        // patch
        $this->patch('https://localhost/users/login');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/users/login');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/users/login');
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
        $this->get('https://localhost/users/logout');
        $this->assertRedirectEquals('https://localhost/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // post
        $this->loginUserAdmin();
        $this->post('https://localhost/users/logout');
        $this->assertRedirectEquals('https://localhost/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->loginUserAdmin();
        $this->patch('https://localhost/users/logout');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->loginUserAdmin();
        $this->put('https://localhost/users/logout');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->loginUserAdmin();
        $this->delete('https://localhost/users/logout');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
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

        // get
        $this->get('https://localhost/users');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/index');

        // post
        $this->post('https://localhost/users');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/users');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/users');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/users');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
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

        // test get
        $this->get('https://localhost/users/view/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/view');

        // post
        $this->post('https://localhost/users/view/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/users/view/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/users/view/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/users/view/3');
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
        $this->get('https://localhost/users/profile/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/profile');

        // post
        $this->post('https://localhost/users/profile/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/users/profile/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/users/profile/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/users/profile/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
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

        // test get
        $this->get('https://localhost/users/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/add');

        // post
        $this->post('https://localhost/users/add', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
        ]);
        $this->assertRedirectEquals('https://localhost/users/view/4');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('https://localhost/users/add', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/users/add', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/users/add');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
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

        // test get
        $this->get('https://localhost/users/edit/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/edit');

        // post
        $this->post('https://localhost/users/edit/3', [
            'name' => 'Updated User',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/users/edit/3', [
            'name' => 'Updated User',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/users/edit/3', [
            'name' => 'Updated User',
        ]);
        $this->assertRedirectEquals('https://localhost/users/view/3');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // delete
        $this->delete('https://localhost/users/edit/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\UsersController::delete()
     */
    public function testDelete(): void
    {
        $this->loginUserAdmin();

        // test get
        $this->get('https://localhost/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // post
        $this->post('https://localhost/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/users/delete/3');
        $this->assertFlashMessage('The user `Delete Me` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
        $this->assertRedirectEquals('https://localhost/users');
    }
}
