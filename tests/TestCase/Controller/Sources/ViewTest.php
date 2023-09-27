<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Sources;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\SourcesController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax, and json.
 *
 * @uses \App\Controller\SourcesController
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
     * @uses \App\Controller\SourcesController::index()
     */
    public function testIndexNormal(): void
    {
        // not logged in
        $this->get('/sources');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fsources');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/sources');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test with admin
        $this->loginUserAdmin();
        $this->get('/sources');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\SourcesController::index()
     */
    public function testIndexAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('/sources');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fsources');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/sources');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/sources');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\SourcesController::view()
     */
    public function testViewNormal(): void
    {
        // not logged in
        $this->get('/sources/view/1');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fsources%2Fview%2F1');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/sources/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();

        // test with admin
        $this->loginUserAdmin();
        $this->get('/sources/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\SourcesController::view()
     */
    public function testViewAjax(): void
    {
        // not logged in
        $this->requestAsAjax();
        $this->get('/sources/view/1');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fsources%2Fview%2F1');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/sources/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/sources/view/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\SourcesController::add()
     */
    public function testAddNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/sources/add');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `add` on `App\Model\Entity\Source`.');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/sources/add');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\SourcesController::add()
     */
    public function testAddAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/sources/add');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `add` on `App\Model\Entity\Source`.');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/sources/add');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\SourcesController::edit()
     */
    public function testEditNormal(): void
    {
        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/sources/edit/1');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `edit` on `App\Model\Entity\Source`.');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/sources/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutNormal();
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\SourcesController::edit()
     */
    public function testEditAjax(): void
    {
        // test with reqular, get
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('/sources/edit/1');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `edit` on `App\Model\Entity\Source`.');

        // test with admin, get
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('/sources/edit/1');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
    }
}
