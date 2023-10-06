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
    public function testDontexist(): void
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
     */
    public function testIndex(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/qr-images');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\QrImagesController::index()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\QrImagesController::index()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\QrImagesController::index()`');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images');
        $this->assertResponseCode(404);
        $this->helperTestError400('/qr-images');
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
        $this->loginGuest();
        $this->get('https://localhost/qr-images/qr-code/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-images%2Fqr-code%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/qr-code/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fqr-images%2Fqr-code%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/qr-code/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrImages/qr_code');
        $this->helperTestObjectComment(3, 'QrImages/entity');
        $this->helperTestObjectComment(2, 'QrImages/entity/active');
        $this->helperTestObjectComment(1, 'QrImages/entity/inactive');

        // test with admin, not owner
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/qr-code/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrImages/qr_code');
        $this->helperTestObjectComment(3, 'QrImages/entity');
        $this->helperTestObjectComment(2, 'QrImages/entity/active');
        $this->helperTestObjectComment(1, 'QrImages/entity/inactive');

        // test with admin, owner
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/qr-code/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrImages/qr_code');
        $this->helperTestObjectComment(2, 'QrImages/entity');
        $this->helperTestObjectComment(2, 'QrImages/entity/active');
        $this->helperTestObjectComment(0, 'QrImages/entity/inactive');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/qr-code/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrImages/qr_code');
        Configure::write('debug', true);
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
        $this->assertResponseContains('Error: Record not found in table `qr_images`.');

        // not logged in, missing image, debug off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->get('https://localhost/qr-images/show/999');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Not Found');
        Configure::write('debug', true);

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
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // test with reqular, inactive image, not owner
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/show/3');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fqr-images%2Fshow%2F3');
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

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/show');
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
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->get('https://localhost/qr-images/add/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-images%2Fadd%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/add/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fqr-images%2Fadd%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/add/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrImages/add');
        $this->helperTestFormTag('/qr-images/add/3', 'post', true);

        // test with admin, not owner
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/add/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrImages/add');
        $this->helperTestFormTag('/qr-images/add/3', 'post', true);

        // test with admin, owner
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/add/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrImages/add');
        $this->helperTestFormTag('/qr-images/add/1', 'post', true);

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/qr-images/add');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/add');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/add');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/add');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::edit()
     */
    public function testEdit(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->get('https://localhost/qr-images/edit/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-images%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/edit/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fqr-images%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/edit/5');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrImages/edit');
        $this->helperTestFormTag('/qr-images/edit/5', 'patch', true);

        // test with admin, owner
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/edit/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrImages/edit');
        $this->helperTestFormTag('/qr-images/edit/1', 'patch', true);

        // test with admin, not owner
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/edit/5');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrImages/edit');
        $this->helperTestFormTag('/qr-images/edit/5', 'patch', true);

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/qr-images/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::delete()
     */
    public function testDelete(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->delete('https://localhost/qr-images/delete/1');
        $this->assertRedirectEquals('https://localhost/users/login');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->delete('https://localhost/qr-images/delete/1');
        $this->assertRedirectEquals('https://localhost/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->delete('https://localhost/qr-images/delete/5');
        // qr-codes/index is the homepage.
        $this->assertRedirectEquals('https://localhost/qr-images/qr-code/3');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The image `In Hand` for `American Flag Charm` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin, not owner
        $this->loginUserAdmin();
        $this->delete('https://localhost/qr-images/delete/6');
        // qr-codes/index is the homepage.
        $this->assertRedirectEquals('https://localhost/qr-images/qr-code/3');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The image `Dimensions Top` for `American Flag Charm` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin, owner
        $this->loginUserAdmin();
        $this->delete('https://localhost/qr-images/delete/1');
        // qr-codes/index is the homepage.
        $this->assertRedirectEquals('https://localhost/qr-images/qr-code/1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The image `Front Cover` for `Sow & Scribe` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->delete('https://localhost/qr-images/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->delete('https://localhost/qr-images/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/qr-images/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin, debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->delete('https://localhost/qr-images/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);

        // not logged in, debug off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->delete('https://localhost/qr-images/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }
}
