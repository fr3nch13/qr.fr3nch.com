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
     * @uses \App\Controller\QrCodesController::index()
     */
    public function testForward(): void
    {
        $this->get('/?k=sownscribe');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/qr-codes/forward/sownscribe');

        $this->get('/qr-codes/forward/sownscribe');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('https://amazon.com/path/to/details/page');

        $this->get('/?k=dontexist');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/qr-codes/forward/dontexist');

        $this->get('/qr-codes/forward/dontexist');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/');
        $this->assertFlashMessage('A QR Code with the key: `dontexist` could not be found.', 'flash');
        $this->assertFlashElement('flash/error');
    }
}
