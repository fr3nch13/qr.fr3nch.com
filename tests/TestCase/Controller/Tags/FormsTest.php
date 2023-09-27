<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Tags;

use App\Test\TestCase\Controller\LoggedInTrait;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\TagsController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\TagsController
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
        $this->post('/tags/add', [
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The tag could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="tags form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/tags/add">');
        $this->assertResponseContains('<legend>Add Tag</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('id="name-error"');

        // test success
        $this->post('/tags/add', [
            'name' => 'new tag',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/tags');
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
        $this->patch('/tags/edit/1', [
            'name' => 'Amazon', // an existing record
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The tag could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="tags form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/tags/edit/1">');
        $this->assertResponseContains('<legend>Edit Tag</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('id="name-error"');

        // test success
        $this->patch('/tags/edit/1', [
            'name' => 'New Tag',
            'description' => 'The Description',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/tags');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
