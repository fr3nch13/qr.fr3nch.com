<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Tags;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\TagsController Test Case
 *
 * Tests that the proper http method is being used.
 *
 * @uses \App\Controller\Admin\TagsController
 */
class CrudTest extends BaseControllerTest
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
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::index()
     */
    public function testIndex(): void
    {
        // get
        $this->get('https://localhost/admin/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Tags/index');

        // post
        $this->post('https://localhost/admin/tags');
        $this->assertRedirectEquals('https://localhost/admin/tags');
        // changed because we added friendsofcake/search
        // which does a Post-Redirect-Get
        // $this->assertResponseCode(405);
        // $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin/tags');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/tags');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/tags');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::add()
     */
    public function testAdd(): void
    {
        // test get
        $this->get('https://localhost/admin/tags/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Tags/add');

        // post
        $this->post('https://localhost/admin/tags/add', [
            'name' => 'New Tag',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/tags');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('https://localhost/admin/tags/add', [
            'name' => 'New Tag',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/tags/add', [
            'name' => 'New Tag',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/tags/add');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::edit()
     */
    public function testEdit(): void
    {
        // test get
        $this->get('https://localhost/admin/tags/edit/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Tags/edit');

        // post
        $this->post('https://localhost/admin/tags/edit/1', [
            'name' => 'Updated Tag',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin/tags/edit/1', [
            'name' => 'Updated Tag',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/tags/edit/1', [
            'name' => 'Updated Tag',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/tags');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // delete
        $this->delete('https://localhost/admin/tags/edit/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::delete()
     */
    public function testDelete(): void
    {
        // test get
        $this->get('https://localhost/admin/tags/delete/1');
        // only one to all a get, as the delete button is loaded via ajax into a modal.
        $this->assertFlashMessage('The tag `Notebook` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
        $this->assertRedirectEquals('https://localhost/admin/tags');

        // post
        $this->post('https://localhost/admin/tags/delete/2');
        $this->assertFlashMessage('The tag `Journal` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
        $this->assertRedirectEquals('https://localhost/admin/tags');

        // patch
        $this->patch('https://localhost/admin/tags/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/tags/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/tags/delete/3');
        $this->assertFlashMessage('The tag `Delete Me` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
        $this->assertRedirectEquals('https://localhost/admin/tags');
    }
}
