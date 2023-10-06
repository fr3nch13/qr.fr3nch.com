<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrImagesController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax.
 *
 * TODO: Test specific HTML once templates are done.
 * labels: frontend, templates, tesing
 *
 * @uses \App\Controller\QrImagesController
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
     * @uses \App\Controller\QrImagesController::qrCode()
     */
    public function testQrCodeNormal(): void
    {
        // test with admin
        // test html content.
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/qr-code/1');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('QrImages/qr_code');
        $this->helperTestObjectComment(2, 'QrImages/entity');
        $this->helperTestObjectComment(2, 'QrImages/entity/active');
    }

    /**
     * Test qrCode method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::qrCode()
     */
    public function testQrCodeAjax(): void
    {
        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/qr-code/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrImages/qr_code');
        $this->helperTestObjectComment(2, 'QrImages/entity');
        $this->helperTestObjectComment(2, 'QrImages/entity/active');
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
        $this->get('https://localhost/qr-images/add/3');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
        $this->helperTestTemplate('QrImages/add');

        // test with admin, get, can edit any.
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/add/3');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
        $this->helperTestTemplate('QrImages/add');
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
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/qr-images/add/3');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrImages/add');

        // test with admin, get, can edit any.
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/add/3');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrImages/add');
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
        $this->get('https://localhost/qr-images/edit/5');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
        $this->helperTestTemplate('QrImages/edit');

        // test with admin, get, can edit any.
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/edit/5');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesForm();
        $this->helperTestTemplate('QrImages/edit');
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
        $this->get('https://localhost/qr-images/edit/5');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrImages/edit');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/edit/5');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('QrImages/edit');
    }
}
