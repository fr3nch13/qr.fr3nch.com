<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Sources;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\SourcesController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\Admin\SourcesController
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
     * @uses \App\Controller\Admin\SourcesController::add()
     */
    public function testAdd(): void
    {
        // test failed
        $this->post('http://localhost:8080/admin/sources/add', [
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Sources/add');
        $this->helperTestFormTag('/admin/sources/add');
        $this->helperTestAlert('The source could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This field is required', 'name-error');
        $this->helperTestFormFieldError('This field is required', 'description-error');

        // existing fail
        $this->post('http://localhost:8080/admin/sources/add', [
            'name' => 'Etsy',
            'description' => 'description',
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Sources/add');
        $this->helperTestFormTag('/admin/sources/add');
        $this->helperTestAlert('The source could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This Name already exists.', 'name-error');

        // test success
        $this->post('http://localhost:8080/admin/sources/add', [
            'name' => 'new name',
            'description' => 'description',
        ]);
        $this->assertRedirectEquals('/admin/sources');
        $this->assertFlashMessage('The source has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::edit()
     */
    public function testEdit(): void
    {
        // test fail
        $this->put('http://localhost:8080/admin/sources/edit/1', [
            'name' => 'Etsy', // an existing record
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Sources/edit');
        $this->helperTestFormTag('/admin/sources/edit/1', 'put');
        $this->helperTestAlert('The source could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This Name already exists.', 'name-error');

        // test put success
        $this->put('http://localhost:8080/admin/sources/edit/1', [
            'name' => 'New Source',
            'description' => 'The Description',
        ]);
        $this->assertRedirectEquals('/admin/sources');
        $this->assertFlashMessage('The source has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
