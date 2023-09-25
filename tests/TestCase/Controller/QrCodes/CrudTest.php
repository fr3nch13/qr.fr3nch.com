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
class CrudTest extends TestCase
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
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // get
        $this->get('/qr-codes');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes index content">');
        $this->assertResponseContains('<h3>Qr Codes</h3>');

        // post
        $this->post('/qr-codes');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/qr-codes');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/qr-codes');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/qr-codes');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
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
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/qr-codes/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes view content">');
        $this->assertResponseContains('<h3>Sow &amp; Scribe</h3>');

        // post
        $this->post('/qr-codes/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/qr-codes/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/qr-codes/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/qr-codes/view/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
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
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/qr-codes/add');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/qr-codes/add">');
        $this->assertResponseContains('<legend>Add Qr Code</legend>');

        // post
        $this->post('/qr-codes/add', [
            'key' => 'newqrcode',
            'name' => 'New QrCode',
            'description' => 'The Description',
            'url' => 'https://amazon.com/path/to/details/page/newqrcode',
            'bitly_id' => 'newqrcode',
            'source_id' => 1,
            'user_id' => 1,
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/qr-codes');
        $this->assertFlashMessage('The qr code has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // patch
        $this->patch('/qr-codes/add', [
            'key' => 'newqrcode',
            'name' => 'New QrCode',
            'description' => 'The Description',
            'url' => 'https://amazon.com/path/to/details/page/newqrcode',
            'bitly_id' => 'newqrcode',
            'source_id' => 1,
            'user_id' => 1,
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/qr-codes/add', [
            'key' => 'newqrcode',
            'name' => 'New QrCode',
            'description' => 'The Description',
            'url' => 'https://amazon.com/path/to/details/page/newqrcode',
            'bitly_id' => 'newqrcode',
            'source_id' => 1,
            'user_id' => 1,
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/qr-codes/add');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
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
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/qr-codes/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/qr-codes/edit/1">');
        $this->assertResponseContains('<legend>Edit Qr Code</legend>');

        // post
        $this->post('/qr-codes/edit/1', [
            'name' => 'Edited QrCode',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/qr-codes/edit/1', [
            'name' => 'Edited QrCode',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/qr-codes');
        $this->assertFlashMessage('The qr code has been saved.', 'flash');
        $this->assertFlashElement('flash/success');

        // put
        $this->put('/qr-codes/edit/1', [
            'name' => 'Edited QrCode',
        ]);
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/qr-codes/edit/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::delete()
     */
    public function testDelete(): void
    {
        Configure::write('debug', true);
        $this->enableRetainFlashMessages();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();

        // test get
        $this->get('/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // post
        $this->post('/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('/qr-codes/delete/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('/qr-codes/delete/1');
        $this->assertFlashMessage('The qr code has been deleted.', 'flash');
        $this->assertFlashElement('flash/success');
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/qr-codes');
    }
}
