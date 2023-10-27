<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrCodesController Test Case
 *
 * Test that the Json output is correct.
 * Specifically that fields are there that should be,
 * and not there that shouldn't be,
 *
 * @uses \App\Controller\QrCodesController
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
     * @uses \App\Controller\QrCodesController::index()
     */
    public function testIndex(): void
    {
        $this->get('https://localhost/qr-codes.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['qrCodes']));
        // only active codes
        $this->assertCount(3, $content['qrCodes']);

        $first = reset($content['qrCodes']);
        $this->assertSame(3, $first['id']);
        $this->assertTrue(isset($first['qr_images']));
        $this->assertFalse(isset($first['user_id']));
        $this->assertFalse(isset($first['user']));
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::view()
     */
    public function testView(): void
    {
        $this->get('https://localhost/qr-codes/view/1.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['qrCode']));
        $this->assertSame(1, $content['qrCode']['id']);
        $this->assertTrue(isset($content['qrCode']['tags']));
        $this->assertTrue(isset($content['qrCode']['source']));
        $this->assertTrue(isset($content['qrCode']['qr_images']));
        $this->assertFalse(isset($content['qrCode']['user_id']));
        $this->assertFalse(isset($content['qrCode']['user']));
    }
}
