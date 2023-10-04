<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrImagesController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\QrImagesController
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
     * @uses \App\Controller\QrImagesController::add()
     */
    public function testAdd(): void
    {
        // test failed
        $this->post('/qr-images/add/1', [
        ]);
        $this->assertResponseOk();
        $this->helperTestAlert('The image could not be saved. Please, try again.', 'danger');
        $this->assertResponseContains('<div class="qrImages form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/qr-images/add">');
        $this->assertResponseContains('<legend>Add QR Code</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('id="qrkey-error"');
        $this->assertResponseContains('id="name-error"');
        $this->assertResponseContains('id="description-error"');
        $this->assertResponseContains('id="url-error"');
        $this->assertResponseContains('id="source-id-error"');
        // user is added in the controller, so no form element for it.

        // test success
        $this->post('/qr-images/add/1', [
            'qrkey' => 'newqrcode',
            'name' => 'New QrImage',
            'description' => 'The Description',
            'url' => 'https://amazon.com/path/to/details/page/newqrcode',
            'source_id' => 1,
            'user_id' => 1,
        ]);
        $this->assertRedirectContains('/qr-images/qr-code/1');
        $this->assertFlashMessage('The image has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // TODO: Fix adding a QR Code with selected Categories.
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::edit()
     */
    public function testEdit(): void
    {
        // test fail, can't edit qrkey
        $this->patch('/qr-images/edit/2', [
            'qrkey' => 'blahblah', // changed key
        ]);
        $this->assertResponseOk();
        $this->helperTestAlert('The image could not be saved. Please, try again.', 'danger');
        $this->assertResponseContains('<div class="qrImages form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" role="form" action="/qr-images/edit/1">');
        $this->assertResponseContains('<legend>Edit QR Code</legend>');
        // don't show the frontend an error as this shouldn't happen.
        // if it does, it's someone trying to be nefarious, don't give them more info.

        // a test fail existing key
        $this->patch('/qr-images/edit/2', [
            'qrkey' => 'witchinghour', // an existing record
        ]);
        $this->assertResponseOk();
        $this->helperTestAlert('The image could not be saved. Please, try again.', 'danger');
        $this->assertResponseContains('<div class="qrImages form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" role="form" action="/qr-images/edit/1">');
        $this->assertResponseContains('<legend>Edit QR Code</legend>');
        // don't show the frontend an error as this shouldn't happen.
        // if it does, it's someone trying to be nefarious, don't give them more info.

        // test success
        $this->patch('/qr-images/edit/2', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertRedirect();
        $this->assertRedirectContains('/qr-images/qr-code/1');
        $this->assertFlashMessage('The image has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
