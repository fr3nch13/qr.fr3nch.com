<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrImagesController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax, and json.
 *
 * @uses \App\Controller\QrImagesController
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
     * @uses \App\Controller\QrImagesController::index()
     */
    public function testIndexNormal(): void
    {
        // not logged in
        $this->get('https://localhost/qr-images');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();

        // test with admin
        // test html content.
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.QrImages/index -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.QrImages/index -->'));
        $this->assertSame(1, substr_count($content, '<h1>QR Codes</h1>'));
        $this->assertSame(3, substr_count($content, '<div class="product">'));
        $this->assertSame(3, substr_count($content, '<div class="product-title">'));
        $this->assertSame(3, substr_count($content, '<figure class="product-image">'));

        // make sure the products are listed.
        // Sow & Scribe
        $this->assertSame(1, substr_count($content, '<a href="/qr-images/view/1" class="product-title">Sow &amp; Scribe</a>'));
        $this->assertSame(1, substr_count($content, '<img src="/qr-images/show/1" alt="The QR Code>'));
        $this->assertSame(1, substr_count($content, '<a href="/f/sownscribe" class="btn btn-light">Follow</a>'));
        $this->assertSame(1, substr_count($content, '<a href="/qr-images/view/1" class="btn btn-light">View</a>'));
        // Witching Hour
        $this->assertSame(1, substr_count($content, '<a href="/qr-images/view/2" class="product-title">The Witching Hour</a>'));
        $this->assertSame(1, substr_count($content, '<img src="/qr-images/show/2" alt="The QR Code>'));
        $this->assertSame(1, substr_count($content, '<a href="/f/witchinghour" class="btn btn-light">Follow</a>'));
        $this->assertSame(1, substr_count($content, '<a href="/qr-images/view/2" class="btn btn-light">View</a>'));
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::index()
     */
    public function testIndexAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('https://localhost/qr-images');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::view()
     */
    public function testViewNormal(): void
    {
        // not logged in
        $this->get('https://localhost/qr-images/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();

        // test html content.
        $this->get('https://localhost/qr-images/view/1');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.QrImages/view -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.QrImages/view -->'));
        $this->assertSame(1, substr_count($content, '<h1 class="mb-1">Sow &amp; Scribe</h1>'));
        $this->assertSame(2, substr_count($content, '<img class="img-fluid" src="/qr-images/show/1" alt="The QR Code">'));
        $this->assertSame(1, substr_count($content, '<a href="/f/sownscribe" class="btn btn-primary btn-block rounded-pill" role="button">Follow</a>'));
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::view()
     */
    public function testViewAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('https://localhost/qr-images/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::add()
     */
    public function testAddNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/add/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/add/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::add()
     */
    public function testAddAjax(): void
    {
        // test with reqular, get
        // not their qr code
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/add/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fqr-images%2Fadd%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // their qr code.
        $this->get('https://localhost/qr-images/add/3');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();


        // test with admin, get, can edit any.
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/add/3');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::edit()
     */
    public function testEditNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/edit/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fqr-images%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::edit()
     */
    public function testEditAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/edit/2');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fqr-images%2Fedit%2F2');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/edit/2');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }
}
