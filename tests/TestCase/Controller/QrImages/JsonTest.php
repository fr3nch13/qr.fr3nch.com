<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrImagesController Test Case
 *
 * Test that the Json output is correct.
 * Specifically that fields are there that should be,
 * and not there that shouldn't be,
 *
 * @uses \App\Controller\QrImagesController
 */
class JsonTest extends BaseControllerTest
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
        $this->requestAsJson();
        $this->loginUserAdmin();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::index()
     */
    public function testQrCode(): void
    {
        $this->get('https://localhost/qr-images/qr-code/1.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['qrCode']));
        $this->assertSame(1, $content['qrCode']['id']);
        $this->assertTrue(isset($content['qrImages']));
        $this->assertCount(2, $content['qrImages']);

        $first = reset($content['qrImages']);
        $this->assertSame(1, $first['id']);
        $this->assertFalse(isset($first['user_id']));
        $this->assertFalse(isset($first['user']));
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::add()
     */
    public function testAdd(): void
    {
        // a get
        $this->get('https://localhost/qr-images/add/1.json');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['qrCode']));
        $this->assertFalse(empty($content['qrCode']));
        $this->assertSame(1, $content['qrCode']['id']);

        $this->assertTrue(isset($content['qrImage']));
        $this->assertFalse(empty($content['qrImage']));
        $this->assertSame(1, $content['qrImage']['qr_code_id']);

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        // a post fail
        $this->post('https://localhost/qr-images/add/1.json', []);
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['qrCode']));
        $this->assertFalse(empty($content['qrCode']));
        $this->assertSame(1, $content['qrCode']['id']);

        $this->assertTrue(isset($content['qrImage']));
        $this->assertFalse(empty($content['qrImage']));
        $this->assertSame(1, $content['qrImage']['qr_code_id']);

        $this->assertTrue(isset($content['errors']));
        $this->assertFalse(empty($content['errors']));
        $expected = [
            'name' => [
                '_required' => 'This field is required',
            ],
        ];
        $this->assertSame($expected, $content['errors']);

        // a post success
        $this->post('https://localhost/qr-images/add/1.json', [
            'name' => 'New JSON QR Image',
            'ext' => 'jpg' // TODO: change this once we get file uploading working.
        ]);
        $this->assertRedirectEquals('https://localhost/qr-images/qr-code/1.json');
        $this->assertFlashMessage('The image has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::edit()
     */
    public function testEdit(): void
    {
        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/edit/1.json');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['qrImage']));
        $this->assertFalse(empty($content['qrImage']));
        $this->assertSame(1, $content['qrImage']['qr_code_id']);

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        // a patch success
        $this->patch('https://localhost/qr-images/edit/1.json', [
            'name' => 'New JSON QR Code',
        ]);
        $this->assertRedirectEquals('https://localhost/qr-images/qr-code/1.json');
        $this->assertFlashMessage('The image has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
