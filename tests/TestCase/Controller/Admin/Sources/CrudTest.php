<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Sources;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\SourcesController Test Case
 *
 * Tests that the proper http method is being used.
 *
 * @uses \App\Controller\Admin\SourcesController
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
     * @uses \App\Controller\Admin\SourcesController::index()
     */
    public function testIndex(): void
    {
        // get
        $this->get('https://localhost/admin/sources');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Sources/index');

        // post
        $this->post('https://localhost/admin/sources');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin/sources');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/sources');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/sources');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::add()
     */
    public function testAdd(): void
    {
        // test get
        $this->get('https://localhost/admin/sources/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Sources/add');

        // post
        $this->post('https://localhost/admin/sources/add', [
            'name' => 'new name',
            'description' => 'description',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/sources');
        $this->assertFlashMessage('The source has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('https://localhost/admin/sources/add', [
            'name' => 'new name',
            'description' => 'description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/sources/add', [
            'name' => 'new name',
            'description' => 'description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/sources/add');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::edit()
     */
    public function testEdit(): void
    {
        // test get
        $this->get('https://localhost/admin/sources/edit/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/Sources/edit');

        // post
        $this->post('https://localhost/admin/sources/edit/3', [
            'name' => 'New Source',
            'description' => 'The Description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin/sources/edit/3', [
            'name' => 'New Source',
            'description' => 'The Description',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/sources/edit/3', [
            'name' => 'New Source',
            'description' => 'The Description',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/sources');
        $this->assertFlashMessage('The source has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // delete
        $this->delete('https://localhost/admin/sources/edit/3');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::delete()
     */
    public function testDelete(): void
    {
        // test get
        $this->get('https://localhost/admin/sources/delete/1');
        // allow get, as the delete button is loaded via ajax into a modal.
        $this->assertRedirectEquals('https://localhost/admin/sources');
        $this->assertFlashMessage('The source `Amazon` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // post
        $this->post('https://localhost/admin/sources/delete/2');
        // allow get, as the delete button is loaded via ajax into a modal.
        $this->assertRedirectEquals('https://localhost/admin/sources');
        $this->assertFlashMessage('The source `Etsy` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('https://localhost/admin/sources/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/sources/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/sources/delete/3');
        $this->assertRedirectEquals('https://localhost/admin/sources');
        $this->assertFlashMessage('The source `Delete Me` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
