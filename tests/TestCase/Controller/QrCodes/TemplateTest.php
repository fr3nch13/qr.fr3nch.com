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
 * @uses \App\Controller\QrCodesController
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

        // make sure the codes are listed.
        // make sure only active codes are listed.
        $this->helperTestObjectComment(3, 'QrCodes/active');
        $this->helperTestObjectComment(0, 'QrCodes/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(3, 'QrCode/show');
        $this->helperTestObjectComment(3, 'QrCode/forward');
        $this->helperTestObjectComment(3, 'QrCode/view');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(3, 'QrImages/active/first');

        // validate the html
        $this->helperValidateHTML(true);
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
        // make sure only active
        $this->helperTestObjectComment(3, 'QrCodes/active');
        $this->helperTestObjectComment(0, 'QrCodes/inactive');
        // make sure the qcode is listed for each one.
        $this->helperTestObjectComment(3, 'QrCode/show');
        $this->helperTestObjectComment(3, 'QrCode/forward');
        // make sure only active primary images are listed.
        $this->helperTestObjectComment(3, 'QrImages/active/first');
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
        // the images
        // the smaller images in the carousel
        $this->helperTestObjectComment(3, 'QrImage/show/thumb/sm');
        $this->helperTestObjectComment(1, 'QrCode/show/thumb/sm');
        // the larger images in the carousel
        $this->helperTestObjectComment(3, 'QrImage/show/thumb/lg');
        $this->helperTestObjectComment(1, 'QrCode/show/thumb/lg');
        // the forward button
        $this->helperTestObjectComment(1, 'QrCode/forward');

        // validate the html
        $this->helperValidateHTML(true);
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
        // the images
        // the smaller images in the carousel
        $this->helperTestObjectComment(3, 'QrImage/show/thumb/sm');
        $this->helperTestObjectComment(1, 'QrCode/show/thumb/sm');
        // the larger images in the carousel
        $this->helperTestObjectComment(3, 'QrImage/show/thumb/lg');
        $this->helperTestObjectComment(1, 'QrCode/show/thumb/lg');
        // the forward button
        $this->helperTestObjectComment(1, 'QrCode/forward');
    }
}
