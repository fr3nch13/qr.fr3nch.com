<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\QrImagesController Test Case
 *
 * Tests that the proper http method is being used.
 *
 * @uses \App\Controller\Admin\QrImagesController
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
     * Test qr-code method
     *
     * @uses \App\Controller\Admin\QrImagesController::qrCode()
     * @return void
     */
    public function testQrCode(): void
    {
        // get
        $this->get('https://localhost/admin/qr-images/qr-code/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/qr_code');

        // post
        $this->post('https://localhost/admin/qr-images/qr-code/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin/qr-images/qr-code/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/qr-images/qr-code/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/qr-images/qr-code/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::add()
     */
    public function testAdd(): void
    {
        // test get
        $this->get('https://localhost/admin/qr-images/add/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/add');

        // post
        $this->post('https://localhost/admin/qr-images/add/1', [
        ]);
        $this->assertResponseOk();
        $this->helperTestAlert('The images could not be saved. Please, try again.', 'danger');
        $this->helperTestTemplate('Admin/QrImages/add');

        // patch
        $this->patch('https://localhost/admin/qr-images/add/1', [
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/qr-images/add/1', [
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/qr-images/add/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::edit()
     */
    public function testEdit(): void
    {
        // test get
        $this->get('https://localhost/admin/qr-images/edit/2');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/edit');

        // post
        $this->post('https://localhost/admin/qr-images/edit/2', [
            'name' => 'Edited QrImage',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin/qr-images/edit/2', [
            'name' => 'Edited QrImage',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/qr-images/edit/1', [
            'name' => 'Edited QrImage',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/qr-images/qr-code/1');
        $this->assertFlashMessage('The image has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // delete
        $this->delete('https://localhost/admin/qr-images/edit/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::delete()
     */
    public function testDelete(): void
    {
        // test get
        $this->get('https://localhost/admin/qr-images/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // post
        $this->post('https://localhost/admin/qr-images/delete/1');
        $this->assertRedirectEquals('https://localhost/admin/qr-images/qr-code/1');
        $this->assertFlashMessage('The image `Front Cover` for `Sow & Scribe` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('https://localhost/admin/qr-images/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/qr-images/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/qr-images/delete/2');
        $this->assertRedirectEquals('https://localhost/admin/qr-images/qr-code/1');
        $this->assertFlashMessage('The image `Open Pages` for `Sow & Scribe` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
