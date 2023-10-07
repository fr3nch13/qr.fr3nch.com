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
        $this->enableCsrfToken();
    }

    /**
     * Test missing action
     *
     * @alert Keep the https://localhost/ as the HttpsEnforcerMiddleware will try to redirect.
     * @return void
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
        $this->enableSecurityToken();

        // not logged in
        $this->get('https://localhost/users/login');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/login');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/');
        $this->assertFlashMessage('Welcome back Regular', 'flash');
        $this->assertFlashElement('flash/success');

        // just test redirect
        $this->loginUserRegular();
        $this->get('https://localhost/users/login?redirect=%2Fsources');
        $this->assertRedirectEquals('https://localhost/sources');
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
        $this->assertRedirectEquals('https://localhost/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/logout');
        $this->assertRedirectEquals('https://localhost/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/logout');
        $this->assertRedirectEquals('https://localhost/users/login');
        $this->assertFlashMessage('You have been logged out', 'flash');
        $this->assertFlashElement('flash/success');

        // just test redirect
        $this->loginUserRegular();
        $this->get('https://localhost/users/logout?redirect=%2Fsources');
        $this->assertRedirectEquals('https://localhost/users/login');
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
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        // should not allow a regular user to the list
        $this->loginUserRegular();
        $this->get('https://localhost/users');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, debug off
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get('https://localhost/users');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        Configure::write('debug', true);

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/index');

        // test with admin, debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/users');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/index');
        Configure::write('debug', true);
    }

    /**
     * Test private profile method
     *
     * @return void
     * @uses \App\Controller\UsersController::view()
     */
    public function testView(): void
    {
        // not logged in
        $this->get('https://localhost/users/view/3');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers%2Fview%2F3');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/view/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/view');

        // test with reqular viewing self
        $this->loginUserRegular();
        $this->get('https://localhost/users/view/2');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/view');

        // regular user trying to view another user private profile
        $this->loginUserRegular();
        $this->get('https://localhost/users/view/3');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers%2Fview%2F3');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/users/view');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers%2Fview');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/view');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/view');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/view');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/view');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/users/view');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/view');
        Configure::write('debug', true);
    }

    /**
     * Test public profile method
     *
     * @return void
     * @uses \App\Controller\UsersController::profile()
     */
    public function testProfile(): void
    {
        // not logged in
        $this->get('https://localhost/users/profile/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/profile');

        // test with reqular viewing self
        $this->loginUserRegular();
        $this->get('https://localhost/users/profile/2');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/profile');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/profile/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/profile');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('https://localhost/users/profile');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->get('https://localhost/users/profile');
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
        $this->enableSecurityToken();

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/users/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/add');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/add');
        $this->helperTestFormTag('/users/add', 'post');

        // Debug Off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->get('https://localhost/users/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers%2Fadd');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        Configure::write('debug', true);

        // can't test success with debug off as it messes with the test env setup.
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\UsersController::edit()
     */
    public function testEdit(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->get('https://localhost/users/edit/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not me
        $this->loginUserRegular();
        $this->get('https://localhost/users/edit/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with regular user, me
        $this->loginUserRegular();
        $this->get('https://localhost/users/edit/2');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/edit');
        $this->helperTestFormTag('/users/edit/2', 'put');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/edit/2');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/edit');
        $this->helperTestFormTag('/users/edit/2', 'put');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/users/edit');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers%2Fedit');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/edit');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/edit');
        $this->helperTestFormTag('/users/edit', 'put');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/edit');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/edit');
        $this->helperTestFormTag('/users/edit', 'put');

        // Debug Off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->get('https://localhost/users/edit');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers%2Fedit');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        Configure::write('debug', true);

        // can't test success with debug off as it messes with the test env setup. and triggers a form tempering error.
    }

    /**
     * Test delete method
     *
     * The redirects here should not inlcude the query string.
     * Sonce a delete() http method is also treated similar to a post.
     *
     * @return void
     * @uses \App\Controller\UsersController::delete()
     */
    public function testDelete(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->delete('https://localhost/users/delete/3');
        $this->assertRedirectEquals('https://localhost/users/login');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, other user
        $this->loginUserRegular();
        $this->delete('https://localhost/users/delete/3');
        $this->assertRedirectEquals('https://localhost/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, me
        // only an admin can delete a user.
        $this->loginUserRegular();
        $this->delete('https://localhost/users/delete/2');
        $this->assertRedirectEquals('https://localhost/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/users/delete/3');
        $this->assertRedirectEquals('https://localhost/users');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The user `Delete Me` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->delete('https://localhost/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->delete('https://localhost/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // admin, debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->delete('https://localhost/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);

        // not logged in, debug off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->delete('https://localhost/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }
}
