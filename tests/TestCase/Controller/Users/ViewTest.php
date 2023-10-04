<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\UsersController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax, and json.
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

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/');
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

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/');
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
        $this->helperTestLayoutPagesIndex();
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
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\UsersController::view()
     */
    public function testViewNormal(): void
    {
        // not logged in
        $this->get('https://localhost/users/view/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fusers%2Fview%2F1');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/view/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers%2Fview%2F1');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();
    }

    /**
     * Test view method
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

        // test with reqular, can't view other user's private profile page
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/users/view/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers%2Fview%2F1');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test profile method
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

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/profile/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/profile/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();
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

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/users/profile/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/profile/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
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
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/users/add');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
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
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
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
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/users/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
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
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fusers');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }
}
