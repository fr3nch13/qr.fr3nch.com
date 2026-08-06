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
        // test success, inactive
        $this->logoutUser();
        $this->post('http://localhost:8080/users/login', [
            'email' => 'inactive@example.com',
            'password' => 'inactive',
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/login');
        $this->helperTestFormTag('/users/login');
        $this->helperTestAlert('Invalid email or password, or your account may be inactive.', 'danger');

        // test failed
        $this->post('http://localhost:8080/users/login', [
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/login');
        $this->helperTestFormTag('/users/login');
        $this->helperTestAlert('Invalid email or password, or your account may be inactive.', 'danger');

        // login fail
        $this->post('http://localhost:8080/users/login', [
            'email' => 'admin@example.com',
            'password' => 'notpassword',
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Users/login');
        $this->helperTestFormTag('/users/login');
        $this->helperTestAlert('Invalid email or password, or your account may be inactive.', 'danger');

        // test success, admin
        $this->post('http://localhost:8080/users/login', [
            'email' => 'admin@example.com',
            'password' => 'admin',
        ]);
        $this->assertRedirectEquals('/admin');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');

        // test success, admin redirect
        $this->post('http://localhost:8080/users/login?redirect=%2Ftags', [
            'email' => 'admin@example.com',
            'password' => 'admin',
        ]);
        $this->assertRedirectEquals('/tags');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');

        // test success, regular
        $this->post('http://localhost:8080/users/login', [
            'email' => 'regular@example.com',
            'password' => 'regular',
        ]);
        $this->assertRedirectEquals('/admin');
        $this->assertFlashMessage('Welcome back Regular', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
