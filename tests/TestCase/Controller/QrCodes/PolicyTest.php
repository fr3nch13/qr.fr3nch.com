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
    public function testDontexist(): void
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

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/qr-codes/dontexist');
        Configure::write('debug', true);
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/index');
        // make sure only active ones are listed.
        $this->helperTestObjectComment(3, 'QrCodes/active');
        $this->helperTestObjectComment(0, 'QrCodes/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(3, 'QrCode/show');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(3, 'QrImages/active/first');
        // make sure the primary inactive one isn't listed.
        $content = (string)$this->_response->getBody();
        $this->assertSame(0, substr_count($content, '<img class="product-qrimage" src="/qr-images/show/3'));

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/index');
        // make sure all are listed.
        $this->helperTestObjectComment(3, 'QrCodes/active');
        $this->helperTestObjectComment(1, 'QrCodes/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(4, 'QrCode/show');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(3, 'QrImages/active/first');
        // make sure the primary inactive one isn't listed.
        $content = (string)$this->_response->getBody();
        $this->assertSame(0, substr_count($content, '<img class="product-qrimage" src="/qr-images/show/3'));

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/index');
        $content = (string)$this->_response->getBody();
        // make sure all are listed.
        // this may change to include all active, and inactive, scoped to the user.
        $this->helperTestObjectComment(3, 'QrCodes/active');
        $this->helperTestObjectComment(2, 'QrCodes/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(5, 'QrCode/show');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(3, 'QrImages/active/first');
        // make sure the primary inactive image isn't listed.
        $content = (string)$this->_response->getBody();
        $this->assertSame(0, substr_count($content, '<img class="product-qrimage" src="/qr-images/show/3'));

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/index');
        Configure::write('debug', true);
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::view()
     */
    public function testView(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/view');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/view');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/view/1');
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

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }
}
