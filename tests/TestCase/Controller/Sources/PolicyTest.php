<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Sources;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;
use Cake\Routing\Router;

/**
 * App\Controller\SourcesController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\SourcesController
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
    }

    /**
     * Test missing action
     *
     * @alert Keep the https://localhost/ as the HttpsEnforcerMiddleware will try to redirect.
     *
     * @return void
     * @uses \App\Controller\SourcesController::index()
     */
    public function testDontexist(): void
    {
        // not logged in
        $this->get('https://localhost/sources/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\SourcesController::dontexist()`');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/sources/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\SourcesController::dontexist()`');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/sources/dontexist');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Error: Missing Action `App\Controller\SourcesController::dontexist()`');

        // test with debug off
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/sources/dontexist');
        $this->assertResponseCode(404);
        $this->helperTestError400('/sources/dontexist');
        Configure::write('debug', true);
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\SourcesController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->get('https://localhost/sources');
        $this->assertRedirectEquals('users/login?redirect=%2Fsources');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/sources');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="sources index content">');
        $this->assertResponseContains('<h3>Sources</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/sources');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="sources index content">');
        $this->assertResponseContains('<h3>Sources</h3>');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\SourcesController::view()
     */
    public function testView(): void
    {
        // not logged in
        $this->get('https://localhost/sources/view/1');
        $this->assertRedirectEquals('users/login?redirect=%2Fsources%2Fview%2F1');

        // test with admin
        $this->loginUserAdmin();
        $this->get('https://localhost/sources/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="sources view content">');
        $this->assertResponseContains('<h3>Amazon</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('https://localhost/sources/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="sources view content">');
        $this->assertResponseContains('<h3>Amazon</h3>');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('https://localhost/sources/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'Sources',
            'action' => 'view',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\SourcesController::add()
     */
    public function testAdd(): void
    {
        // not logged in, so should redirect
        $this->get('https://localhost/sources/add');
        $this->assertRedirectEquals('users/login?redirect=%2Fsources%2Fadd');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/sources/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="sources form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" role="form" action="/sources/add">');
        $this->assertResponseContains('<legend>Add Source</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/sources/add');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fsources%2Fadd');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\SourcesController::edit()
     */
    public function testEdit(): void
    {
        // not logged in, so should redirect
        $this->get('https://localhost/sources/edit');
        $this->assertRedirectEquals('users/login?redirect=%2Fsources%2Fedit');

        // test with missing id and debug
        $this->loginUserAdmin();
        $this->get('https://localhost/sources/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('https://localhost/sources/edit');
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'Sources',
            'action' => 'edit',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/sources/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="sources form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" role="form" action="/sources/edit/1">');
        $this->assertResponseContains('<legend>Edit Source</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/sources/edit/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fsources%2Fedit%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\SourcesController::delete()
     */
    public function testDelete(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        // not logged in, missing id
        $this->get('https://localhost/sources/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test get with missing id and debug
        $this->loginUserAdmin();
        $this->get('https://localhost/sources/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get(Router::url([
            '_https' => true,
            'controller' => 'Sources',
            'action' => 'delete',
        ]));
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
        Configure::write('debug', true); // turn it back on

        // test get with reqular, get
        $this->loginUserRegular();
        $this->get('https://localhost/sources/delete/1');
        $this->assertRedirectEquals('https://localhost/?redirect=%2Fsources%2Fdelete%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with regular, post
        $this->loginUserRegular();
        $this->post('https://localhost/sources/delete/1');
        $this->assertRedirectEquals('https://localhost/sources');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test delete with regular user
        $this->loginUserRegular();
        $this->delete('https://localhost/sources/delete/1');
        $this->assertRedirectEquals('https://localhost/sources');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with admin, get
        $this->loginUserAdmin();
        $this->post('https://localhost/sources/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test with admin, post no data, no CSRF
        $this->loginUserAdmin();
        $this->delete('https://localhost/sources/delete/1');
        $this->assertRedirectEquals('sources');
        $this->assertFlashMessage('The source `Amazon` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
