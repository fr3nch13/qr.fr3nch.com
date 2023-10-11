<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\QrCodesController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\Admin\QrCodesController
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
    public function testDontexist(): void
    {
        // not logged in
        $this->get('https://localhost/admin/qr-codes/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\QrCodesController::dontexist()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\QrCodesController::dontexist()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\Admin\QrCodesController::dontexist()`');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/admin/qr-codes/dontexist');
        Configure::write('debug', true);
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-codes');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-codes');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/index');

        /*
        // TODO: Add this back once we have the admin frontend figured out.
        // labels: templates, admin, frontent
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
        */

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/index');

        /*
        // TODO: Add this back once we have the admin frontend figured out.
        // labels: templates, admin, frontent
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
        */

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/index');
        Configure::write('debug', true);
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::view()
     */
    public function testView(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-codes/view/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-codes%2Fview%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/view');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/view');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::add()
     */
    public function testAdd(): void
    {
        $this->enableSecurityToken();

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-codes/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-codes%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/add');
        $this->helperTestFormTag('/admin/qr-codes/add', 'post');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/add');
        $this->helperTestFormTag('/admin/qr-codes/add', 'post');

        // Debug Off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-codes/add');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-codes%2Fadd');
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        Configure::write('debug', true);
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::edit()
     */
    public function testEdit(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-codes/edit/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-codes%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/edit/1');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fqr-codes%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with regular, owner
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/edit/4');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/edit');
        $this->helperTestFormTag('/admin/qr-codes/edit/4', 'put');

        // test with admin, not owner
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/edit/4');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/edit');
        $this->helperTestFormTag('/admin/qr-codes/edit/4', 'put');

        // test with admin, owner
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/edit');
        $this->helperTestFormTag('/admin/qr-codes/edit/1', 'put');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/admin/qr-codes/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::delete()
     */
    public function testDelete(): void
    {
        $this->enableSecurityToken();

        // not logged
        $this->loginGuest();
        $this->delete('https://localhost/admin/qr-codes/delete/3');
        $this->assertRedirectEquals('https://localhost/users/login');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, not owner
        $this->enableRetainFlashMessages();
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/qr-codes/delete/2');
        $this->assertRedirectEquals('https://localhost/admin');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with reqular, owner
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/qr-codes/delete/3');
        // qr-codes/index is the homepage.
        $this->assertRedirectEquals('https://localhost/admin/qr-codes');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The qr code `American Flag Charm` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // test with admin
        $this->loginUserAdmin();
        // not 3 as it was deleted above.
        $this->delete('https://localhost/admin/qr-codes/delete/2');
        // to the dashboard.
        $this->assertRedirectEquals('https://localhost/admin/qr-codes');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('The qr code `The Witching Hour` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        /// Missing IDs

        // not logged in
        $this->loginGuest();
        $this->delete('https://localhost/admin/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with reqular
        $this->loginUserRegular();
        $this->delete('https://localhost/admin/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin, debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->delete('https://localhost/admin/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);

        // not logged in, debug off
        Configure::write('debug', false);
        $this->loginGuest();
        $this->delete('https://localhost/admin/qr-codes/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true);
    }
}
