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
        $this->post('https://localhost/qr-images/add/1', [
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('QrImages/add');
        $this->helperTestFormTag('/qr-images/add/1', 'post', true);
        $this->helperTestAlert('The image could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This field is required', 'name-error');
        // TODO: add testing for the file form field
        // labels: frontend, templates, form, upload
        // user is added in the controller, so no form element for it.

        // test success
        $this->post('https://localhost/qr-images/add/1', [
            'name' => 'New QrImage',
            'ext' => 'jpg', // TODO: change this once we get file uploading working.
        ]);
        $this->assertRedirectEquals('https://localhost/qr-images/qr-code/1');
        $this->assertFlashMessage('The image has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     * TODO: expend testing, especially when trying to change the image/file.
     * labels: frontend, templates, form, upload
     *
     * @return void
     * @uses \App\Controller\QrImagesController::edit()
     */
    public function testEdit(): void
    {
        // test success
        $this->put('https://localhost/qr-images/edit/2', [
            'name' => 'New Category',
        ]);
        $this->assertRedirectEquals('https://localhost/qr-images/qr-code/1');
        $this->assertFlashMessage('The image has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
