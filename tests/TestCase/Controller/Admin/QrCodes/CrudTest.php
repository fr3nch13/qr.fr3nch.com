<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\QrCodesController Test Case
 *
 * Tests that the proper http method is being used.
 *
 * @uses \App\Controller\Admin\QrCodesController
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
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::index()
     */
    public function testIndex(): void
    {
        // get
        $this->get('https://localhost/admin/qr-codes');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/index');

        // post
        $this->post('https://localhost/admin/qr-codes');
        $this->assertRedirectEquals('https://localhost/admin/qr-codes');
        // changed because we added friendsofcake/search
        // which does a Post-Redirect-Get
        // $this->assertResponseCode(405);
        // $this->assertResponseContains('Method Not Allowed');


        // patch
        $this->patch('https://localhost/admin/qr-codes');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/qr-codes');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/qr-codes');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::view()
     */
    public function testView(): void
    {
        // test get
        $this->get('https://localhost/admin/qr-codes/view/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/view');

        // post
        $this->post('https://localhost/admin/qr-codes/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin/qr-codes/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/qr-codes/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/qr-codes/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::add()
     */
    public function testAdd(): void
    {
        // test get
        $this->get('https://localhost/admin/qr-codes/add');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/add');

        // post
        $this->post('https://localhost/admin/qr-codes/add', [
            'qrkey' => 'newqrcode',
            'name' => 'New QrCode',
            'description' => 'The Description',
            'url' => 'https://amazon.com/path/to/details/page/newqrcode',
            'source_id' => 1,
            'user_id' => 1,
        ]);
        $this->assertRedirectEquals('https://localhost/admin/qr-codes/view/6');
        $this->assertFlashMessage('The qr code has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('https://localhost/admin/qr-codes/add', [
            'qrkey' => 'newqrcode',
            'name' => 'New QrCode',
            'description' => 'The Description',
            'url' => 'https://amazon.com/path/to/details/page/newqrcode',
            'source_id' => 1,
            'user_id' => 1,
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/qr-codes/add', [
            'qrkey' => 'newqrcode',
            'name' => 'New QrCode',
            'description' => 'The Description',
            'url' => 'https://amazon.com/path/to/details/page/newqrcode',
            'source_id' => 1,
            'user_id' => 1,
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/qr-codes/add');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::edit()
     */
    public function testEdit(): void
    {
        // test get
        $this->get('https://localhost/admin/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrCodes/edit');

        // post
        $this->post('https://localhost/admin/qr-codes/edit/1', [
            'name' => 'Edited QrCode',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/admin/qr-codes/edit/1', [
            'name' => 'Edited QrCode',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/qr-codes/edit/1', [
            'name' => 'Edited QrCode',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/qr-codes/view/1');
        $this->assertFlashMessage('The qr code has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // delete
        $this->delete('https://localhost/admin/qr-codes/edit/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::delete()
     */
    public function testDelete(): void
    {
        // test get
        $this->get('https://localhost/admin/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // post
        $this->post('https://localhost/admin/qr-codes/delete/1');
        $this->assertRedirectEquals('https://localhost/admin/qr-codes');
        $this->assertFlashMessage('The qr code `Sow & Scribe` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('https://localhost/admin/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/admin/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/admin/qr-codes/delete/2');
        $this->assertRedirectEquals('https://localhost/admin/qr-codes');
        $this->assertFlashMessage('The qr code `The Witching Hour` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
