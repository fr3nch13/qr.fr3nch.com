<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;
use Cake\Routing\Router;

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
     * Test missing action
     *
     * @alert Keep the https://localhost/ as the HttpsEnforcerMiddleware will try to redirect.
     *
     * @return void
     * @uses \App\Controller\UsersController::index()
     */
    public function testDontexist(): void
    {
        // not logged in
        $this->get('https://localhost/users/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\UsersController::dontexist()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\UsersController::dontexist()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\UsersController::dontexist()`');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/users/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/users/dontexist');
        Configure::write('debug', true);
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
        $this->get('https://localhost/users/login');
        $this->assertResponseOk();
        $this->assertResponseContains('<!-- START: App.Users/login -->');
        $this->assertResponseContains('<!-- END: App.Users/login -->');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('/');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('/');

        // just test redirect
        $this->loginUserRegular();
        $this->get('https://localhost/users/login?redirect=%2Fcategories');
        $this->assertRedirectEquals('/categories');
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
        $this->get('https://localhost/users/logout');
        $this->assertRedirectEquals('users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/logout');
        $this->assertRedirectEquals('users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/logout');
        $this->assertRedirectEquals('users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // just test redirect
        $this->loginUserRegular();
        $this->get('https://localhost/users/logout?redirect=%2Fcategories');
        $this->assertRedirectEquals('users/login');
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
        $this->get('https://localhost/users');
        $this->assertRedirectEquals('/users/login?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users index content">');
        $this->assertResponseContains('<h3>Users</h3>');

        // test with reqular, should not allow a regular user to the list
        $this->loginUserRegular();
        $this->get('https://localhost/users');
        $this->assertRedirectEquals('/?redirect=%2Fusers');
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
        $this->get('https://localhost/users/view/3');
        $this->assertRedirectEquals('/users/login?redirect=%2Fusers%2Fview%2F3');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        // TODO: add a flash message for unauthenticated requests.
        // labels: flash

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/view/3');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users view content">');
        $this->assertResponseContains('<h3>Delete Me</h3>');

        // test with reqular viewing self
        $this->loginUserRegular();
        $this->get('https://localhost/users/view/2');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users view content">');
        $this->assertResponseContains('<h3>Regular</h3>');

        // regular user trying to view another user private profile
        $this->loginUserRegular();
        $this->get('https://localhost/users/view/3');
        $this->assertRedirectEquals('/?redirect=%2Fusers%2Fview%2F3');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('https://localhost/users/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'Users',
            'action' => 'view',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\UsersController::profile()
     */
    public function testProfile(): void
    {
        // not logged in
        $this->get('https://localhost/users/profile/3');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users view content">');
        $this->assertResponseContains('<h3>Delete Me</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/profile/3');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users view content">');
        $this->assertResponseContains('<h3>Delete Me</h3>');

        // test with reqular viewing self
        $this->loginUserRegular();
        $this->get('https://localhost/users/profile/2');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users view content">');
        $this->assertResponseContains('<h3>Regular</h3>');

        // regular user trying to view another user private profile
        $this->loginUserRegular();
        $this->get('https://localhost/users/profile/3');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users view content">');
        $this->assertResponseContains('<h3>Delete Me</h3>');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('https://localhost/users/profile');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'Users',
            'action' => 'profile',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
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
        $this->get('https://localhost/users/add');
        $this->assertRedirectEquals('/users/login?redirect=%2Fusers%2Fadd');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/users/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/users/add">');
        $this->assertResponseContains('<legend>Add User</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/users/add');
        $this->assertRedirectEquals('/?redirect=%2Fusers');
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
        $this->get('https://localhost/users/edit');
        $this->assertRedirectEquals('/users/login?redirect=%2Fusers%2Fedit');

        // test with missing id and debug
        $this->loginUserAdmin();
        $this->get('https://localhost/users/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'Users',
            'action' => 'edit',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/users/edit/3');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="users form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" role="form" action="/users/edit/3">');
        $this->assertResponseContains('<legend>Edit User</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/users/edit/3');
        $this->assertRedirectEquals('/?redirect=%2Fusers');
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

        // not logged in, missing id
        $this->get('https://localhost/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test get with missing id and debug
        $this->loginUserAdmin();
        $this->get('https://localhost/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'Users',
            'action' => 'delete',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on

        // test get with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/users/delete/3');
        $this->assertRedirectEquals('/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with regular, post
        $this->loginUserRegular();
        $this->post('https://localhost/users/delete/3');
        $this->assertRedirectEquals('/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test delete with regular user
        $this->loginUserRegular();
        $this->delete('https://localhost/users/delete/3');
        $this->assertRedirectEquals('/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with admin, get
        $this->loginUserAdmin();
        $this->post('https://localhost/users/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test with admin, post no data, no CSRF
        $this->loginUserAdmin();
        $this->delete('https://localhost/users/delete/3');
        $this->assertRedirectEquals('/users');
        $this->assertFlashMessage('The user `Delete Me` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
