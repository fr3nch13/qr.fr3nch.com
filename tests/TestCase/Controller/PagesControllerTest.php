<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\TestSuite\Constraint\Response\StatusCode;

/**
 * PagesControllerTest class
 *
 * @uses \App\Controller\PagesController
 */
class PagesControllerTest extends BaseControllerTest
{
    /**
     * testDisplay method
     *
     * @return void
     */
    public function testDisplay()
    {
        $this->get('https://localhost/pages/about');

        $this->assertResponseOk();
        $this->helperTestLayoutPagesView();
        $this->helperTestTemplate('Pages/about');
    }

    /**
     * testDisplay method
     *
     * / routes to QrCodesController::index()
     *
     * @return void
     */
    public function testDisplayRootRoute()
    {
        $this->get('https://localhost/');

        $this->assertResponseOk();
        $this->helperTestLayoutPagesIndex();
        $this->helperTestTemplate('QrCodes/index');
    }

    /**
     * testDisplay method
     *
     * @return void
     */
    public function testDisplaySubPage()
    {
        $this->get('https://localhost/pages/about/staff');

        $this->assertResponseOk();
        $this->helperTestLayoutPagesGeneric();
        $this->helperTestTemplate('Pages/about/staff');
    }

    /**
     * testDisplay method
     *
     * @return void
     */
    public function testDisplayDirectly()
    {
        $this->get('https://localhost/pages');

        $this->assertRedirectEquals('https://localhost/');
    }

    /**
     * testDisplay method Logged in user
     *
     * @return void
     */
    public function testDisplayLoggedIn()
    {
        $this->loginUserAdmin();

        $this->get('https://localhost/pages/index');

        $this->assertResponseOk();
        $this->helperTestLayoutPagesGeneric();
        $this->helperTestTemplate('Pages/index');
    }

    /**
     * Test that missing template renders 404 page in production
     *
     * @return void
     */
    public function testMissingTemplate()
    {
        Configure::write('debug', false);
        $this->get('https://localhost/pages/not_existing');
        $this->assertResponseCode(404);
        $this->helperTestError400('/pages/not_existing');
    }

    /**
     * Test that missing template in debug mode renders missing_template error page
     *
     * @return void
     */
    public function testMissingTemplateInDebug()
    {
        Configure::write('debug', true);

        $this->get('https://localhost/pages/not_existing');
        $this->assertResponseCode(500);

        $this->assertResponseContains('Missing Template');
        $this->assertResponseContains('stack-frames');
        $this->assertResponseContains('not_existing.php');
    }

    /**
     * Test directory traversal protection
     *
     * @return void
     */
    public function testDirectoryTraversalProtection()
    {
        $this->get('https://localhost/pages/../Layout/ajax');

        $this->assertResponseCode(403);
        $this->assertResponseContains('Forbidden');
    }

    /**
     * Test that CSRF protection is applied to page rendering.
     *
     * @return void
     */
    public function testCsrfAppliedError()
    {
        $this->post('https://localhost/pages/home', ['hello' => 'world']);

        $this->assertResponseCode(403);
        $this->assertResponseContains('CSRF');
    }

    /**
     * Test that CSRF protection is applied to page rendering.
     *
     * @return void
     */
    public function testCsrfAppliedOk()
    {
        $this->enableCsrfToken();
        Configure::write('debug', true);

        $this->post('https://localhost/pages/home', ['hello' => 'world']);

        $this->assertThat(403, $this->logicalNot(new StatusCode($this->_response)));
        $this->assertResponseContains('`_Token` was not found in request data.');
    }
}
