<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Tags;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\TagsController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\TagsController
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
     * @uses \App\Controller\TagsController::add()
     */
    public function testAdd(): void
    {
        // test failed
        $this->post('https://localhost/tags/add', [
        ]);
        $this->assertResponseOk();
        $this->helperTestAlert('The tag could not be saved. Please, try again.', 'danger');
        $this->assertResponseContains('<div class="tags form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/tags/add">');
        $this->assertResponseContains('<legend>Add Tag</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('id="name-error"');

        // test success
        $this->post('https://localhost/tags/add', [
            'name' => 'new tag',
        ]);
        $this->assertRedirectEquals('https://localhost/tags');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\TagsController::edit()
     */
    public function testEdit(): void
    {
        // test fail
        $this->patch('https://localhost/tags/edit/1', [
            'name' => 'Amazon', // an existing record
        ]);
        $this->assertResponseOk();
        $this->helperTestAlert('The tag could not be saved. Please, try again.', 'danger');
        $this->assertResponseContains('<div class="tags form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" role="form" action="/tags/edit/1">');
        $this->assertResponseContains('<legend>Edit Tag</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('id="name-error"');

        // test success
        $this->patch('https://localhost/tags/edit/1', [
            'name' => 'New Tag',
            'description' => 'The Description',
        ]);
        $this->assertRedirectEquals('https://localhost/tags');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
