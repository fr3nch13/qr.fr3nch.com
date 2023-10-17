<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\QrImagesController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\Admin\QrImagesController
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
     * Test add method with no images
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::add()
     */
    public function testAddNoFiles(): void
    {
        // test failed
        $this->post('https://localhost/admin/qr-images/add/1', [
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/add');
        $this->helperTestFormTag('/admin/qr-images/add/1', 'post', true);
        $this->helperTestAlert('The images could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestString('<p class="text-danger">No images were uploaded</p>');
    }

    /**
     * Test add with a bad file
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::add()
     */
    public function testAddBadFile(): void
    {
        // test fail with a file upload
        $imagePaths = [
            TESTS . 'assets' . DS . 'qr_images' . DS . '1' . DS . '1.jpg',
            TESTS . 'assets' . DS . 'qr_images' . DS . '1' . DS . 'dontexist.jpg',
        ];
        $images = $this->helperTestUploads($imagePaths, 'newimages', \UPLOAD_ERR_NO_FILE);

        // test success
        $this->post('https://localhost/admin/qr-images/add/1', [
            'newimages' => $images,
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/add');
        $this->helperTestFormTag('/admin/qr-images/add/1', 'post', true);
        $this->helperTestAlert('The images could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestString('<p class="text-danger">There was an issue with the file: ' .
            'tests_assets_qr_images_1_dontexist.jpg - 4</p>');
    }

    /**
     * Test add success
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::add()
     */
    public function testAddSuccess(): void
    {
        // test success with a file upload
        $imagePaths = [
            TESTS . 'assets' . DS . 'qr_images' . DS . '1' . DS . '1.jpg',
            TESTS . 'assets' . DS . 'qr_images' . DS . '1' . DS . '2.jpg',
        ];
        $images = $this->helperTestUploads($imagePaths, 'newimages');

        // test success
        $this->post('https://localhost/admin/qr-images/add/1', [
            'newimages' => $images,
        ]);
        $this->assertRedirectEquals('https://localhost/admin/qr-images/qr-code/1');
        $this->assertFlashMessage('The images have been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     * TODO: expend testing, especially when trying to change the image/file.
     * labels: frontend, templates, form, upload
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::edit()
     */
    public function testEdit(): void
    {
        // test fail
        $this->put('https://localhost/admin/qr-images/edit/2', [
            'name' => '',
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Admin/QrImages/edit');
        $this->helperTestFormTag('/admin/qr-images/edit/2', 'put');
        $this->helperTestAlert('The image could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This field cannot be left empty', 'name-error');

        // test success
        $this->put('https://localhost/admin/qr-images/edit/2', [
            'name' => 'New Image',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/qr-images/qr-code/1');
        $this->assertFlashMessage('The image has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
