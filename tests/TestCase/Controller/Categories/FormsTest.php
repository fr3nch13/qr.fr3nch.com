<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Categories;

use App\Test\TestCase\Controller\LoggedInTrait;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\CategoriesController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\CategoriesController
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
        'app.Sources',
        'app.Categories',
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
     * @uses \App\Controller\CategoriesController::add()
     */
    public function testAdd(): void
    {
        // test failed
        $this->post('/categories/add', [
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The category could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="categories form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/categories/add">');
        $this->assertResponseContains('<legend>Add Category</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('id="name-error"');
        $this->assertResponseContains('id="description-error"');

        // test success
        $this->post('/categories/add', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/categories');
        $this->assertFlashMessage('The category has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::edit()
     */
    public function testEdit(): void
    {
        // test fail
        $this->patch('/categories/edit/1', [
            'name' => 'Journals', // an existing record
            'parent_id' => 4, // this doesn't exist
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The category could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="categories form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/categories/edit/1">');
        $this->assertResponseContains('<legend>Edit Category</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('id="name-error"');
        // @todo Figure out why this is allowed to pass.
        //$this->assertResponseContains('id="parent-id-error"');

        // test success
        $this->patch('/categories/edit/1', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/categories');
        $this->assertFlashMessage('The category has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
