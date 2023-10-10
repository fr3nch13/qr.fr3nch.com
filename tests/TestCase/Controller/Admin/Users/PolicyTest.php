<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\UsersController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\Admin\UsersController
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
     * @alert Keep the https://localhost/admin/ as the HttpsEnforcerMiddleware will try to redirect.
     * @return void
     */
    public function testDontexist(): void
    {
        // not logged in
        $this->get('https://localhost/admin/users/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\UsersController::dontexist()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\UsersController::dontexist()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\UsersController::dontexist()`');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/admin/users/dontexist');
        Configure::write('debug', true);
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::index()
     */
    public function testDashboard(): void
    {
        // not logged in
        $this->get('https://localhost/admin');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        // should not allow a regular user to the list
        $this->loginUserRegular();
        $this->get('https://localhost/admin');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/dashboard');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/dashboard');

        // test with admin, debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/dashboard');
        Configure::write('debug', true);
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->get('https://localhost/admin/users');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        // should not allow a regular user to the list
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, debug off
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        Configure::write('debug', true);

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/index');

        // test with admin, debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/index');
        Configure::write('debug', true);
    }

    /**
     * Test private profile method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::view()
     */
    public function testView(): void
    {
        // not logged in
        $this->get('https://localhost/admin/users/view/3');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fusers%2Fview%2F3');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/view/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/view');

        // test with reqular viewing self
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/view/2');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/view');

        // regular user trying to view another user private profile
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/view/3');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers%2Fview%2F3');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/users/view');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fusers%2Fview');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/view');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/view');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/view');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/view');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/view');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/view');
        Configure::write('debug', true);
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::add()
     */
    public function testAdd(): void
    {
        $this->enableSecurityToken();

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/users/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fusers%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/add');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/add');
        $this->helperTestFormTag('/admin/users/add', 'post');

        // Debug Off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->get('https://localhost/admin/users/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fusers%2Fadd');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        Configure::write('debug', true);

        // can't test success with debug off as it messes with the test env setup.
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::edit()
     */
    public function testEdit(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->get('https://localhost/admin/users/edit/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fusers%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not me
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/edit/1');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with regular user, me
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/edit/2');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/edit');
        $this->helperTestFormTag('/admin/users/edit/2', 'put');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/edit/2');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/edit');
        $this->helperTestFormTag('/admin/users/edit/2', 'put');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/users/edit');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fusers%2Fedit');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/edit');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/edit');
        $this->helperTestFormTag('/admin/users/edit', 'put');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/edit');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/edit');
        $this->helperTestFormTag('/admin/users/edit', 'put');

        // Debug Off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->get('https://localhost/admin/users/edit');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fusers%2Fedit');
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
     * @uses \App\Controller\Admin\UsersController::delete()
     */
    public function testDelete(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->delete('https://localhost/admin/users/delete/3');
        $this->assertRedirectEquals('https://localhost/users/login');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, other user
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/users/delete/3');
        $this->assertRedirectEquals('https://localhost/admin');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, me
        // only an admin can delete a user.
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/users/delete/2');
        $this->assertRedirectEquals('https://localhost/admin');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/users/delete/3');
        $this->assertRedirectEquals('https://localhost/admin/users');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The user `Delete Me` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->delete('https://localhost/admin/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // admin, debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);

        // not logged in, debug off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->delete('https://localhost/admin/users/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }
}
