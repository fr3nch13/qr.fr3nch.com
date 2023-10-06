<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrCodesController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax.
 *
 * TODO: Test specific HTML once templates are done.
 * labels: frontend, templates, tesing
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
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('QrCodes/index');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('QrCodes/index');

        // test with admin
        // test html content.
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('QrCodes/index');

        // make sure the products are listed.
        // make sure all are listed.
        // this may change to include all active, and inactive, scoped to the user.
        $this->helperTestObjectComment(3, 'QrCodes/active');
        $this->helperTestObjectComment(1, 'QrCodes/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(4, 'QrCode/show');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(3, 'QrImages/active/first');
        // Sow & Scribe
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<a href="/qr-codes/view/1" class="product-title">Sow &amp; Scribe</a>'));
        $this->assertSame(1, substr_count($content, '<img class="product-qrcode" src="/qr-codes/show/1" alt="The QR Code">'));
        $this->assertSame(1, substr_count($content, '<a href="/f/sownscribe" class="btn btn-light">Follow Code</a>'));
        $this->assertSame(1, substr_count($content, '<a href="/qr-codes/view/1" class="btn btn-light">Details</a>'));
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
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrCodes/index');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrCodes/index');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrCodes/index');

        // make sure the products are listed.
        // make sure all are listed.
        // this may change to include all active, and inactive, scoped to the user.
        $this->helperTestObjectComment(3, 'QrCodes/active');
        $this->helperTestObjectComment(1, 'QrCodes/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(4, 'QrCode/show');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(3, 'QrImages/active/first');
        // Sow & Scribe
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<a href="/qr-codes/view/1" class="product-title">Sow &amp; Scribe</a>'));
        $this->assertSame(1, substr_count($content, '<img class="product-qrcode" src="/qr-codes/show/1" alt="The QR Code">'));
        $this->assertSame(1, substr_count($content, '<a href="/f/sownscribe" class="btn btn-light">Follow Code</a>'));
        $this->assertSame(1, substr_count($content, '<a href="/qr-codes/view/1" class="btn btn-light">Details</a>'));
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
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();
        $this->helperTestTemplate('QrCodes/view');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();
        $this->helperTestTemplate('QrCodes/view');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();
        $this->helperTestTemplate('QrCodes/view');

        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<h1 class="mb-1">Sow &amp; Scribe</h1>'));
        $this->assertSame(2, substr_count($content, '<img class="img-fluid" src="/qr-codes/show/1" alt="The QR Code">'));
        $this->assertSame(1, substr_count($content, '<a href="/f/sownscribe" class="btn btn-primary btn-block rounded-pill" role="button">Follow Code</a>'));
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
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrCodes/view');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrCodes/view');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrCodes/view');

        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<h1 class="mb-1">Sow &amp; Scribe</h1>'));
        $this->assertSame(2, substr_count($content, '<img class="img-fluid" src="/qr-codes/show/1" alt="The QR Code">'));
        $this->assertSame(1, substr_count($content, '<a href="/f/sownscribe" class="btn btn-primary btn-block rounded-pill" role="button">Follow Code</a>'));
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
        $this->get('https://localhost/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
        $this->helperTestTemplate('QrCodes/add');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
        $this->helperTestTemplate('QrCodes/add');
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
        $this->get('https://localhost/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrCodes/add');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrCodes/add');
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
        $this->get('https://localhost/qr-codes/edit/3');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
        $this->helperTestTemplate('QrCodes/edit');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
        $this->helperTestTemplate('QrCodes/edit');
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
        $this->get('https://localhost/qr-codes/edit/3');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrCodes/edit');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrCodes/edit');
    }
}
