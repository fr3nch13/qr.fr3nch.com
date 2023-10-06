<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\UsersController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax.
 *
 * TODO: Test specific HTML once templates are done.
 * labels: frontend, templates, tesing
 *
 * @uses \App\Controller\UsersController
 */
class ViewTest extends BaseControllerTest
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
     * @uses \App\Controller\UsersController::index()
     */
    public function testLoginNormal(): void
    {
        // not logged in
        $this->get('https://localhost/users/login');
        $this->assertResponseOk();
        $this->helperTestLayoutLogin();
        $this->helperTestTemplate('Users/login');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/');
        $this->assertFlashMessage('Welcome back Regular', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\UsersController::index()
     */
    public function testLoginAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('https://localhost/users/login');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Users/login');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/');
        $this->assertFlashMessage('Welcome back Regular', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\UsersController::index()
     */
    public function testIndexNormal(): void
    {
        // not logged in
        $this->get('https://localhost/users');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesDashboard();
        $this->helperTestTemplate('Users/index');
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\UsersController::index()
     */
    public function testIndexAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('https://localhost/users');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/users');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Users/index');
    }

    /**
     * Test private view method
     *
     * @return void
     * @uses \App\Controller\UsersController::view()
     */
    public function testViewNormal(): void
    {
        // not logged in
        $this->get('https://localhost/users/view/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers%2Fview%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, other user
        $this->loginUserRegular();
        $this->get('https://localhost/users/view/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers%2Fview%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, self
        $this->loginUserRegular();
        $this->get('https://localhost/users/view/2');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesDashboard();
        $this->helperTestTemplate('Users/view');

        $this->loginUserRegular();
        $this->get('https://localhost/users/view');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesDashboard();
        $this->helperTestTemplate('Users/view');

        // test with admin, other user
        $this->loginUserAdmin();
        $this->get('https://localhost/users/view/2');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesDashboard();
        $this->helperTestTemplate('Users/view');

        // test with admin, self
        $this->loginUserAdmin();
        $this->get('https://localhost/users/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesDashboard();
        $this->helperTestTemplate('Users/view');

        $this->loginUserAdmin();
        $this->get('https://localhost/users/view');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesDashboard();
        $this->helperTestTemplate('Users/view');
    }

    /**
     * Test private view method
     *
     * @return void
     * @uses \App\Controller\UsersController::view()
     */
    public function testViewAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('https://localhost/users/view/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers%2Fview%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, can't view other user's private profile page
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/users/view/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers%2Fview%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, self
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/users/view/2');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Users/view');

        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/users/view');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Users/view');

        // test with admin, other user
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/view/2');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Users/view');

        // test with admin, self
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Users/view');

        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/view');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Users/view');
    }

    /**
     * Test view public profile method
     *
     * @return void
     * @uses \App\Controller\UsersController::profile()
     */
    public function testProfileNormal(): void
    {
        // not logged in
        $this->get('https://localhost/users/profile/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();
        $this->helperTestTemplate('Users/profile');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/profile/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();
        $this->helperTestTemplate('Users/profile');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/profile/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();
        $this->helperTestTemplate('Users/profile');
    }

    /**
     * Test profile method
     *
     * @return void
     * @uses \App\Controller\UsersController::profile()
     */
    public function testProfileAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('https://localhost/users/profile/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Users/profile');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/users/profile/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Users/profile');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/profile/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Users/profile');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\UsersController::add()
     */
    public function testAddNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/users/add');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/users/add');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
        $this->helperTestTemplate('Users/add');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\UsersController::add()
     */
    public function testAddAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/users/add');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Users/add');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\UsersController::edit()
     */
    public function testEditNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/users/edit/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/users/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
        $this->helperTestTemplate('Users/edit');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\UsersController::edit()
     */
    public function testEditAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/users/edit/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Users/edit');
    }
}
