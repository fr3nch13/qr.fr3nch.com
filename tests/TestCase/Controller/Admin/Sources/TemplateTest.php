<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Sources;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\SourcesController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax.
 *
 * TODO: Test specific HTML once templates are done.
 * labels: frontend, templates, tesing
 *
 * @uses \App\Controller\Admin\SourcesController
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
     * @uses \App\Controller\Admin\SourcesController::index()
     */
    public function testIndexNormal(): void
    {
        // not logged in
        $this->get('https://localhost/admin/sources');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fsources');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('Admin/Sources/index');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('Admin/Sources/index');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::index()
     */
    public function testIndexAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('https://localhost/admin/sources');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fsources');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Sources/index');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Sources/index');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::view()
     */
    public function testViewNormal(): void
    {
        // not logged in
        $this->get('https://localhost/admin/sources/view/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fsources%2Fview%2F1');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/Sources/view');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardView();
        $this->helperTestTemplate('Admin/Sources/view');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::view()
     */
    public function testViewAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('https://localhost/admin/sources/view/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fsources%2Fview%2F1');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Sources/view');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Sources/view');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::add()
     */
    public function testAddNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources/add');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fsources%2Fadd');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/add');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardForm();
        $this->helperTestTemplate('Admin/Sources/add');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::add()
     */
    public function testAddAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources/add');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fsources%2Fadd');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Sources/add');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::edit()
     */
    public function testEditNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources/edit/1');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fsources%2Fedit%2F1');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutDashboardForm();
        $this->helperTestTemplate('Admin/Sources/edit');

        // validate the html
        $this->helperValidateHTML();
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::edit()
     */
    public function testEditAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/admin/sources/edit/1');
        $this->assertRedirectEquals('https://localhost/admin?redirect=%2Fadmin%2Fsources%2Fedit%2F1');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Admin/Sources/edit');
    }
}
