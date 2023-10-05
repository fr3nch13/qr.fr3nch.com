<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;
use Cake\Routing\Router;

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
     *
     * @return void
     * @uses \App\Controller\QrCodesController::index()
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
        $content = (string)$this->_response->getBody();
        // make sure only active ones are listed.
        $this->assertSame(3, substr_count($content, '<!-- objectComment: QrCodes.active -->'));
        $this->assertSame(0, substr_count($content, '<!-- objectComment: QrCodes.inactive -->'));
        // make sure the qcode is listed for each one.
        $this->assertSame(3, substr_count($content, '<!-- objectComment: QrCode.show -->'));
        // make sure only active primary images are listed.
        $this->assertSame(3, substr_count($content, '<!-- objectComment: QrImages.active.first -->'));
        // make sure the primary inactive one isn't listed.
        $this->assertSame(0, substr_count($content, '<img class="product-qrimage" src="/qr-images/show/3'));

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/index');
        $content = (string)$this->_response->getBody();
        // make sure all are listed.
        // this may change to include all active, and inactive, scoped to the user.
        $this->assertSame(3, substr_count($content, '<!-- objectComment: QrCodes.active -->'));
        $this->assertSame(1, substr_count($content, '<!-- objectComment: QrCodes.inactive -->'));
        // make sure the qcode is listed for each one.
        $this->assertSame(4, substr_count($content, '<!-- objectComment: QrCode.show -->'));
        // make sure only active primary images are listed.
        $this->assertSame(3, substr_count($content, '<!-- objectComment: QrImages.active.first -->'));
        // make sure the primary inactive one isn't listed.
        $this->assertSame(0, substr_count($content, '<img class="product-qrimage" src="/qr-images/show/3'));

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/index');
        $content = (string)$this->_response->getBody();
        // make sure all are listed.
        // this may change to include all active, and inactive, scoped to the user.
        $this->assertSame(3, substr_count($content, '<!-- objectComment: QrCodes.active -->'));
        $this->assertSame(1, substr_count($content, '<!-- objectComment: QrCodes.inactive -->'));
        // make sure the qcode is listed for each one.
        $this->assertSame(4, substr_count($content, '<!-- objectComment: QrCode.show -->'));
        // make sure only active primary images are listed.
        $this->assertSame(3, substr_count($content, '<!-- objectComment: QrImages.active.first -->'));
        // make sure the primary inactive one isn't listed.
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

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::add()
     */
    public function testAdd(): void
    {
        $this->enableSecurityToken();

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/qr-codes/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-codes%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/add');
        $this->helperTestFormTag('/qr-codes/add', 'post');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/add');
        $this->helperTestFormTag('/qr-codes/add', 'post');

        // Debug Off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->get('https://localhost/qr-codes/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-codes%2Fadd');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        Configure::write('debug', true);
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::edit()
     */
    public function testEdit(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->get('https://localhost/qr-codes/edit/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-codes%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/edit/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fqr-codes%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/edit');
        $this->helperTestFormTag('/qr-codes/edit/1', 'patch');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/qr-codes/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::delete()
     */
    public function testDelete(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->delete('https://localhost/qr-codes/delete/3');
        $this->assertRedirectEquals('https://localhost/users/login');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->enableRetainFlashMessages();
        $this->loginUserRegular();
        $this->delete('https://localhost/qr-codes/delete/2');
        $this->assertRedirectEquals('https://localhost/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->delete('https://localhost/qr-codes/delete/3');
        // qr-codes/index is the homepage.
        $this->assertRedirectEquals('https://localhost/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The qr code `American Flag Charm` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin
        $this->loginUserAdmin();
        // not 3 as it was deleted above.
        $this->delete('https://localhost/qr-codes/delete/2');
        // qr-codes/index is the homepage.
        $this->assertRedirectEquals('https://localhost/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The qr code `The Witching Hour` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->delete('https://localhost/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->delete('https://localhost/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin, debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->delete('https://localhost/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);

        // not logged in, debug off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->delete('https://localhost/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }
}
