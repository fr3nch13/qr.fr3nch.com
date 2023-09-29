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
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes index content">');
        $this->assertResponseContains('<h3>QR Codes</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes index content">');
        $this->assertResponseContains('<h3>QR Codes</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/qr-codes');
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
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes view content">');
        $this->assertResponseContains('<h3>Sow &amp; Scribe</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes view content">');
        $this->assertResponseContains('<h3>Sow &amp; Scribe</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes view content">');
        $this->assertResponseContains('<h3>Sow &amp; Scribe</h3>');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get('/qr-codes/view');
        $this->assertResponseCode(500);
        // TODO: This should apply a check `/qr-codes/view`
        // Should also throw a 404, instead of a 500
        // labels: policy, response code
        // milestone: 1
        $this->assertResponseContains('The request to `/qr-codes/view` did not apply any authorization checks.');
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
        $this->get('/qr-codes/add');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fqr-codes%2Fadd');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/qr-codes/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/qr-codes/add">');
        $this->assertResponseContains('<legend>Add QR Code</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/qr-codes/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/qr-codes/add">');
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
        $this->get('/qr-codes/edit');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fqr-codes%2Fedit');

        // test with missing id and debug
        $this->loginUserAdmin();
        $this->get('/qr-codes/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('/qr-codes/edit');
        $this->assertResponseCode(500);
        // TODO: This should apply a check `/qr-codes/edit`
        // Should also throw a 404, instead of a 500
        // labels: policy, response code
        // milestone: 1
        $this->assertResponseContains('The request to `/qr-codes/edit` did not apply any authorization checks.');
        Configure::write('debug', true); // turn it back on

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/qr-codes/edit/1">');
        $this->assertResponseContains('<legend>Edit QR Code</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/qr-codes/edit/1');
        $this->assertRedirectContains('/?redirect=%2Fqr-codes%2Fedit%2F1');
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

        // not logged in, so should redirect
        $this->get('/qr-codes/delete');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fqr-codes%2Fdelete');

        // test get with missing id and debug
        $this->loginUserAdmin();
        $this->get('/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test get with reqular, get
        $this->loginUserRegular();
        $this->get('/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test post with regular, post
        $this->loginUserRegular();
        $this->post('/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test delete with regular user
        $this->loginUserRegular();
        $this->delete('/qr-codes/delete/1');
        $this->assertRedirectContains('/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with admin, get
        $this->loginUserAdmin();
        $this->post('/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test with admin, delete, no ID
        $this->loginUserAdmin();
        $this->delete('/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin, post no data, no CSRF
        $this->loginUserAdmin();
        $this->delete('/qr-codes/delete/1');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/');
        $this->assertFlashMessage('The qr code `Sow & Scribe` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
