<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrCodes;

use App\Test\TestCase\Controller\LoggedInTrait;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\QrCodesController Test Case
 *
 * @uses \App\Controller\QrCodesController
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
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::index()
     */
    public function testIndex(): void
    {
        Configure::write('debug', true);

        // not logged in
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes index content">');
        $this->assertResponseContains('<h3>Qr Codes</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/qr-codes');

        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes index content">');
        $this->assertResponseContains('<h3>Qr Codes</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/qr-codes');

        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes index content">');
        $this->assertResponseContains('<h3>Qr Codes</h3>');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::view()
     */
    public function testView(): void
    {
        Configure::write('debug', true);

        // not logged in
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes view content">');
        $this->assertResponseContains('<h3>Sow &amp; Scribe</h3>');

        // test with admin
        $this->loginUserAdmin();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes view content">');
        $this->assertResponseContains('<h3>Sow &amp; Scribe</h3>');

        // test with reqular
        $this->loginUserRegular();
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes view content">');
        $this->assertResponseContains('<h3>Sow &amp; Scribe</h3>');

        // test with missing id and debug
        $this->loginUserRegular();
        $this->get('/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Record not found in table `qr_codes`.');

        // test with missing id, no debug
        Configure::write('debug', false);
        $this->loginUserRegular();
        $this->get('/qr-codes/view');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Not Found');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::add()
     */
    public function testAdd(): void
    {
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();

        // not logged in, so should redirect
        $this->get('/qr-codes/add');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fqr-codes%2Fadd');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/qr-codes/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/qr-codes/add">');
        $this->assertResponseContains('<legend>Add Qr Code</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/qr-codes/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/qr-codes/add">');
        $this->assertResponseContains('<legend>Add Qr Code</legend>');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::edit()
     */
    public function testEdit(): void
    {
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();

        // not logged in, so should redirect
        $this->get('/qr-codes/edit');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fqr-codes%2Fedit');

        // test with missing id and debug
        $this->loginUserAdmin();
        $this->get('/qr-codes/edit');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Record not found in table `qr_codes`.');

        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/qr-codes/edit/1">');
        $this->assertResponseContains('<legend>Edit Qr Code</legend>');

        // test with reqular, get
        $this->loginUserRegular();
        $this->get('/qr-codes/edit/1');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `edit` on `App\Model\Entity\QrCode`.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::delete()
     */
    public function testDelete(): void
    {
        Configure::write('debug', true); // needed for the Csrf/Security to properly mock.
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->enableRetainFlashMessages();

        // not logged in, so should redirect
        $this->get('/qr-codes/delete');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/users/login?redirect=%2Fqr-codes%2Fdelete');

        // test get with missing id and debug
        $this->loginUserAdmin();
        $this->get('/qr-codes/delete');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test get with reqular, get
        $this->loginUserRegular();
        $this->get('/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test post with regular, post
        $this->loginUserRegular();
        $this->post('/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test delete with regular user
        $this->loginUserRegular();
        $this->delete('/qr-codes/delete/1');
        $this->assertResponseCode(403);
        $this->assertResponseContains('Error: Identity is not authorized to perform `delete` on `App\Model\Entity\QrCode`.');

        // test post with admin, get
        $this->loginUserAdmin();
        $this->post('/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // test with admin, post no data, no CSRF
        $this->loginUserAdmin();
        $this->delete('/qr-codes/delete/1');
        $this->assertFlashMessage('The qr code has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/qr-codes');
    }
}
