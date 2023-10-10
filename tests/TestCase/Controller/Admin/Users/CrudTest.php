<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\UsersController Test Case
 *
 * Tests that the proper http method is being used.
 *
 * @uses \App\Controller\Admin\UsersController
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
     * Test dashboard method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::dashboard()
     */
    public function testDashboard(): void
    {
        $this->loginUserAdmin();

        // get
        $this->get('https://localhost/admin');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/dashboard');

        // get
        $this->get('https://localhost/admin/dashboard');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/dashboard');

        // get
        $this->get('https://localhost/admin/users/dashboard');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/dashboard');

        // post
        $this->post('https://localhost/admin');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::index()
     */
    public function testIndex(): void
    {
        $this->loginUserAdmin();

        // get
        $this->get('https://localhost/admin/users');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/index');

        // post
        $this->post('https://localhost/admin/users');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin/users');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/users');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/users');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::view()
     */
    public function testView(): void
    {
        $this->loginUserAdmin();

        // test get
        $this->get('https://localhost/admin/users/view/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/view');

        // post
        $this->post('https://localhost/admin/users/view/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin/users/view/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/users/view/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/users/view/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::add()
     */
    public function testAdd(): void
    {
        $this->loginUserAdmin();

        // test get
        $this->get('https://localhost/admin/users/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/add');

        // post
        $this->post('https://localhost/admin/users/add', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/users/view/4');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('https://localhost/admin/users/add', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/users/add', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/users/add');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::edit()
     */
    public function testEdit(): void
    {
        $this->loginUserAdmin();

        // test get
        $this->get('https://localhost/admin/users/edit/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/edit');

        // post
        $this->post('https://localhost/admin/users/edit/3', [
            'name' => 'Updated User',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin/users/edit/3', [
            'name' => 'Updated User',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/users/edit/3', [
            'name' => 'Updated User',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/users/view/3');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // delete
        $this->delete('https://localhost/admin/users/edit/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::delete()
     */
    public function testDelete(): void
    {
        $this->loginUserAdmin();

        // test get
        $this->get('https://localhost/admin/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // post
        $this->post('https://localhost/admin/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/users/delete/3');
        $this->assertFlashMessage('The user `Delete Me` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
        $this->assertRedirectEquals('https://localhost/admin/users');
    }
}
