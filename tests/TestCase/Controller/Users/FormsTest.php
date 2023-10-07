<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\UsersController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\UsersController
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
     * Test login method
     *
     * @return void
     * @uses \App\Controller\UsersController::login()
     */
    public function testLogin(): void
    {
        // test failed
        $this->post('https://localhost/users/login', [
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/login');
        $this->helperTestFormTag('/users/login');
        $this->helperTestAlert('Invalid email or password', 'danger');

        // login fail
        $this->post('https://localhost/users/login', [
            'email' => 'admin@example.com',
            'password' => 'notpassword',
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/login');
        $this->helperTestFormTag('/users/login');
        $this->helperTestAlert('Invalid email or password', 'danger');

        // test success
        $this->post('https://localhost/users/login', [
            'email' => 'admin@example.com',
            'password' => 'admin',
        ]);
        $this->assertRedirectEquals('https://localhost/');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');

        // test success redirect
        $this->post('https://localhost/users/login?redirect=%2Fcategories', [
            'email' => 'admin@example.com',
            'password' => 'admin',
        ]);
        $this->assertRedirectEquals('https://localhost/categories');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\UsersController::add()
     */
    public function testAdd(): void
    {
        $this->loginUserAdmin();

        // test failed
        $this->post('https://localhost/users/add', [
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/add');
        $this->helperTestFormTag('/users/add');
        $this->helperTestAlert('The user could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This field is required', 'name-error');
        $this->helperTestFormFieldError('This field is required', 'email-error');
        $this->helperTestFormFieldError('This field is required', 'password-error');

        // formatting fail
        $this->post('https://localhost/users/add', [
            'name' => 'New User',
            'email' => 'new user@example.com', // invalid email
            // missing password
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/add');
        $this->helperTestFormTag('/users/add');
        $this->helperTestAlert('The user could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('The provided value must be an e-mail address', 'email-error');
        $this->helperTestFormFieldError('This field is required', 'password-error');

        // existing email
        $this->post('https://localhost/users/add', [
            'name' => 'New User',
            'email' => 'regular@example.com', // invalid email
            'password' => 'password',
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/add');
        $this->helperTestFormTag('/users/add');
        $this->helperTestAlert('The user could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This Email already exists.', 'email-error');

        // test success
        $this->post('https://localhost/users/add', [
            'name' => 'New User',
            'email' => 'newuser@example.com', // invalid email
            'password' => 'password',
        ]);
        $this->assertRedirectEquals('https://localhost/users/view/4');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\UsersController::edit()
     */
    public function testEdit(): void
    {
        $this->loginUserAdmin();

        // test fail
        $this->put('https://localhost/users/edit/3', [
            'name' => 'Regular', // an existing record, can have same name,
            'email' => 'regular@example.com', // existing record, should be unique
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/edit');
        $this->helperTestFormTag('/users/edit/3', 'put');
        $this->helperTestAlert('The user could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This Email already exists.', 'email-error');
        // test put success
        $this->put('https://localhost/users/edit/3', [
            'name' => 'New User',
            'description' => 'The Description',
        ]);
        $this->assertRedirectEquals('https://localhost/users/view/3');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
