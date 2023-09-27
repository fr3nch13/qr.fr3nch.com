<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Tags;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\TagsController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax, and json.
 *
 * @uses \App\Controller\TagsController
 */
class ViewTest extends BaseControllerTest
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
     * @uses \App\Controller\TagsController::index()
     */
    public function testIndexNormal(): void
    {
        // not logged in
        $this->get('/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test with reqular
        $this->loginUserRegular();
        $this->get('/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test with admin
        $this->loginUserAdmin();
        $this->get('/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\TagsController::index()
     */
    public function testIndexAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\TagsController::view()
     */
    public function testViewNormal(): void
    {
        // not logged in
        $this->get('/tags/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test with reqular
        $this->loginUserRegular();
        $this->get('/tags/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test with admin
        $this->loginUserAdmin();
        $this->get('/tags/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\TagsController::view()
     */
    public function testViewAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('/tags/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/tags/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/tags/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\TagsController::add()
     */
    public function testAddNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/tags/add');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/tags/add');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\TagsController::add()
     */
    public function testAddAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/tags/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/tags/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\TagsController::edit()
     */
    public function testEditNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/tags/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/tags/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\TagsController::edit()
     */
    public function testEditAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/tags/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/tags/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }
}
