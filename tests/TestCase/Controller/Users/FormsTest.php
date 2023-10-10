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
        $this->assertRedirectEquals('https://localhost/admin');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');

        // test success redirect
        $this->post('https://localhost/users/login?redirect=%2Ftags', [
            'email' => 'admin@example.com',
            'password' => 'admin',
        ]);
        $this->assertRedirectEquals('https://localhost/tags');
        $this->assertFlashMessage('Welcome back Admin', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
