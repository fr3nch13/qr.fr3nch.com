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
        $this->helperTestAlert('The category could not be saved. Please, try again.', 'danger');
        $this->assertResponseContains('<div class="categories form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/categories/add">');
        $this->assertResponseContains('<legend>Add Category</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('id="name-error"');
        $this->assertResponseContains('id="description-error"');

        // test success
        $this->post('https://localhost/categories/add', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertRedirectEquals('categories');
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
        $this->patch('https://localhost/categories/edit/1', [
            'name' => 'Journals', // an existing record
            'parent_id' => 4, // this doesn't exist
        ]);
        $this->assertResponseOk();
        $this->helperTestAlert('The category could not be saved. Please, try again.', 'danger');
        $this->assertResponseContains('<div class="categories form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" role="form" action="/categories/edit/1">');
        $this->assertResponseContains('<legend>Edit Category</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('id="name-error"');
        // this should be here, but if validation fails for, the buildRules doesn't even seem to get called.
        $this->assertResponseContains('id="parent-id-error"');

        // test success
        $this->patch('https://localhost/categories/edit/1', [
            'name' => 'New Category',
            'description' => 'The Description',
            'parent_id' => 3, // this doesn't exist
        ]);
        $this->assertRedirectEquals('categories');
        $this->assertFlashMessage('The category has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
