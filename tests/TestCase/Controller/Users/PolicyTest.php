<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\UsersController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\UsersController
 */
class PolicyTest extends BaseControllerTest
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
    }

    /**
     * Test login method
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLogin(): void
    {
        // not logged in
        $this->get('/users/login');
        $this->assertResponseOk();
        $this->assertResponseContains('<!-- START: App.Users/login -->');
        $this->assertResponseContains('<!-- END: App.Users/login -->');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/users/login');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/users/login');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/');

        // just test redirect
        $this->loginUserRegular();
        $this->get('/users/login?redirect=%2Fcategories');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/categories');
    }

    /**
     * Test logout method
     *
     * @return void
     * @uses \App\Controller\UsersController::logout()
     */
    public function testLogout(): void
    {
        // not logged in
        $this->get('/users/logout');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirect('http://localhost/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/users/logout');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirect('http://localhost/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/users/logout');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirect('http://localhost/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // just test redirect
        $this->loginUserRegular();
        $this->get('/users/logout?redirect=%2Fcategories');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirect('http://localhost/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
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
        // not logged in
        $this->get('/users');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/users');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users index content">');
        $this->assertResponseContains('<h3>Users</h3>');

        // test with reqular, should not allow a regular user to the list
        $this->loginUserRegular();
        $this->get('/users');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\UsersController::view()
     */
    public function testView(): void
    {
        // not logged in
        $this->get('/users/view/3');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fusers%2Fview%2F3');
        // TODO: add a flash message for unauthenticated requests.

        // test with admin
        $this->loginUserAdmin();
        $this->get('/users/view/3');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users view content">');
        $this->assertResponseContains('<h3>Delete Me</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/users/view/3');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users view content">');
        $this->assertResponseContains('<h3>Delete Me</h3>');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('/users/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get('/users/view');
        $this->assertResponseCode(500);
        // TODO(policy): This should apply a check `/users/view`
        $this->assertResponseContains('The request to `/users/view` did not apply any authorization checks.');
        Configure::write('debug', true); // turn it back on
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\UsersController::add()
     */
    public function testAdd(): void
    {
        // not logged in, so should redirect
        $this->get('/users/add');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fusers%2Fadd');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/users/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/users/add">');
        $this->assertResponseContains('<legend>Add User</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/users/add');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\UsersController::edit()
     */
    public function testEdit(): void
    {
        // not logged in, so should redirect
        $this->get('/users/edit');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fusers%2Fedit');

        // test with missing id and debug
        $this->loginUserAdmin();
        $this->get('/users/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('/users/edit');
        $this->assertResponseCode(500);
        // TODO(policy): This should apply a check `/users/edit`
        $this->assertResponseContains('The request to `/users/edit` did not apply any authorization checks.');
        Configure::write('debug', true); // turn it back on

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/users/edit/3');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/users/edit/3">');
        $this->assertResponseContains('<legend>Edit User</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/users/edit/3');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\UsersController::delete()
     */
    public function testDelete(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        // not logged in, so should redirect
        $this->get('/users/delete');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fusers%2Fdelete');

        // test get with missing id and debug
        $this->loginUserAdmin();
        $this->get('/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test get with reqular, get
        $this->loginUserRegular();
        $this->get('/users/delete/3');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with regular, post
        $this->loginUserRegular();
        $this->post('/users/delete/3');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test delete with regular user
        $this->loginUserRegular();
        $this->delete('/users/delete/3');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with admin, get
        $this->loginUserAdmin();
        $this->post('/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test with admin, delete, no ID
        $this->loginUserAdmin();
        $this->delete('/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin, post no data, no CSRF
        $this->loginUserAdmin();
        $this->delete('/users/delete/3');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users');
        $this->assertFlashMessage('The user `Delete Me` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
