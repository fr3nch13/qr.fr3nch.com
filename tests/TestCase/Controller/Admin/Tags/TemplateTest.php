<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Tags;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\TagsController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax.
 *
 * @uses \App\Controller\Admin\TagsController
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
     * @uses \App\Controller\Admin\TagsController::index()
     */
    public function testIndexNormal(): void
    {
        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardIndex();
        $this->helperTestTemplate('Admin/Tags/index');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardIndex();
        $this->helperTestTemplate('Admin/Tags/index');

        // validate the html
        $this->helperValidateHTML(true);
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::index()
     */
    public function testIndexAjax(): void
    {
        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Tags/index');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Tags/index');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::add()
     */
    public function testAddAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/tags/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Tags/add');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Tags/add');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::edit()
     */
    public function testEditNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/admin/tags/edit/4');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardForm();
        $this->helperTestTemplate('Admin/Tags/edit');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardForm();
        $this->helperTestTemplate('Admin/Tags/edit');

        // validate the html
        $this->helperValidateHTML(true);
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::edit()
     */
    public function testEditAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/tags/edit/4');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Tags/edit');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Tags/edit');
    }
}
