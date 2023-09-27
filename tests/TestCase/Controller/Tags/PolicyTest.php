<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Tags;

use App\Test\TestCase\Controller\LoggedInTrait;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\TagsController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\TagsController
 */
class PolicyTest extends TestCase
{
    use IntegrationTestTrait;

    use LoggedInTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
        'app.Categories',
        'app.Sources',
        'app.QrCodes',
        'app.CategoriesQrCodes',
        'app.Tags',
        'app.QrCodesTags',
    ];

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
     * @uses \App\Controller\TagsController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->get('/tags');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Ftags');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/tags');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="tags index content">');
        $this->assertResponseContains('<h3>Tags</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/tags');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="tags index content">');
        $this->assertResponseContains('<h3>Tags</h3>');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\TagsController::view()
     */
    public function testView(): void
    {
        // not logged in
        $this->get('/tags/view/1');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Ftags%2Fview%2F1');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/tags/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="tags view content">');
        $this->assertResponseContains('<h3>Notebook</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/tags/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="tags view content">');
        $this->assertResponseContains('<h3>Notebook</h3>');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('/tags/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Record not found in table `tags`.');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get('/tags/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Not Found');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\TagsController::add()
     */
    public function testAdd(): void
    {
        // not logged in, so should redirect
        $this->get('/tags/add');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Ftags%2Fadd');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/tags/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="tags form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/tags/add">');
        $this->assertResponseContains('<legend>Add Tag</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/tags/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="tags form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/tags/add">');
        $this->assertResponseContains('<legend>Add Tag</legend>');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\TagsController::edit()
     */
    public function testEdit(): void
    {
        // not logged in, so should redirect
        $this->get('/tags/edit');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Ftags%2Fedit');

        // test with missing id and debug
        $this->loginUserAdmin();
        $this->get('/tags/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Record not found in table `tags`.');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/tags/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="tags form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/tags/edit/1">');
        $this->assertResponseContains('<legend>Edit Tag</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/tags/edit/1');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `edit` on `App\Model\Entity\Tag`.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\TagsController::delete()
     */
    public function testDelete(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        // not logged in, so should redirect
        $this->get('/tags/delete');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Ftags%2Fdelete');

        // test get with missing id and debug
        $this->loginUserAdmin();
        $this->get('/tags/delete');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test get with reqular, get
        $this->loginUserRegular();
        $this->get('/tags/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test post with regular, post
        $this->loginUserRegular();
        $this->post('/tags/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test delete with regular user
        $this->loginUserRegular();
        $this->delete('/tags/delete/1');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `delete` on `App\Model\Entity\Tag`.');

        // test post with admin, get
        $this->loginUserAdmin();
        $this->post('/tags/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test with admin, post no data, no CSRF
        $this->loginUserAdmin();
        $this->delete('/tags/delete/1');
        $this->assertFlashMessage('The tag has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/tags');
    }
}
