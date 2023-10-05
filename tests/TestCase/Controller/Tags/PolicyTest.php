<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Tags;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;
use Cake\Routing\Router;

/**
 * App\Controller\TagsController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\TagsController
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
     *
     * @return void
     * @uses \App\Controller\TagsController::index()
     */
    public function testDontexist(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/tags/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\TagsController::dontexist()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/tags/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\TagsController::dontexist()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/tags/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\TagsController::dontexist()`');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/tags/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/tags/dontexist');
        Configure::write('debug', true);
    }

    /**
     * Test index method
     *
     * Anyone can view the list of Tags.
     *
     * @return void
     * @uses \App\Controller\TagsController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/index');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/index');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/index');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/index');
        Configure::write('debug', true);
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\TagsController::view()
     */
    public function testView(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/tags/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/view');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/tags/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/view');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/tags/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/view');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/tags/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/tags/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/tags/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/tags/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\TagsController::add()
     */
    public function testAdd(): void
    {
        $this->enableSecurityToken();

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/tags/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Ftags%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/tags/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/add');
        $this->helperTestFormTag('/tags/add', 'post');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/tags/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/add');
        $this->helperTestFormTag('/tags/add', 'post');

        // Debug Off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->get('https://localhost/tags/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Ftags%2Fadd');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        Configure::write('debug', true);
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\TagsController::edit()
     */
    public function testEdit(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->get('https://localhost/tags/edit/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Ftags%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->get('https://localhost/tags/edit/4');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/edit');
        $this->helperTestFormTag('/tags/edit/4', 'patch');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->get('https://localhost/tags/edit/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Ftags%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/tags/edit/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/edit');
        $this->helperTestFormTag('/tags/edit/1', 'patch');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/tags/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/tags/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/tags/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/tags/edit');
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
     * @uses \App\Controller\TagsController::delete()
     */
    public function testDelete(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->delete('https://localhost/tags/delete/3');
        $this->assertRedirectEquals('https://localhost/users/login');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->delete('https://localhost/tags/delete/3');
        $this->assertRedirectEquals('https://localhost/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->delete('https://localhost/tags/delete/4');
        $this->assertRedirectEquals('https://localhost/tags');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The tag `Pig` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/tags/delete/3');
        $this->assertRedirectEquals('https://localhost/tags');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The tag `Delete Me` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // test admin with another tag
        $this->loginUserAdmin();
        $this->delete('https://localhost/tags/delete/2');
        $this->assertRedirectEquals('https://localhost/tags');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The tag `Journal` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->delete('https://localhost/tags/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->delete('https://localhost/tags/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/tags/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->delete('https://localhost/tags/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }
}
