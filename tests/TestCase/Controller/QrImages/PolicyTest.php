<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;
use Cake\Routing\Router;

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
    }

    /**
     * Test missing action
     *
     * @alert Keep the https://localhost/ as the HttpsEnforcerMiddleware will try to redirect.
     *
     * @return void
     * @uses \App\Controller\QrImagesController::index()
     */
    public function testDontexist(): void
    {
        // not logged in
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

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/qr-images/dontexist');
        Configure::write('debug', true);
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::index() which doesn't exist
     */
    public function testIndex(): void
    {
        // not logged in
        $this->get('https://localhost/qr-images');
        $this->assertResponseCode(500);
        $this->assertResponseContains('Error: Method `canIndex` for invoking action `index` has not been defined in `App\Policy\QrImagesControllerPolicy`.');
        // debug turned off.
        Configure::write('debug', false);
        $this->get('https://localhost/qr-images');
        $this->assertResponseCode(500);
        $this->assertResponseContains('Error: Method `canIndex` for invoking action `index` has not been defined in `App\Policy\QrImagesControllerPolicy`.');
        Configure::write('debug', true);

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images');
        $this->assertResponseCode(500);
        $this->assertResponseContains('Error: Method `canIndex` for invoking action `index` has not been defined in `App\Policy\QrImagesControllerPolicy`.');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images');
        $this->assertResponseCode(500);
        $this->assertResponseContains('Error: Method `canIndex` for invoking action `index` has not been defined in `App\Policy\QrImagesControllerPolicy`.');
        // debug turned off.
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-images%2Fqr-code%2F1');
        // TODO: add a flash message for users not logged in.
        // labels: policy, flash
        //$this->assertFlashMessage('You are not authorized to access that location', 'flash');
        //$this->assertFlashElement('flash/error');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Method in QrImagesController');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-images');
        Configure::write('debug', true);

    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::qrCode()
     */
    public function testQrCode(): void
    {
        // not logged in
        $this->get('https://localhost/qr-images/qr-code/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-images%2Fqr-code%2F1');
        // TODO: add a flash message for users not logged in.
        // labels: policy, flash
        //$this->assertFlashMessage('You are not authorized to access that location', 'flash');
        //$this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/qr-code/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fqr-images%2Fqr-code%2F1');
        // TODO: Add a flash message when a policy check fails like this.
        // labels: policy, flash
        //$this->assertFlashMessage('You are not authorized to access that location', 'flash');
        //$this->assertFlashElement('flash/error');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/qr-code/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<!-- START: App.QrImages/qr_code -->');
        $this->assertResponseContains('<!-- END: App.QrImages/qr_code -->');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::show()
     */
    public function testShow(): void
    {
        // not logged in, active image
        $this->get('https://localhost/qr-images/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        //$headers = $this->_response->getHeaders(); // @phpstan-ignore-line
        //$this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        //$this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // not logged in, inactive image
        $this->get('https://localhost/qr-images/show/7');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders(); // @phpstan-ignore-line
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/show/7');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders(); // @phpstan-ignore-line
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders(); // @phpstan-ignore-line
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        $this->get('https://localhost/qr-images/show/7');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders(); // @phpstan-ignore-line
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/show');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'QrImages',
            'action' => 'show',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::add()
     */
    public function testAdd(): void
    {
        // not logged in, so should redirect
        $this->get('https://localhost/qr-images/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-images%2Fadd');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrImages form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/qr-images/add">');
        $this->assertResponseContains('<legend>Add QR Code</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrImages form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/qr-images/add">');
        $this->assertResponseContains('<legend>Add QR Code</legend>');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::edit()
     */
    public function testEdit(): void
    {
        // not logged in, so should redirect
        $this->get('https://localhost/qr-images/edit');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-images%2Fedit');

        // test with missing id and debug
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'QrImages',
            'action' => 'edit',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/edit/2');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrImages form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" role="form" action="/qr-images/edit/1">');
        $this->assertResponseContains('<legend>Edit QR Code</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/edit/2');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fqr-images%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::delete()
     */
    public function testDelete(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        // not logged in, missing id
        $this->get('https://localhost/qr-images/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test get with missing id and debug
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'QrImages',
            'action' => 'delete',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on

        // test get with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/delete/2');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test post with regular, post
        $this->loginUserRegular();
        $this->post('https://localhost/qr-images/delete/2');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test delete with regular user
        $this->loginUserRegular();
        $this->delete('https://localhost/qr-images/delete/2');
        $this->assertRedirectEquals('https://localhost/qr-images/qr-code/1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with admin, get
        $this->loginUserAdmin();
        $this->post('https://localhost/qr-images/delete/2');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test with admin, post no data, no CSRF
        $this->loginUserAdmin();
        $this->delete('https://localhost/qr-images/delete/2');
        $this->assertRedirectEquals('https://localhost/qr-images/qr-code/1');
        $this->assertFlashMessage('The image `Front Cover` for `Sow & Scribe` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
