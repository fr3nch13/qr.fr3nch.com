<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrImagesController Test Case
 *
 * Tests that the proper http method is being used.
 *
 * @uses \App\Controller\QrImagesController
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
     * @uses \App\Controller\QrImagesController::index()
     */
    public function testIndex(): void
    {
        // get
        $this->get('https://localhost/qr-images');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrImages index content">');
        $this->assertResponseContains('<h3>QR Codes</h3>');

        // post
        $this->post('https://localhost/qr-images');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/qr-images');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/qr-images');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/qr-images');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::view()
     */
    public function testView(): void
    {
        // test get
        $this->get('https://localhost/qr-images/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrImages view content">');
        $this->assertResponseContains('<h3>Sow &amp; Scribe</h3>');

        // post
        $this->post('https://localhost/qr-images/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/qr-images/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/qr-images/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/qr-images/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::add()
     */
    public function testAdd(): void
    {
        // test get
        $this->get('https://localhost/qr-images/add/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrImages form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/qr-images/add">');
        $this->assertResponseContains('<legend>Add QR Code</legend>');

        // post
        $this->post('https://localhost/qr-images/add/1', [
            'name' => 'New QrImage',
            'qr_code_id' => 1,
        ]);
        $this->assertRedirectEquals('https://localhost/qr-images/qr-code/1');
        $this->assertFlashMessage('The image has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('https://localhost/qr-images/add/1', [
            'name' => 'New QrImage',
            'qr_code_id' => 1,
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/qr-images/add/1', [
            'name' => 'New QrImage',
            'qr_code_id' => 1,
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/qr-images/add/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::edit()
     */
    public function testEdit(): void
    {
        // test get
        $this->get('https://localhost/qr-images/edit/2');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrImages form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" role="form" action="/qr-images/edit/1">');
        $this->assertResponseContains('<legend>Edit QR Code</legend>');

        // post
        $this->post('https://localhost/qr-images/edit/2', [
            'name' => 'Edited QrImage',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/qr-images/edit/2', [
            'name' => 'Edited QrImage',
        ]);
        $this->assertRedirectEquals('https://localhost/qr-images/qr-code/1');
        $this->assertFlashMessage('The image has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // put
        $this->put('https://localhost/qr-images/edit/1', [
            'name' => 'Edited QrImage',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/qr-images/edit/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::delete()
     */
    public function testDelete(): void
    {
        // test get
        $this->get('https://localhost/qr-images/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // post
        $this->post('https://localhost/qr-images/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/qr-images/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/qr-images/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/qr-images/delete/1');
        $this->assertRedirectEquals('https://localhost/qr-images/qr-code/1');
        $this->assertFlashMessage('The image `Front Cover` for `Sow & Scribe` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
