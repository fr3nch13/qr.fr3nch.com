<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Users;

use App\Test\TestCase\Controller\LoggedInTrait;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\UsersController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\UsersController
 */
class FormsTest extends TestCase
{
    use IntegrationTestTrait;

    use LoggedInTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
        'app.Categories',
        'app.Sources',
        'app.QrCodes',
        'app.CategoriesQrCodes',
        'app.Tags',
        'app.QrCodesTags',
    ];

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
        //$this->loginUserAdmin();
        // not logged in, that is tested in the PolicyTest

        // test failed
        $this->post('/users/login', [
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">Invalid email or password</div>');
        $this->assertResponseContains('<div class="users form">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/users/login">');
        $this->assertResponseContains('<legend>Please enter your email and password</legend>');

        // login fail
        $this->post('/users/login', [
            'email' => 'admin@example.com',
            'password' => 'notpassword',
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">Invalid email or password</div>');
        $this->assertResponseContains('<div class="users form">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/users/login">');
        $this->assertResponseContains('<legend>Please enter your email and password</legend>');

        // test success
        $this->post('/users/login', [
            'email' => 'admin@example.com',
            'password' => 'admin',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/qr-codes');
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
        $this->post('/users/add', [
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The user could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="users form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/users/add">');
        $this->assertResponseContains('<legend>Add User</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('<div class="error-message" id="name-error">This field is required</div>');
        $this->assertResponseContains('<div class="error-message" id="email-error">This field is required</div>');
        $this->assertResponseContains('<div class="error-message" id="password-error">This field is required</div>');

        // formatting fail
        $this->post('/users/add', [
            'name' => 'New User',
            'email' => 'new user@example.com', // invalid email
            // missing password
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The user could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="users form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/users/add">');
        $this->assertResponseContains('<legend>Add User</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('<div class="error-message" id="email-error">The provided value must be an e-mail address</div>');
        $this->assertResponseContains('<div class="error-message" id="password-error">This field is required</div>');

        // existing email
        $this->post('/users/add', [
            'name' => 'New User',
            'email' => 'regular@example.com', // invalid email
            'password' => 'password',
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The user could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="users form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/users/add">');
        $this->assertResponseContains('<legend>Add User</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('<div class="error-message" id="email-error">This Email already exists.</div>');

        // test success
        $this->post('/users/add', [
            'name' => 'New User',
            'email' => 'newuser@example.com', // invalid email
            'password' => 'password',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users');
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
        $this->patch('/users/edit/3', [
            'name' => 'Regular', // an existing record, can have same name,
            'email' => 'regular@example.com', // existing record, should be unique
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The user could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="users form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/users/edit/3">');
        $this->assertResponseContains('<legend>Edit User</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('<div class="error-message" id="email-error">This Email already exists.</div>');

        // test success
        $this->patch('/users/edit/3', [
            'name' => 'New User',
            'description' => 'The Description',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
