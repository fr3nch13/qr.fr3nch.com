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
     * Test missing action - debug on
     *
     * @alert Keep the https://localhost/ as the HttpsEnforcerMiddleware will try to redirect.
     * @return void
     */
    public function testDontexistDebugOn(): void
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
    }

    /**
     * Test missing action - debug off
     *
     * @alert Keep the https://localhost/ as the HttpsEnforcerMiddleware will try to redirect.
     * @return void
     */
    public function testDontexistDebugOff(): void
    {
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/users/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/users/dontexist');
    }

    /**
     * Test login method
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLoginDebugOn(): void
    {
        $this->enableSecurityToken();

        // not logged in
        $this->get('https://localhost/users/login');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/login');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/admin');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/admin');
        $this->assertFlashMessage('Welcome back Regular', 'flash');
        $this->assertFlashElement('flash/success');

        // just test redirect
        $this->loginUserRegular();
        $this->get('https://localhost/users/login?redirect=%2Fsources');
        $this->assertRedirectEquals('https://localhost/sources');
    }

    /**
     * Test login method
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLoginDebugOff(): void
    {
        $this->enableSecurityToken();
        Configure::write('debug', false);

        // not logged in
        $this->get('https://localhost/users/login');
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/login');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/admin');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/users/login');
        $this->assertRedirectEquals('https://localhost/admin');
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
    public function testLogoutDebugOn(): void
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
     * Test logout method
     *
     * @return void
     * @uses \App\Controller\UsersController::logout()
     */
    public function testLogoutDebugOff(): void
    {
        Configure::write('debug', false);

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
     * Test public profile method
     *
     * @return void
     * @uses \App\Controller\UsersController::profile()
     */
    public function testProfileDebugOn(): void
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
    }

    /**
     * Test public profile method
     *
     * @return void
     * @uses \App\Controller\UsersController::profile()
     */
    public function testProfileDebugOff(): void
    {
        // test with missing id, no debug
        Configure::write('debug', false);
        $this->get('https://localhost/users/profile');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }
}
