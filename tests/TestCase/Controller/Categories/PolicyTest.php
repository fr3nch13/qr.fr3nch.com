<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Categories;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;
use Cake\Routing\Router;

/**
 * App\Controller\CategoriesController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\CategoriesController
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
        $this->enableSecurityToken();
    }

    /**
     * Test missing action
     *
     * @alert Keep the https://localhost/ as the HttpsEnforcerMiddleware will try to redirect.
     *
     * @return void
     * @uses \App\Controller\CategoriesController::index()
     */
    public function testDontexist(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/categories/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\CategoriesController::dontexist()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/categories/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\CategoriesController::dontexist()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/categories/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\CategoriesController::dontexist()`');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/categories/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/categories/dontexist');
        Configure::write('debug', true);
    }

    /**
     * Test index method
     *
     * Anyone can view the list of Categories.
     *
     * @return void
     * @uses \App\Controller\CategoriesController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/categories');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories index content">');
        $this->assertResponseContains('<h3>Categories</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/categories');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories index content">');
        $this->assertResponseContains('<h3>Categories</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/categories');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories index content">');
        $this->assertResponseContains('<h3>Categories</h3>');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/categories');
        /// This is expected as turning off debug messes with the $this->enableCsrfToken()
        // so look for it specifically
        $this->assertResponseCode(400);
        $this->assertResponseContains('Form tampering protection token validation failed.');
        Configure::write('debug', true);
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::view()
     */
    public function testView(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/categories/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories view content">');
        $this->assertResponseContains('<h3>Books</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/categories/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories view content">');
        $this->assertResponseContains('<h3>Books</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/categories/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories view content">');
        $this->assertResponseContains('<h3>Books</h3>');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/categories/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/categories/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/categories/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/categories/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::add()
     */
    public function testAdd(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/categories/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fcategories%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/categories/add');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fcategories%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/categories/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/categories/add">');
        $this->assertResponseContains('<legend>Add Category</legend>');

        // Debug Off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->get('https://localhost/categories/add');
        $this->assertRedirectEquals('users/login?redirect=%2Fcategories%2Fadd');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        Configure::write('debug', true);
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::edit()
     */
    public function testEdit(): void
    {
        // not logged
        $this->loginGuest();
        $this->get('https://localhost/categories/edit/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fcategories%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/categories/edit/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fcategories%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/categories/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" role="form" action="/categories/edit/1">');
        $this->assertResponseContains('<legend>Edit Category</legend>');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/categories/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/categories/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/categories/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/categories/edit');
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
     * @uses \App\Controller\CategoriesController::delete()
     */
    public function testDelete(): void
    {
        // not logged
        $this->loginGuest();
        $this->delete('https://localhost/categories/delete/3');
        $this->assertRedirectEquals('https://localhost/users/login');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->delete('https://localhost/categories/delete/3');
        $this->assertRedirectEquals('https://localhost/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/categories/delete/3');
        $this->assertRedirectEquals('https://localhost/categories');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The category `Charms` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->delete('https://localhost/categories/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->delete('https://localhost/categories/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/categories/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->delete('https://localhost/categories/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }
}
