<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Sources;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\SourcesController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\Admin\SourcesController
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
        $this->get('https://localhost/admin/sources/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\SourcesController::dontexist()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\SourcesController::dontexist()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\SourcesController::dontexist()`');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/admin/sources/dontexist');
        Configure::write('debug', true);
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/sources');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fsources');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Sources/index');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Sources/index');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Sources/index');
        Configure::write('debug', true);
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::add()
     */
    public function testAdd(): void
    {
        $this->enableSecurityToken();

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/sources/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fsources%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources/add');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fsources%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Sources/add');
        $this->helperTestFormTag('/admin/sources/add', 'post');

        // Debug Off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->get('https://localhost/admin/sources/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fsources%2Fadd');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        Configure::write('debug', true);
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::edit()
     */
    public function testEdit(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->get('https://localhost/admin/sources/edit/3');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fsources%2Fedit%2F3');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources/edit/3');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fsources%2Fedit%2F3');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/edit/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Sources/edit');
        $this->helperTestFormTag('/admin/sources/edit/3', 'put');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/sources/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }

    /**
     * Test delete method
     *
     * The redirects here should not inlcude the query string.
     * Sonce a delete() http method is also treated similar to a post.
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::delete()
     */
    public function testDelete(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->delete('https://localhost/admin/sources/delete/3');
        $this->assertRedirectEquals('https://localhost/users/login');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/sources/delete/3');
        $this->assertRedirectEquals('https://localhost/admin');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/sources/delete/3');
        $this->assertRedirectEquals('https://localhost/admin/sources');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The source `Delete Me` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // test admin with another source
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/sources/delete/2');
        $this->assertRedirectEquals('https://localhost/admin/sources');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The source `Etsy` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->delete('https://localhost/admin/sources/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/sources/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/sources/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/sources/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }
}
