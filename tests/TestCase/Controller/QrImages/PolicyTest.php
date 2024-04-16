<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrImagesController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\QrImagesController
 */
class PolicyTest extends BaseControllerTest
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
    }

    /**
     * Test missing action
     *
     * @alert Keep the https://localhost/ as the HttpsEnforcerMiddleware will try to redirect.
     * @return void
     */
    public function testDontexistDebugOn(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/qr-images/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\QrImagesController::dontexist()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\QrImagesController::dontexist()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\QrImagesController::dontexist()`');
    }

    /**
     * Test missing action
     *
     * @alert Keep the https://localhost/ as the HttpsEnforcerMiddleware will try to redirect.
     * @return void
     */
    public function testDontexistOff(): void
    {
        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/qr-images/dontexist');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::show()
     */
    public function testShowDebugOn(): void
    {
        // not logged in, active image
        $this->loginGuest();
        $this->get('https://localhost/qr-images/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // not logged in, inactive image
        $this->get('https://localhost/qr-images/show/7');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-images%2Fshow%2F7');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // not logged in, missing image, debug on
        $this->loginGuest();
        $this->get('https://localhost/qr-images/show/999');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Record not found in table `qr_images`.');

        // test with admin, active image
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // test with admin, inactive image, not owner
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/show/7');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // test with reqular, active image
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // test with reqular, inactive image, owner
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/show/7');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fqr-images%2Fshow%2F7');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, inactive image, not owner
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/show/3');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fqr-images%2Fshow%2F3');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with missing id and debug
        $this->loginGuest();
        $this->get('https://localhost/qr-images/show');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/show');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::show()
     */
    public function testShowDebugOff(): void
    {
        // not logged in, missing image, debug off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->get('https://localhost/qr-images/show/999');
        $this->assertResponseCode(404);
        $this->helperTestError400('/qr-images/show/999');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/show');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }
}
