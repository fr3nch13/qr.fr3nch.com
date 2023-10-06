<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Tags;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\TagsController Test Case
 *
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\TagsController
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
     * @uses \App\Controller\TagsController::add()
     */
    public function testAdd(): void
    {
        // test failed
        $this->post('https://localhost/tags/add', [
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/add');
        $this->helperTestFormTag('/tags/add');
        $this->helperTestAlert('The tag could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This field is required', 'name-error');

        // test success
        $this->post('https://localhost/tags/add', [
            'name' => 'new tag',
        ]);
        $this->assertRedirectEquals('https://localhost/tags/view/6');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\TagsController::edit()
     */
    public function testEdit(): void
    {
        // test fail
        $this->put('https://localhost/tags/edit/1', [
            'name' => 'Amazon', // an existing record
        ]);
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/edit');
        $this->helperTestFormTag('/tags/edit/1', 'put');
        $this->helperTestAlert('The tag could not be saved. Please, try again.', 'danger');
        // test to make sure the fields that are required are actually tagged as so.
        $this->helperTestFormFieldError('This Tag already exists.', 'name-error');

        // test put success
        $this->put('https://localhost/tags/edit/1', [
            'name' => 'New Tag',
            'description' => 'The Description',
        ]);
        $this->assertRedirectEquals('https://localhost/tags/view/1');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
