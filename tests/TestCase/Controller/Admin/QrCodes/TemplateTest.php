<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\QrCodesController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax.
 *
 * TODO: Test specific HTML once templates are done.
 * labels: frontend, templates, tesing
 *
 * @uses \App\Controller\Admin\QrCodesController
 */
class TemplateTest extends BaseControllerTest
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
     * @uses \App\Controller\Admin\QrCodesController::index()
     */
    public function testIndexNormal(): void
    {
        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardIndex();
        $this->helperTestTemplate('Admin/QrCodes/index');
        $this->helperValidateHTML();

        // test with admin
        // test html content.
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardIndex();
        // TODO: Also look for sub-layout page.
        $this->helperTestTemplate('Admin/QrCodes/index');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::index()
     */
    public function testIndexAjax(): void
    {
        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrCodes/index');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrCodes/index');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::view()
     */
    public function testViewNormal(): void
    {
        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/QrCodes/view');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        // TODO: Also look for sub-layout page.
        $this->helperTestTemplate('Admin/QrCodes/view');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::view()
     */
    public function testViewAjax(): void
    {
        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrCodes/view');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrCodes/view');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::add()
     */
    public function testAddNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/QrCodes/add');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/QrCodes/add');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::add()
     */
    public function testAddAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrCodes/add');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrCodes/add');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::edit()
     */
    public function testEditNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/edit/3');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/QrCodes/edit');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/QrCodes/edit');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::edit()
     */
    public function testEditAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-codes/edit/3');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrCodes/edit');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrCodes/edit');
    }
}
