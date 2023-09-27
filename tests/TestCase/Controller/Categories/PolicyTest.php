<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Categories;

use App\Test\TestCase\Controller\LoggedInTrait;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\CategoriesController Test Case
 *
 * Tests the the policies are correct, and are being properly applied.
 *
 * @uses \App\Controller\CategoriesController
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
        'app.Sources',
        'app.Categories',
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
     * @uses \App\Controller\CategoriesController::index()
     */
    public function testIndex(): void
    {
        // not logged in
        $this->get('/categories');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories index content">');
        $this->assertResponseContains('<h3>Categories</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/categories');

        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories index content">');
        $this->assertResponseContains('<h3>Categories</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/categories');

        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories index content">');
        $this->assertResponseContains('<h3>Categories</h3>');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::view()
     */
    public function testView(): void
    {
        // not logged in
        $this->get('/categories/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories view content">');
        $this->assertResponseContains('<h3>Books</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/categories/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories view content">');
        $this->assertResponseContains('<h3>Books</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/categories/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories view content">');
        $this->assertResponseContains('<h3>Books</h3>');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('/categories/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Record not found in table `categories`.');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get('/categories/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Not Found');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::add()
     */
    public function testAdd(): void
    {
        // not logged in, so should redirect
        $this->get('/categories/add');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fcategories%2Fadd');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/categories/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/categories/add">');
        $this->assertResponseContains('<legend>Add Category</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/categories/add');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `add` on `App\Model\Entity\Category`.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::edit()
     */
    public function testEdit(): void
    {
        // not logged in, so should redirect
        $this->get('/categories/edit');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fcategories%2Fedit');

        // test with missing id and debug
        $this->loginUserAdmin();
        $this->get('/categories/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Record not found in table `categories`.');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/categories/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="categories form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/categories/edit/1">');
        $this->assertResponseContains('<legend>Edit Category</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/categories/edit/1');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `edit` on `App\Model\Entity\Category`.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::delete()
     */
    public function testDelete(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        // not logged in, so should redirect
        $this->get('/categories/delete');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fcategories%2Fdelete');

        // test get with missing id and debug
        $this->loginUserAdmin();
        $this->get('/categories/delete');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test get with reqular, get
        $this->loginUserRegular();
        $this->get('/categories/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test post with regular, post
        $this->loginUserRegular();
        $this->post('/categories/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test delete with regular user
        $this->loginUserRegular();
        $this->delete('/categories/delete/1');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `delete` on `App\Model\Entity\Category`.');

        // test post with admin, get
        $this->loginUserAdmin();
        $this->post('/categories/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test with admin, post no data, no CSRF
        $this->loginUserAdmin();
        $this->delete('/categories/delete/1');
        $this->assertFlashMessage('The category has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/categories');
    }
}
