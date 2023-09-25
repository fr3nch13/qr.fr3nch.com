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
 * @uses \App\Controller\TagsController
 */
class CrudTest extends TestCase
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
     * Test index method
     *
     * @return void
     * @uses \App\Controller\TagsController::index()
     */
    public function testIndex(): void
    {
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // get
        $this->get('/tags');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="tags index content">');
        $this->assertResponseContains('<h3>Tags</h3>');

        // post
        $this->post('/tags');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/tags');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/tags');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/tags');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\TagsController::view()
     */
    public function testView(): void
    {
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/tags/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="tags view content">');
        $this->assertResponseContains('<h3>Notebook</h3>');

        // post
        $this->post('/tags/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/tags/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/tags/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/tags/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\TagsController::add()
     */
    public function testAdd(): void
    {
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/tags/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="tags form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/tags/add">');
        $this->assertResponseContains('<legend>Add Tag</legend>');

        // post
        $this->post('/tags/add', [
            'key' => 'newtag',
            'name' => 'new name',
            'description' => 'description',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/tags');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('/tags/add', [
            'key' => 'newtag',
            'name' => 'new name',
            'description' => 'description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/tags/add', [
            'key' => 'newtag',
            'name' => 'new name',
            'description' => 'description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/tags/add');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\TagsController::edit()
     */
    public function testEdit(): void
    {
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/tags/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="tags form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/tags/edit/1">');
        $this->assertResponseContains('<legend>Edit Tag</legend>');

        // post
        $this->post('/tags/edit/1', [
            'name' => 'New Tag',
            'description' => 'The Description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/tags/edit/1', [
            'name' => 'New Tag',
            'description' => 'The Description',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/tags');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // put
        $this->put('/tags/edit/1', [
            'name' => 'New Tag',
            'description' => 'The Description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/tags/edit/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\TagsController::delete()
     */
    public function testDelete(): void
    {
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/tags/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // post
        $this->post('/tags/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/tags/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/tags/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/tags/delete/1');
        $this->assertFlashMessage('The tag has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/tags');
    }
}
