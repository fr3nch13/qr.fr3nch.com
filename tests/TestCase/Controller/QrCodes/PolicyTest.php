<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrCodesController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\QrCodesController
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
        $this->get('https://localhost/qr-codes/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\QrCodesController::dontexist()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\QrCodesController::dontexist()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\QrCodesController::dontexist()`');
    }

    /**
     * Test missing action
     *
     * @alert Keep the https://localhost/ as the HttpsEnforcerMiddleware will try to redirect.
     * @return void
     */
    public function testDontexistDebugOff(): void
    {
        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/qr-codes/dontexist');
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::index()
     */
    public function testIndexDebugOn(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/index');
        // make sure only active are listed.
        $this->helperTestObjectComment(3, 'QrCode/active');
        $this->helperTestObjectComment(0, 'QrCode/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(3, 'QrCode/view');
        $this->helperTestObjectComment(3, 'QrCode/forward');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(3, 'QrImage/active/first');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/index');
        // make sure only active are listed.
        $this->helperTestObjectComment(3, 'QrCode/active');
        $this->helperTestObjectComment(0, 'QrCode/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(3, 'QrCode/view');
        $this->helperTestObjectComment(3, 'QrCode/forward');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(3, 'QrImage/active/first');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/index');
        // make sure only active are listed.
        $this->helperTestObjectComment(3, 'QrCode/active');
        $this->helperTestObjectComment(0, 'QrCode/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(3, 'QrCode/view');
        $this->helperTestObjectComment(3, 'QrCode/forward');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(3, 'QrImage/active/first');
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::index()
     */
    public function testIndexDebugOff(): void
    {
        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/index');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::view()
     */
    public function testViewDebugOn(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/view');

        // inactive
        $this->get('https://localhost/qr-codes/view/4');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-codes%2Fview%2F4');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/view');

        // inactive
        $this->get('https://localhost/qr-codes/view/4');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/view');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/view');

        // inactive
        $this->get('https://localhost/qr-codes/view/4');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/view');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::view()
     */
    public function testViewDebugOff(): void
    {
        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }
}
