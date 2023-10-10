<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\QrCodesController Test Case
 *
 * Tests the other aspects of the controller
 *
 * @uses \App\Controller\Admin\QrCodesController
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
        $this->loginUserAdmin();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::forward()
     */
    public function testForward(): void
    {
        $this->get('https://localhost/admin/f/sownscribe');
        $this->assertRedirectEquals('https://amazon.com/path/to/details/page');

        $this->get('https://localhost/admin/qr-codes/forward/sownscribe');
        $this->assertRedirectEquals('https://amazon.com/path/to/details/page');

        $this->get('https://localhost/admin/f/inactive');
        $this->assertRedirectEquals('https://google.com');

        $this->get('https://localhost/admin/qr-codes/forward/inactive');
        $this->assertRedirectEquals('https://google.com');

        $this->get('https://localhost/admin/f/dontexist');
        $this->assertRedirectEquals('https://localhost/admin/qr-codes');
        $this->assertFlashMessage('A QR Code with the key: `dontexist` could not be found.', 'flash');
        $this->assertFlashElement('flash/error');

        $this->get('https://localhost/admin/f/');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::show()
     */
    public function testShow(): void
    {
        $this->get('https://localhost/admin/qr-codes/show/1');
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
     * @uses \App\Controller\Admin\QrCodesController::show()
     */
    public function testShowHeadersNoDebug(): void
    {
        // check cache policy when debug is off.
        Configure::write('debug', false);
        $this->get('https://localhost/admin/qr-codes/show/1');
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
