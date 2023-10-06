<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
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
        Configure::write('debug', true);

        $this->get('https://localhost/pages/about');

        $this->assertResponseOk();
        $this->assertResponseContains('About');
        $this->assertResponseContains('<html lang="en">');
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
        Configure::write('debug', true);

        $this->get('https://localhost/');

        $this->assertResponseOk();
        $this->assertResponseContains('QR Codes');
        $this->assertResponseContains('<html lang="en">');
    }

    /**
     * testDisplay method
     *
     * @return void
     */
    public function testDisplaySubPage()
    {
        Configure::write('debug', true);

        $this->get('https://localhost/pages/about/staff');

        $this->assertResponseOk();
        $this->helperTestLayoutPagesGeneric();
        $this->assertResponseContains('<!-- START: App.Pages/about/staff -->');
        $this->assertResponseContains('<!-- END: App.Pages/about/staff -->');
    }

    /**
     * testDisplay method
     *
     * @return void
     */
    public function testDisplayDirectly()
    {
        Configure::write('debug', true);

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
        Configure::write('debug', true);
        $this->loginUserAdmin();

        $this->get('https://localhost/pages/index');

        $this->assertResponseOk();
        $this->assertResponseContains('Index');
        $this->assertResponseContains('<html lang="en">');
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

        $this->assertResponseFailure();
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
        Configure::write('debug', true);
        $this->enableCsrfToken();

        $this->post('https://localhost/pages/home', ['hello' => 'world']);

        $this->assertThat(403, $this->logicalNot(new StatusCode($this->_response)));
        $this->assertResponseContains('`_Token` was not found in request data.');
    }
}
