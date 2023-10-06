<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrCodes;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrCodesController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\QrCodesController
 */
class FormsTest extends BaseControllerTest
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
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::add()
     */
    public function testAdd(): void
    {
        // test failed
        $this->post('https://localhost/qr-codes/add', [
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/add');
        $this->helperTestFormTag('/qr-codes/add');
        $this->helperTestAlert('The qr code could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This field is required', 'qrkey-error');
        $this->helperTestFormFieldError('This field is required', 'name-error');
        $this->helperTestFormFieldError('This field is required', 'description-error');
        $this->helperTestFormFieldError('This field is required', 'url-error');
        $this->helperTestFormFieldError('This field is required', 'source-id-error');

        // test success
        $this->post('https://localhost/qr-codes/add', [
            'qrkey' => 'newqrcode',
            'name' => 'New QrCode',
            'description' => 'The Description',
            'url' => 'https://amazon.com/path/to/details/page/newqrcode',
            'source_id' => 1,
            'user_id' => 1,
        ]);
        $this->assertRedirectEquals('https://localhost/qr-codes/view/5');
        $this->assertFlashMessage('The qr code has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // TODO: Fix adding a QR Code with selected Categories.
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::edit()
     */
    public function testEdit(): void
    {
        // test fail, can't edit qrkey
        $this->patch('https://localhost/qr-codes/edit/1', [
            'qrkey' => 'blahblah', // changed key
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/edit');
        $this->helperTestFormTag('/qr-codes/edit/1', 'patch');
        $this->helperTestAlert('The qr code could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        // don't show the frontend an error as this shouldn't happen.
        // if it does, it's someone trying to be nefarious, don't give them more info.
        // $this->helperTestFormFieldError('Message here.', 'qrkey-error');

        // a test fail existing key
        $this->patch('https://localhost/qr-codes/edit/1', [
            'qrkey' => 'witchinghour', // an existing record
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('QrCodes/edit');
        $this->helperTestFormTag('/qr-codes/edit/1', 'patch');
        $this->helperTestAlert('The qr code could not be saved. Please, try again.', 'danger');
        // don't show the frontend an error as this shouldn't happen.
        // if it does, it's someone trying to be nefarious, don't give them more info.
        // $this->helperTestFormFieldError('Message here.', 'qrkey-error');

        // test success
        $this->patch('https://localhost/qr-codes/edit/1', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertRedirectEquals('https://localhost/qr-codes/view/1');
        $this->assertFlashMessage('The qr code has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
