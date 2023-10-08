<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrCodesController Test Case
 *
 * Tests the other aspects of the controller
 *
 * @uses \App\Controller\QrCodesController
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
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::forward()
     */
    public function testForward(): void
    {
        $this->get('https://localhost/?k=sownscribe');
        $this->assertRedirectEquals('https://localhost/f/sownscribe');

        $this->get('https://localhost/f/sownscribe');
        $this->assertRedirectEquals('https://amazon.com/path/to/details/page');

        $this->get('https://localhost/qr-codes/forward/sownscribe');
        $this->assertRedirectEquals('https://amazon.com/path/to/details/page');

        $this->get('https://localhost/?k=inactive');
        $this->assertRedirectEquals('https://localhost/f/inactive');

        $this->get('https://localhost/f/inactive');
        $this->assertRedirectEquals('https://localhost/');
        $this->assertFlashMessage('This QR Code is inactive.', 'flash');
        $this->assertFlashElement('flash/warning');

        $this->get('https://localhost/?k=dontexist');
        $this->assertRedirectEquals('https://localhost/f/dontexist');

        $this->get('https://localhost/f/dontexist');
        $this->assertRedirectEquals('https://localhost/');
        $this->assertFlashMessage('A QR Code with the key: `dontexist` could not be found.', 'flash');
        $this->assertFlashElement('flash/error');

        $this->get('https://localhost/f/');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShow(): void
    {
        $this->get('https://localhost/qr-codes/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertSame('image/png', $headers['Content-Type'][0]);
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
        $this->get('https://localhost/qr-codes/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertTrue(isset($headers['Cache-Control']));
        $this->assertSame('public, max-age=3600', $headers['Cache-Control'][0]);
        $this->assertTrue(isset($headers['Last-Modified']));
        $this->assertSame('image/png', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
        Configure::write('debug', true);
    }
}
