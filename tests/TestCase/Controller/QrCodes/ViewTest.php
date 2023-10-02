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
        $this->assertResponseOk();
        $this->helperTestLayoutDefault();
        $content = (string)$this->_response->getBody();
        debug($content);

        // test with admin
        $this->loginUserAdmin();
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutDefault();

        // test with reqular
        $this->loginUserRegular();
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutDefault();

        // test html content.
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<a href="/qr-codes/view/1">View</a>'));
        $this->assertSame(1, substr_count($content, '<a href="/f/sownscribe">Follow</a>'));
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
        $this->helperTestLayoutDefault();

        // test with admin
        $this->loginUserAdmin();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutDefault();

        // test with reqular
        $this->loginUserRegular();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutDefault();

        // test html content.
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<h3>Sow &amp; Scribe</h3>'));
        $this->assertSame(1, substr_count($content, '<a href="/f/sownscribe" class="side-nav-item">Follow</a>'));
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
        $this->helperTestLayoutDefault();

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutDefault();
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
        $this->assertRedirectContains('/?redirect=%2Fqr-codes%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutDefault();
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
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/?redirect=%2Fqr-codes%2Fedit%2F1');
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
