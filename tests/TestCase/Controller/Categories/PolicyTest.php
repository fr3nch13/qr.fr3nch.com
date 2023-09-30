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
     * Test index method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->get('/categories');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories index content">');
        $this->assertResponseContains('<h3>Categories</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/categories');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories index content">');
        $this->assertResponseContains('<h3>Categories</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/categories');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories index content">');
        $this->assertResponseContains('<h3>Categories</h3>');
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
        $this->get('/categories/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories view content">');
        $this->assertResponseContains('<h3>Books</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/categories/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories view content">');
        $this->assertResponseContains('<h3>Books</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/categories/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories view content">');
        $this->assertResponseContains('<h3>Books</h3>');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('/categories/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'Categories',
            'action' => 'view',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::add()
     */
    public function testAdd(): void
    {
        // not logged in, so should redirect
        $this->get('/categories/add');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fcategories%2Fadd');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/categories/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/categories/add">');
        $this->assertResponseContains('<legend>Add Category</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/categories/add');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/?redirect=%2Fcategories%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::edit()
     */
    public function testEdit(): void
    {
        // not logged in, so should redirect
        $this->get('/categories/edit');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fcategories%2Fedit');

        // test with missing id and debug
        $this->loginUserAdmin();
        $this->get('/categories/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'Categories',
            'action' => 'edit',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/categories/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" role="form" action="/categories/edit/1">');
        $this->assertResponseContains('<legend>Edit Category</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/categories/edit/1');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/?redirect=%2Fcategories%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::delete()
     */
    public function testDelete(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        // not logged in, so should redirect
        $this->get('/categories/delete');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fcategories%2Fdelete');

        // test get with missing id and debug
        $this->loginUserAdmin();
        $this->get('/categories/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'Categories',
            'action' => 'delete',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on

        // test get with reqular, get
        $this->loginUserRegular();
        $this->get('/categories/delete/3');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/?redirect=%2Fcategories%2Fdelete%2F3');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with regular, post
        $this->loginUserRegular();
        $this->post('/categories/delete/3');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test delete with regular user
        $this->loginUserRegular();
        $this->delete('/categories/delete/3');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with admin, get
        $this->loginUserAdmin();
        $this->post('/categories/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test with admin, delete
        $this->loginUserAdmin();
        $this->delete('/categories/delete/3');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/categories');
        $this->assertFlashMessage('The category `Charms` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
