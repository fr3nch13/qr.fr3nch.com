<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\UsersController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax.
 *
 * @uses \App\Controller\Admin\UsersController
 */
class TemplateTest extends BaseControllerTest
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
    public function testDashboardNormal(): void
    {
        // not logged in
        $this->get('https://localhost/admin');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardIndex();
        $this->helperTestTemplate('Admin/Users/dashboard');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardIndex();
        $this->helperTestTemplate('Admin/Users/dashboard');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/dashboard');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardIndex();
        $this->helperTestTemplate('Admin/Users/dashboard');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/dashboard');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardIndex();
        $this->helperTestTemplate('Admin/Users/dashboard');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test dashboard method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::dashboard()
     */
    public function testDashboardAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('https://localhost/admin');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Users/dashboard');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Users/dashboard');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/dashboard');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Users/dashboard');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/dashboard');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Users/dashboard');
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::index()
     */
    public function testIndexNormal(): void
    {
        // not logged in
        $this->get('https://localhost/admin/users');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardIndex();
        $this->helperTestTemplate('Admin/Users/index');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::index()
     */
    public function testIndexAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('https://localhost/admin/users');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Users/index');
    }

    /**
     * Test private view method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::view()
     */
    public function testViewNormal(): void
    {
        // not logged in
        $this->get('https://localhost/admin/users/view/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fusers%2Fview%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, other user
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/view/1');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers%2Fview%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, self
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/view/2');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/Users/view');

        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/view');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/Users/view');

        // test with admin, other user
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/view/2');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/Users/view');

        // test with admin, self
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/Users/view');

        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/view');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/Users/view');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test private view method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::view()
     */
    public function testViewAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('https://localhost/admin/users/view/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fusers%2Fview%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, can't view other user's private profile page
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/view/1');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers%2Fview%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, self
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/view/2');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Users/view');

        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/view');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Users/view');

        // test with admin, other user
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/view/2');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Users/view');

        // test with admin, self
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Users/view');

        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/view');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Users/view');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::add()
     */
    public function testAddNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/add');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/add');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardForm();
        $this->helperTestTemplate('Admin/Users/add');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::add()
     */
    public function testAddAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/add');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Users/add');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::edit()
     */
    public function testEditNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/edit/1');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardForm();
        $this->helperTestTemplate('Admin/Users/edit');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::edit()
     */
    public function testEditAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/users/edit/1');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fusers%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Users/edit');
    }
}
