<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Users;

use App\Test\TestCase\Controller\LoggedInTrait;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\UsersController Test Case
 *
 * @uses \App\Controller\UsersController
 */
class CrudTest extends TestCase
{
    use IntegrationTestTrait;

    use LoggedInTrait;

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
        'app.Tags',
        'app.QrCodesTags',
    ];

    /**
     * Test login method
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLogin(): void
    {
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        //$this->loginUserAdmin();
        // test this without being logged in this is tested in PolicyTest

        // get
        $this->get('/users/login');
        $this->assertResponseOk();

        // post
        $this->post('/users/login');
        $this->assertResponseOk();

        // patch
        $this->patch('/users/login');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/users/login');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/users/login');
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
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        // get
        $this->loginUserAdmin();
        $this->get('/users/logout');
        $this->assertResponseCode(302);
        $this->assertRedirect('http://localhost/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // post
        $this->loginUserAdmin();
        $this->post('/users/logout');
        $this->assertResponseCode(302);
        $this->assertRedirect('http://localhost/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->loginUserAdmin();
        $this->patch('/users/logout');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->loginUserAdmin();
        $this->put('/users/logout');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->loginUserAdmin();
        $this->delete('/users/logout');
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
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // get
        $this->get('/users');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users index content">');
        $this->assertResponseContains('<h3>Users</h3>');

        // post
        $this->post('/users');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/users');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/users');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/users');
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
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/users/view/3');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users view content">');
        $this->assertResponseContains('<h3>Delete Me</h3>');

        // post
        $this->post('/users/view/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/users/view/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/users/view/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/users/view/3');
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
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/users/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/users/add">');
        $this->assertResponseContains('<legend>Add User</legend>');

        // post
        $this->post('/users/add', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('/users/add', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/users/add', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/users/add');
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
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/users/edit/3');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/users/edit/3">');
        $this->assertResponseContains('<legend>Edit User</legend>');

        // post
        $this->post('/users/edit/3', [
            'name' => 'Updated User',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/users/edit/3', [
            'name' => 'Updated User',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // put
        $this->put('/users/edit/3', [
            'name' => 'Updated User',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/users/edit/3');
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
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // post
        $this->post('/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/users/delete/3');
        $this->assertFlashMessage('The user has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users');
    }
}
