<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Categories;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\CategoriesController Test Case
 *
 * Tests that the proper http method is being used.
 *
 * @uses \App\Controller\CategoriesController
 */
class CrudTest extends BaseControllerTest
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
        $this->loginUserAdmin();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::index()
     */
    public function testIndex(): void
    {
        // get
        $this->get('/categories');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories index content">');
        $this->assertResponseContains('<h3>Categories</h3>');

        // post
        $this->post('/categories');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/categories');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/categories');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/categories');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::view()
     */
    public function testView(): void
    {
        // test get
        $this->get('/categories/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories view content">');
        $this->assertResponseContains('<h3>Books</h3>');

        // post
        $this->post('/categories/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/categories/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/categories/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/categories/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::add()
     */
    public function testAdd(): void
    {
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/categories/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/categories/add">');
        $this->assertResponseContains('<legend>Add Category</legend>');

        // post
        $this->post('/categories/add', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertRedirectContains('categories');
        $this->assertFlashMessage('The category has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('/categories/add', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/categories/add', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/categories/add');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::edit()
     */
    public function testEdit(): void
    {
        // test get
        $this->get('/categories/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" role="form" action="/categories/edit/1">');
        $this->assertResponseContains('<legend>Edit Category</legend>');

        // post
        $this->post('/categories/edit/1', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/categories/edit/1', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertRedirectContains('categories');
        $this->assertFlashMessage('The category has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // put
        $this->put('/categories/edit/1', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/categories/edit/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::delete()
     */
    public function testDelete(): void
    {
        // test get
        $this->get('/categories/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // post
        $this->post('/categories/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/categories/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/categories/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/categories/delete/3');
        $this->assertRedirectContains('categories');
        $this->assertFlashMessage('The category `Charms` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
