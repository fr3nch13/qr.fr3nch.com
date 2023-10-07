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
        $this->get('https://localhost/categories');
        $this->assertResponseOk();
        $this->helperTestTemplate('Categories/index');

        // post
        $this->post('https://localhost/categories');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/categories');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/categories');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/categories');
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
        $this->get('https://localhost/categories/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Categories/view');

        // post
        $this->post('https://localhost/categories/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/categories/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/categories/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/categories/view/1');
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
        $this->get('https://localhost/categories/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Categories/add');

        // post
        $this->post('https://localhost/categories/add', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertRedirectEquals('https://localhost/categories/view/4');
        $this->assertFlashMessage('The category has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('https://localhost/categories/add', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/categories/add', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/categories/add');
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
        $this->get('https://localhost/categories/edit/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Categories/edit');

        // post
        $this->post('https://localhost/categories/edit/1', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/categories/edit/1', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/categories/edit/1', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertRedirectEquals('https://localhost/categories/view/1');
        $this->assertFlashMessage('The category has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // delete
        $this->delete('https://localhost/categories/edit/1');
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
        $this->get('https://localhost/categories/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // post
        $this->post('https://localhost/categories/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/categories/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/categories/delete/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/categories/delete/3');
        $this->assertRedirectEquals('https://localhost/categories');
        $this->assertFlashMessage('The category `Charms` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
