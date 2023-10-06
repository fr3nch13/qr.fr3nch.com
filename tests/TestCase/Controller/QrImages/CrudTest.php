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
     * Test qr-code method
     *
     * @uses \App\Controller\QrImagesController::qrCode()
     * @return void
     */
    public function testQrCode(): void
    {
        // get
        $this->get('https://localhost/qr-images/qr-code/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrImages/qr_code');

        // post
        $this->post('https://localhost/qr-images/qr-code/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/qr-images/qr-code/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/qr-images/qr-code/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/qr-images/qr-code/1');
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
        $this->helperTestTemplate('QrImages/add');

        // post
        $this->post('https://localhost/qr-images/add/1', [
            'name' => 'New QrImage',
            'ext' => 'jpg',
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
        $this->helperTestTemplate('QrImages/edit');

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
