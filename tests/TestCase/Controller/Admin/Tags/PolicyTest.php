<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Tags;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\TagsController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\Admin\TagsController
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
        $this->loginGuest();
        $this->get('https://localhost/admin/tags/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\TagsController::dontexist()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/tags/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\TagsController::dontexist()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\TagsController::dontexist()`');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/admin/tags/dontexist');
        Configure::write('debug', true);
    }

    /**
     * Test index method
     *
     * Anyone can view the list of Tags.
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/tags');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Ftags');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Tags/index');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Tags/index');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Tags/index');
        Configure::write('debug', true);
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::add()
     */
    public function testAdd(): void
    {
        $this->enableSecurityToken();

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/tags/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Ftags%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/tags/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Tags/add');
        $this->helperTestFormTag('/admin/tags/add', 'post');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Tags/add');
        $this->helperTestFormTag('/admin/tags/add', 'post');

        // Debug Off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->get('https://localhost/admin/tags/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Ftags%2Fadd');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        Configure::write('debug', true);
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::edit()
     */
    public function testEdit(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->get('https://localhost/admin/tags/edit/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Ftags%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->get('https://localhost/admin/tags/edit/4');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Tags/edit');
        $this->helperTestFormTag('/admin/tags/edit/4', 'put');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->get('https://localhost/admin/tags/edit/1');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Ftags%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, not owner
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags/edit/4');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Tags/edit');
        $this->helperTestFormTag('/admin/tags/edit/4', 'put');

        // test with admin, owner
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags/edit/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Tags/edit');
        $this->helperTestFormTag('/admin/tags/edit/1', 'put');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/tags/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/tags/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags/edit');
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
     * @uses \App\Controller\Admin\TagsController::delete()
     */
    public function testDelete(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->delete('https://localhost/admin/tags/delete/3');
        $this->assertRedirectEquals('https://localhost/users/login');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/tags/delete/3');
        $this->assertRedirectEquals('https://localhost/admin');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/tags/delete/4');
        $this->assertRedirectEquals('https://localhost/admin/tags');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The tag `Pig` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/tags/delete/3');
        $this->assertRedirectEquals('https://localhost/admin/tags');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The tag `Delete Me` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // test admin with another tag
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/tags/delete/2');
        $this->assertRedirectEquals('https://localhost/admin/tags');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The tag `Journal` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->delete('https://localhost/admin/tags/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/tags/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/tags/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/tags/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);

        // not logged in, debug off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->delete('https://localhost/admin/tags/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }
}
