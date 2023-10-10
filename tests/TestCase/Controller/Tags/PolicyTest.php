<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Tags;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\TagsController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\TagsController
 */
class PolicyTest extends BaseControllerTest
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
    }

    /**
     * Test missing action
     *
     * @alert Keep the https://localhost/ as the HttpsEnforcerMiddleware will try to redirect.
     * @return void
     */
    public function testDontexist(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/tags/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\TagsController::dontexist()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/tags/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\TagsController::dontexist()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/tags/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\TagsController::dontexist()`');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/tags/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/tags/dontexist');
        Configure::write('debug', true);
    }

    /**
     * Test index method
     *
     * Anyone can view the list of Tags.
     *
     * @return void
     * @uses \App\Controller\TagsController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->loginGuest();
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/index');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/index');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/index');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/tags');
        $this->assertResponseOk();
        $this->helperTestTemplate('Tags/index');
        Configure::write('debug', true);
    }
}
