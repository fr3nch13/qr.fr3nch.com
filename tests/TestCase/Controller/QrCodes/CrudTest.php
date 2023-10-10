<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrCodesController Test Case
 *
 * Tests that the proper http method is being used.
 *
 * TODO: Add test for show and forward.
 * labels: tests, crud
 *
 * @uses \App\Controller\QrCodesController
 */
class CrudTest extends BaseControllerTest
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
        $this->loginUserAdmin();
    }

    /**
     * Test qr-code method
     *
     * @uses \App\Controller\QrImagesController::qrCode()
     * @return void
     */
    public function testShow(): void
    {
        // get
        $this->get('https://localhost/qr-codes/show/1');
        $this->assertResponseOk();

        // post
        $this->post('https://localhost/qr-codes/show/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/qr-codes/show/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/qr-codes/show/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/qr-codes/show/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::index()
     */
    public function testIndex(): void
    {
        // get
        $this->get('https://localhost/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/index');

        // post
        $this->post('https://localhost/qr-codes');
        $this->assertRedirectEquals('https://localhost/qr-codes');
        // changed because we added friendsofcake/search
        // which does a Post-Redirect-Get
        // $this->assertResponseCode(405);
        // $this->assertResponseContains('Method Not Allowed');


        // patch
        $this->patch('https://localhost/qr-codes');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/qr-codes');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/qr-codes');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::view()
     */
    public function testView(): void
    {
        // test get
        $this->get('https://localhost/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/view');

        // post
        $this->post('https://localhost/qr-codes/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/qr-codes/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/qr-codes/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/qr-codes/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }
}
