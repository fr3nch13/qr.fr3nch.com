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
        $this->helperTestError400();
        $this->assertResponseContains('The requested address <strong>\'/qr-codes/dontexist\'</strong> was not found on this server.</p>');
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
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes index content">');
        $this->assertResponseContains('<h3>QR Codes</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes index content">');
        $this->assertResponseContains('<h3>QR Codes</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes index content">');
        $this->assertResponseContains('<h3>QR Codes</h3>');
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
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes view content">');
        $this->assertResponseContains('<h3>Sow &amp; Scribe</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes view content">');
        $this->assertResponseContains('<h3>Sow &amp; Scribe</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes view content">');
        $this->assertResponseContains('<h3>Sow &amp; Scribe</h3>');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->loginUserAdmin();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'QrCodes',
            'action' => 'view',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::add()
     */
    public function testAdd(): void
    {
        // not logged in, so should redirect
        $this->get('https://localhost/qr-codes/add');
        $this->assertRedirectEquals('users/login?redirect=%2Fqr-codes%2Fadd');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/qr-codes/add">');
        $this->assertResponseContains('<legend>Add QR Code</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/qr-codes/add">');
        $this->assertResponseContains('<legend>Add QR Code</legend>');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::edit()
     */
    public function testEdit(): void
    {
        // not logged in, so should redirect
        $this->get('https://localhost/qr-codes/edit');
        $this->assertRedirectEquals('users/login?redirect=%2Fqr-codes%2Fedit');

        // test with missing id and debug
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'QrCodes',
            'action' => 'edit',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" role="form" action="/qr-codes/edit/1">');
        $this->assertResponseContains('<legend>Edit QR Code</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/edit/1');
        $this->assertRedirectEquals('/?redirect=%2Fqr-codes%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::delete()
     */
    public function testDelete(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        // not logged in, missing id
        $this->get('https://localhost/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test get with missing id and debug
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on

        // test get with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test post with regular, post
        $this->loginUserRegular();
        $this->post('https://localhost/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test delete with regular user
        $this->loginUserRegular();
        $this->delete('https://localhost/qr-codes/delete/1');
        $this->assertRedirectEquals('/qr-codes');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with admin, get
        $this->loginUserAdmin();
        $this->post('https://localhost/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test with admin, post no data, no CSRF
        $this->loginUserAdmin();
        $this->delete('https://localhost/qr-codes/delete/1');
        $this->assertRedirectEquals('/qr-codes');
        $this->assertFlashMessage('The qr code `Sow & Scribe` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
