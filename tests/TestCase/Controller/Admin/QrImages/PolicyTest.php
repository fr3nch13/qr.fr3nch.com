<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\QrImagesController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\Admin\QrImagesController
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
     * @alert Keep the https://localhost/admin/ as the HttpsEnforcerMiddleware will try to redirect.
     * @return void
     */
    public function testDontexistDebugOn(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-images/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\QrImagesController::dontexist()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\QrImagesController::dontexist()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\QrImagesController::dontexist()`');
    }

    /**
     * Test missing action
     *
     * @alert Keep the https://localhost/admin/ as the HttpsEnforcerMiddleware will try to redirect.
     * @return void
     */
    public function testDontexistDebugOff(): void
    {
        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/admin/qr-images/dontexist');
    }

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndexDebugOn(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-images');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\QrImagesController::index()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\QrImagesController::index()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\QrImagesController::index()`');
    }

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndexDebugOff(): void
    {
        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images');
        $this->assertResponseCode(404);
        $this->helperTestError400('/admin/qr-images');
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::qrCode()
     */
    public function testQrCodeDebugOn(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-images/qr-code/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-images%2Fqr-code%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/qr-code/1');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fqr-images%2Fqr-code%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/qr-code/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/qr_code');
        $this->helperTestObjectComment(3, 'QrImages/entity');
        $this->helperTestObjectComment(2, 'QrImages/entity/active');
        $this->helperTestObjectComment(1, 'QrImages/entity/inactive');

        // test with admin, not owner
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/qr-code/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/qr_code');
        $this->helperTestObjectComment(3, 'QrImages/entity');
        $this->helperTestObjectComment(2, 'QrImages/entity/active');
        $this->helperTestObjectComment(1, 'QrImages/entity/inactive');

        // test with admin, owner
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/qr-code/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/qr_code');
        $this->helperTestObjectComment(3, 'QrImages/entity');
        $this->helperTestObjectComment(3, 'QrImages/entity/active');
        $this->helperTestObjectComment(0, 'QrImages/entity/inactive');
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::qrCode()
     */
    public function testQrCodeDebugOff(): void
    {
        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/qr-code/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/qr_code');
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
        $this->get('https://localhost/admin/qr-images/show/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-images%2Fshow%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // not logged in, inactive image
        $this->get('https://localhost/admin/qr-images/show/7');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-images%2Fshow%2F7');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // not logged in, missing image, debug on
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-images/show/999');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-images%2Fshow%2F999');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, active image
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // test with admin, inactive image, not owner
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/show/7');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // test with reqular, active image
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // test with reqular, inactive image, owner
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/show/7');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // test with reqular, inactive image, not owner
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/show/3');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fqr-images%2Fshow%2F3');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with missing id and debug
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-images/show');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/show');
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
        $this->get('https://localhost/admin/qr-images/show/999');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-images%2Fshow%2F999');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/show');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::add()
     */
    public function testAddDebugOn(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-images/add/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-images%2Fadd%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/add/1');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fqr-images%2Fadd%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/add/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/add');
        $this->helperTestFormTag('/admin/qr-images/add/3', 'post', true);

        // test with admin, not owner
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/add/3');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/add');
        $this->helperTestFormTag('/admin/qr-images/add/3', 'post', true);

        // test with admin, owner
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/add/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/add');
        $this->helperTestFormTag('/admin/qr-images/add/1', 'post', true);

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-images/add');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/add');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/add');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::add()
     */
    public function testAddOff(): void
    {
        $this->enableSecurityToken();
        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/add');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::edit()
     */
    public function testEditDebugOn(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-images/edit/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-images%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/edit/1');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fqr-images%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/edit/5');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/edit');
        $this->helperTestFormTag('/admin/qr-images/edit/5', 'put');

        // test with admin, owner
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/edit/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/edit');
        $this->helperTestFormTag('/admin/qr-images/edit/1', 'put');

        // test with admin, not owner
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/edit/5');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/edit');
        $this->helperTestFormTag('/admin/qr-images/edit/5', 'put');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-images/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::edit()
     */
    public function testEditDebugOff(): void
    {
        $this->enableSecurityToken();

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::delete()
     */
    public function testDeleteDebugOn(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->delete('https://localhost/admin/qr-images/delete/1');
        $this->assertRedirectEquals('https://localhost/users/login');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/qr-images/delete/1');
        $this->assertRedirectEquals('https://localhost/admin');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/qr-images/delete/5');
        // qr-codes/index is the homepage.
        $this->assertRedirectEquals('https://localhost/admin/qr-images/qr-code/3');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The image `In Hand` for `American Flag Charm` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin, not owner
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/qr-images/delete/6');
        // qr-codes/index is the homepage.
        $this->assertRedirectEquals('https://localhost/admin/qr-images/qr-code/3');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The image `Dimensions Top` for `American Flag Charm` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin, owner
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/qr-images/delete/1');
        // qr-codes/index is the homepage.
        $this->assertRedirectEquals('https://localhost/admin/qr-images/qr-code/1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The image `Front Cover` for `Sow & Scribe` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->delete('https://localhost/admin/qr-images/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/qr-images/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/qr-images/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::delete()
     */
    public function testDeleteDebugOff(): void
    {
        $this->enableSecurityToken();

        // test with admin, debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/qr-images/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // not logged in, debug off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->delete('https://localhost/admin/qr-images/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }
}
