<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Sources;

use App\Test\TestCase\Controller\LoggedInTrait;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\SourcesController Test Case
 *
 * @uses \App\Controller\SourcesController
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
     * Test add method
     *
     * @return void
     * @uses \App\Controller\SourcesController::add()
     */
    public function testAdd(): void
    {
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

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

        // formatting fail
        $this->post('/sources/add', [
            'key' => 'new source',
            'name' => 'new name',
            'description' => 'description',
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The source could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="sources form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/sources/add">');
        $this->assertResponseContains('<legend>Add Source</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('<div class="error-message" id="key-error">Value cannot have a space in it.</div>');

        // test success
        $this->post('/sources/add', [
            'key' => 'newsource',
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
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

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
