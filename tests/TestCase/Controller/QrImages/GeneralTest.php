<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrImagesController Test Case
 *
 * Tests the other aspects of the controller
 *
 * @uses \App\Controller\QrImagesController
 */
class GeneralTest extends BaseControllerTest
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
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShow(): void
    {
        $this->get('https://localhost/qr-images/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShowHeadersNoDebug(): void
    {
        // check cache policy when debug is off.
        Configure::write('debug', false);
        $this->get('https://localhost/qr-images/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertTrue(isset($headers['Cache-Control']));
        $this->assertSame('public, max-age=3600', $headers['Cache-Control'][0]);
        $this->assertTrue(isset($headers['Last-Modified']));
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
        Configure::write('debug', true);
    }
}
