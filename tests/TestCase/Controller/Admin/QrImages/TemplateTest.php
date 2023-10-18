<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\QrImagesController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax.
 *
 * @uses \App\Controller\Admin\QrImagesController
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
     * Test qrCode method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::qrCode()
     */
    public function testQrCodeNormal(): void
    {
        // test with admin
        // test html content.
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/qr-code/1');
        $this->assertResponseOk();
        // must be view as it extends /admin/QrCodes/details
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/QrCodes/details');
        $this->helperTestTemplate('Admin/QrImages/qr_code');
        $this->helperTestObjectComment(3, 'QrImages/entity');
        $this->helperTestObjectComment(3, 'QrImages/entity/active');

        // validate the html
        $this->helperValidateHTML(true);
    }

    /**
     * Test qrCode method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::qrCode()
     */
    public function testQrCodeAjax(): void
    {
        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/qr-code/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrImages/qr_code');
        $this->helperTestObjectComment(3, 'QrImages/entity');
        $this->helperTestObjectComment(3, 'QrImages/entity/active');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::add()
     */
    public function testAddNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/add/3');
        $this->assertResponseOk();
        // must be view as it extends /admin/QrCodes/details
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/QrCodes/details');
        $this->helperTestTemplate('Admin/QrImages/add');

        // test with admin, get, can edit any.
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/add/3');
        $this->assertResponseOk();
        // must be view as it extends /admin/QrCodes/details
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/QrCodes/details');
        $this->helperTestTemplate('Admin/QrImages/add');

        // validate the html
        $this->helperValidateHTML(true);
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::add()
     */
    public function testAddAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/add/3');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrImages/add');

        // test with admin, get, can edit any.
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/add/3');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrImages/add');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::edit()
     */
    public function testEditNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/edit/5');
        $this->assertResponseOk();
        // must be view as it extends /admin/QrCodes/details
        $this->helperTestLayoutDashboardForm();
        $this->helperTestTemplate('Admin/QrImages/edit');

        // test with admin, get, can edit any.
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/edit/5');
        $this->assertResponseOk();
        // must be view as it extends /admin/QrCodes/details
        $this->helperTestLayoutDashboardForm();
        $this->helperTestTemplate('Admin/QrImages/edit');

        // validate the html
        $this->helperValidateHTML(true);
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::edit()
     */
    public function testEditAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/qr-images/edit/5');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrImages/edit');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-images/edit/5');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/QrImages/edit');
    }
}
