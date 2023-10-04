<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrCodesController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax, and json.
 *
 * @uses \App\Controller\QrCodesController
 */
class ViewTest extends BaseControllerTest
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
        $this->enableSecurityToken();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::index()
     */
    public function testIndexNormal(): void
    {
        // not logged in
        $this->get('/qr-codes');
        $content = (string)$this->_response->getBody();
        $this->assertResponseOk();
        $this->helperTestLayoutIndex();

        // test with reqular
        $this->loginUserRegular();
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutIndex();

        // test with admin
        // test html content.
        $this->loginUserAdmin();
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.QrCodes/index -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.QrCodes/index -->'));
        $this->assertSame(1, substr_count($content, '<h1>QR Codes</h1>'));
        $this->assertSame(3, substr_count($content, '<div class="product">'));
        $this->assertSame(3, substr_count($content, '<div class="product-title">'));
        $this->assertSame(3, substr_count($content, '<figure class="product-image">'));

        // make sure the products are listed.
        // Sow & Scribe
        $this->assertSame(1, substr_count($content, '<a href="/qr-codes/view/1" class="product-title">Sow &amp; Scribe</a>'));
        $this->assertSame(1, substr_count($content, '<img src="/qr-codes/show/1" alt="The QR Code>'));
        $this->assertSame(1, substr_count($content, '<a href="/f/sownscribe" class="btn btn-light">Follow</a>'));
        $this->assertSame(1, substr_count($content, '<a href="/qr-codes/view/1" class="btn btn-light">View</a>'));
        // Witching Hour
        $this->assertSame(1, substr_count($content, '<a href="/qr-codes/view/2" class="product-title">The Witching Hour</a>'));
        $this->assertSame(1, substr_count($content, '<img src="/qr-codes/show/2" alt="The QR Code>'));
        $this->assertSame(1, substr_count($content, '<a href="/f/witchinghour" class="btn btn-light">Follow</a>'));
        $this->assertSame(1, substr_count($content, '<a href="/qr-codes/view/2" class="btn btn-light">View</a>'));
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::index()
     */
    public function testIndexAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::view()
     */
    public function testViewNormal(): void
    {
        // not logged in
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutView();

        // test with reqular
        $this->loginUserRegular();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutView();

        // test with admin
        $this->loginUserAdmin();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutView();

        // test html content.
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.QrCodes/view -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.QrCodes/view -->'));
        $this->assertSame(1, substr_count($content, '<h1 class="mb-1">Sow &amp; Scribe</h1>'));
        $this->assertSame(2, substr_count($content, '<img class="img-fluid" src="/qr-codes/show/1" alt="The QR Code">'));
        $this->assertSame(1, substr_count($content, '<a href="/f/sownscribe" class="btn btn-primary btn-block rounded-pill" role="button">Follow</a>'));
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::view()
     */
    public function testViewAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::add()
     */
    public function testAddNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutForm();

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutForm();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::add()
     */
    public function testAddAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::edit()
     */
    public function testEditNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/qr-codes/edit/1');
        $this->assertRedirectContains('?redirect=%2Fqr-codes%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutForm();
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::edit()
     */
    public function testEditAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/qr-codes/edit/1');
        $this->assertRedirectContains('?redirect=%2Fqr-codes%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }
}
