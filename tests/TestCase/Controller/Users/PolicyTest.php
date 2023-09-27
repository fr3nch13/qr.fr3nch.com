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
        $this->assertRedirectContains('/qr-codes');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/users/login');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/qr-codes');

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

        // test with admin
        $this->loginUserAdmin();
        $this->get('/users');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users index content">');
        $this->assertResponseContains('<h3>Users</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/users');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users index content">');
        $this->assertResponseContains('<h3>Users</h3>');
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
        $this->assertResponseContains('Record not found in table `users`.');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get('/users/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Not Found');
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
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `add` on `App\Model\Entity\User`.');
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
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fusers%2Fedit');

        // test with missing id and debug
        $this->loginUserAdmin();
        $this->get('/users/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Record not found in table `users`.');

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
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `edit` on `App\Model\Entity\User`.');
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
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test get with reqular, get
        $this->loginUserRegular();
        $this->get('/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test post with regular, post
        $this->loginUserRegular();
        $this->post('/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test delete with regular user
        $this->loginUserRegular();
        $this->delete('/users/delete/3');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `delete` on `App\Model\Entity\User`.');

        // test post with admin, get
        $this->loginUserAdmin();
        $this->post('/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test with admin, post no data, no CSRF
        $this->loginUserAdmin();
        $this->delete('/users/delete/3');
        $this->assertFlashMessage('The user has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users');
    }
}
