<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Tags;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\TagsController Test Case
 *
 * Tests that the proper http method is being used.
 *
 * @uses \App\Controller\TagsController
 */
class CrudTest extends BaseControllerTest
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
     * Test index method
     *
     * @return void
     * @uses \App\Controller\TagsController::index()
     */
    public function testIndex(): void
    {
        // get
        $this->get('http://localhost:8080/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/index');

        // post
        $this->post('http://localhost:8080/tags');
        $this->assertRedirectEquals('/tags');
        // changed because we added friendsofcake/search
        // which does a Post-Redirect-Get
        // $this->assertResponseCode(405);
        // $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('http://localhost:8080/tags');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('http://localhost:8080/tags');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('http://localhost:8080/tags');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }
}
