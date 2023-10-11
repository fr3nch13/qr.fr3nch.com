<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\UsersController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\Admin\UsersController
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
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::add()
     */
    public function testAdd(): void
    {
        $this->loginUserAdmin();

        // test failed
        $this->post('https://localhost/admin/users/add', [
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/add');
        $this->helperTestFormTag('/admin/users/add');
        $this->helperTestAlert('The user could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This field is required', 'name-error');
        $this->helperTestFormFieldError('This field is required', 'email-error');
        $this->helperTestFormFieldError('This field is required', 'password-error');

        // formatting fail
        $this->post('https://localhost/admin/users/add', [
            'name' => 'New User',
            'email' => 'new user@example.com', // invalid email
            // missing password
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/add');
        $this->helperTestFormTag('/admin/users/add');
        $this->helperTestAlert('The user could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('The provided value must be an e-mail address', 'email-error');
        $this->helperTestFormFieldError('This field is required', 'password-error');

        // existing email
        $this->post('https://localhost/admin/users/add', [
            'name' => 'New User',
            'email' => 'regular@example.com', // invalid email
            'password' => 'password',
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/add');
        $this->helperTestFormTag('/admin/users/add');
        $this->helperTestAlert('The user could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This Email already exists.', 'email-error');

        // test success
        $this->post('https://localhost/admin/users/add', [
            'name' => 'New User',
            'email' => 'newuser@example.com', // invalid email
            'password' => 'password',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/users/view/5');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::edit()
     */
    public function testEdit(): void
    {
        $this->loginUserAdmin();

        // test fail
        $this->put('https://localhost/admin/users/edit/3', [
            'name' => 'Regular', // an existing record, can have same name,
            'email' => 'regular@example.com', // existing record, should be unique
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Users/edit');
        $this->helperTestFormTag('/admin/users/edit/3', 'put');
        $this->helperTestAlert('The user could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This Email already exists.', 'email-error');
        // test put success
        $this->put('https://localhost/admin/users/edit/3', [
            'name' => 'New User',
            'description' => 'The Description',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/users/view/3');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
