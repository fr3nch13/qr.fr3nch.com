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
        $this->enableCsrfToken();
        $this->enableSecurityToken();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::forward()
     */
    public function testForward(): void
    {
        $this->get('/?k=sownscribe');
        $this->assertRedirectContains('f/sownscribe');

        $this->get('/f/sownscribe');
        $this->assertRedirectContains('https://amazon.com/path/to/details/page');

        $this->get('/qr-codes/forward/sownscribe');
        $this->assertRedirectContains('https://amazon.com/path/to/details/page');

        $this->get('/?k=dontexist');
        $this->assertRedirectContains('f/dontexist');

        $this->get('/f/dontexist');
        $this->assertRedirectContains('/');
        $this->assertFlashMessage('A QR Code with the key: `dontexist` could not be found.', 'flash');
        $this->assertFlashElement('flash/error');

        $this->get('/f/');
        $this->assertRedirectContains('/');
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShow(): void
    {
        $this->get('/qr-codes/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders(); // @phpstan-ignore-line
        $this->assertSame('image/png', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
    }
}
