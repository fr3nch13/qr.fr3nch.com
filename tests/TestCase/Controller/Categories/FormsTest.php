<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Categories;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\CategoriesController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\CategoriesController
 */
class FormsTest extends BaseControllerTest
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
     * Test add method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::add()
     */
    public function testAdd(): void
    {
        // test failed
        $this->post('https://localhost/categories/add', [
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Categories/add');
        $this->helperTestFormTag('/categories/add');
        $this->helperTestAlert('The category could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This field is required', 'name-error');
        $this->helperTestFormFieldError('This field is required', 'description-error');

        // test success
        $this->post('https://localhost/categories/add', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertRedirectEquals('https://localhost/categories/view/4');
        $this->assertFlashMessage('The category has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::edit()
     */
    public function testEdit(): void
    {
        // test fail validationDefault
        $this->put('https://localhost/categories/edit/1', [
            'name' => 'Journals', // an existing record
            'parent_id' => 4, // this doesn't exist
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Categories/edit');
        $this->helperTestFormTag('/categories/edit/1', 'put');
        $this->helperTestAlert('The category could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This Name already exists.', 'name-error');
        $this->helperTestFormFieldError('This Parent Category doesn&#039;t exist.', 'parent-id-error');

        // test success
        $this->put('https://localhost/categories/edit/1', [
            'name' => 'New Category',
            'description' => 'The Description',
            'parent_id' => 3, // this doesn't exist
        ]);
        $this->assertRedirectEquals('https://localhost/categories/view/1');
        $this->assertFlashMessage('The category has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
