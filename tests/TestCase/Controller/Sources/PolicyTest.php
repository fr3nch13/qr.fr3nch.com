<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Sources;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

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
     * Test index method
     *
     * @return void
     * @uses \App\Controller\SourcesController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->get('/sources');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fsources');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/sources');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="sources index content">');
        $this->assertResponseContains('<h3>Sources</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/sources');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/?redirect=%2Fsources');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
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
        $this->get('/sources/view/1');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fsources%2Fview%2F1');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/sources/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="sources view content">');
        $this->assertResponseContains('<h3>Amazon</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/sources/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="sources view content">');
        $this->assertResponseContains('<h3>Amazon</h3>');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('/sources/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get('/sources/view');
        $this->assertResponseCode(500);
        // TODO: This should apply a check `/sources/view`
        // Should also throw a 404, instead of a 500
        // labels: policy, response code
        $this->assertResponseContains('The request to `/sources/view` did not apply any authorization checks.');
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
        $this->get('/sources/add');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fsources%2Fadd');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/sources/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="sources form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/sources/add">');
        $this->assertResponseContains('<legend>Add Source</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/sources/add');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/?redirect=%2Fsources%2Fadd');
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
        $this->get('/sources/edit');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fsources%2Fedit');

        // test with missing id and debug
        $this->loginUserAdmin();
        $this->get('/sources/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserAdmin();
        $this->get('/sources/edit');
        $this->assertResponseCode(500);
        // TODO: This should apply a check `/sources/edit`
        // Should also throw a 404, instead of a 500
        // labels: policy, response code
        $this->assertResponseContains('The request to `/sources/edit` did not apply any authorization checks.');
        Configure::write('debug', true); // turn it back on

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/sources/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="sources form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/sources/edit/1">');
        $this->assertResponseContains('<legend>Edit Source</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/sources/edit/1');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/?redirect=%2Fsources%2Fedit%2F1');
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

        // not logged in, so should redirect
        $this->get('/sources/delete');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fsources%2Fdelete');

        // test get with missing id and debug
        $this->loginUserAdmin();
        $this->get('/sources/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test get with reqular, get
        $this->loginUserRegular();
        $this->get('/sources/delete/1');
        $this->assertRedirectContains('/?redirect=%2Fsources%2Fdelete%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with regular, post
        $this->loginUserRegular();
        $this->post('/sources/delete/1');
        $this->assertRedirectContains('/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test delete with regular user
        $this->loginUserRegular();
        $this->delete('/sources/delete/1');
        $this->assertRedirectContains('/');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        // test post with admin, get
        $this->loginUserAdmin();
        $this->post('/sources/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test with admin, delete, no ID
        $this->loginUserAdmin();
        $this->delete('/sources/delete');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');

        // test with admin, post no data, no CSRF
        $this->loginUserAdmin();
        $this->delete('/sources/delete/1');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/sources');
        $this->assertFlashMessage('The source `Amazon` has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
