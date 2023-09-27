<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\UsersController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax, and json.
 *
 * @uses \App\Controller\UsersController
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
     * @uses \App\Controller\UsersController::index()
     */
    public function testIndexNormal(): void
    {
        // not logged in
        $this->get('/users');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fusers');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/users');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test with admin
        $this->loginUserAdmin();
        $this->get('/users');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\UsersController::index()
     */
    public function testIndexAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('/users');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fusers');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/users');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/users');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\UsersController::view()
     */
    public function testViewNormal(): void
    {
        // not logged in
        $this->get('/users/view/1');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fusers%2Fview%2F1');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/users/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test with admin
        $this->loginUserAdmin();
        $this->get('/users/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\UsersController::view()
     */
    public function testViewAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('/users/view/1');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fusers%2Fview%2F1');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/users/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/users/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\UsersController::add()
     */
    public function testAddNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/users/add');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `add` on `App\Model\Entity\User`.');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/users/add');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\UsersController::add()
     */
    public function testAddAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/users/add');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `add` on `App\Model\Entity\User`.');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/users/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\UsersController::edit()
     */
    public function testEditNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/users/edit/1');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `edit` on `App\Model\Entity\User`.');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/users/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\UsersController::edit()
     */
    public function testEditAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/users/edit/1');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `edit` on `App\Model\Entity\User`.');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/users/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }
}
