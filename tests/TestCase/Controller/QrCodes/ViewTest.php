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
        $this->helperTestLayoutNormal();

        // test with admin
        $this->loginUserAdmin();
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test with reqular
        $this->loginUserRegular();
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test html content.
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<a href="/qr-codes/view/1">View</a>'));
        $this->assertSame(1, substr_count($content, '<a href="/qr-codes/forward/sownscribe">Follow</a>'));
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
        $this->helperTestLayoutNormal();

        // test with admin
        $this->loginUserAdmin();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test with reqular
        $this->loginUserRegular();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test html content.
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<h3>Sow &amp; Scribe</h3>'));
        $this->assertSame(1, substr_count($content, '<a href="/qr-codes/forward/sownscribe" class="side-nav-item">Follow</a>'));
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
        $this->helperTestLayoutNormal();

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
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
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `edit` on `App\Model\Entity\QrCode`.');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
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
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `edit` on `App\Model\Entity\QrCode`.');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }
}
