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
 * @uses \App\Controller\UsersController
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
     * Test login method
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLoginNormal(): void
    {
        // not logged in
        $this->get('https://localhost/users/login');
        $this->assertResponseOk();
        $this->helperTestLayoutLogin();
        $this->helperTestTemplate('Users/login');
        // validate the html
        $this->helperValidateHTML();

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/admin');
        $this->assertFlashMessage('Welcome back Regular', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/admin');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
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
        $this->assertRedirectEquals('https://localhost/admin');
        $this->assertFlashMessage('Welcome back Regular', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/admin');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');
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

        // validate the html
        $this->helperValidateHTML();
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
}
