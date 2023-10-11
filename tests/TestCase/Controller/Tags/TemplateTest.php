<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Tags;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\TagsController Test Case
 *
 * Tests that the templates are being used coreectly.
 * Specifically in requests for regular, ajax.
 *
 * TODO: Test specific HTML once templates are done.
 * labels: frontend, templates, tesing
 *
 * @uses \App\Controller\TagsController
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
     * @uses \App\Controller\TagsController::index()
     */
    public function testIndexNormal(): void
    {
        // not logged in
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('Tags/index');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('Tags/index');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('Tags/index');
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
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Tags/index');

        // test with reqular
        $this->requestAsAjax();
        $this->loginUserRegular();
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Tags/index');

        // test with admin
        $this->requestAsAjax();
        $this->loginUserAdmin();
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestLayoutAjax();
        $this->helperTestTemplate('Tags/index');
    }
}
