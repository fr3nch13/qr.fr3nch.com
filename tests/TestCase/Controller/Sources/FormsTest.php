<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Sources;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\SourcesController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\SourcesController
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
     * @uses \App\Controller\SourcesController::add()
     */
    public function testAdd(): void
    {
        // test failed
        $this->post('/sources/add', [
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The source could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="sources form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/sources/add">');
        $this->assertResponseContains('<legend>Add Source</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('id="name-error"');
        $this->assertResponseContains('id="description-error"');

        // existing fail
        $this->post('/sources/add', [
            'name' => 'Etsy',
            'description' => 'description',
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The source could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="sources form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/sources/add">');
        $this->assertResponseContains('<legend>Add Source</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('<div class="error-message" id="name-error">This Name already exists.</div>');

        // test success
        $this->post('/sources/add', [
            'name' => 'new name',
            'description' => 'description',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/sources');
        $this->assertFlashMessage('The source has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\SourcesController::edit()
     */
    public function testEdit(): void
    {
        // test fail
        $this->patch('/sources/edit/1', [
            'name' => 'Etsy', // an existing record
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The source could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="sources form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/sources/edit/1">');
        $this->assertResponseContains('<legend>Edit Source</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('id="name-error"');

        // test success
        $this->patch('/sources/edit/1', [
            'name' => 'New Source',
            'description' => 'The Description',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/sources');
        $this->assertFlashMessage('The source has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
