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
 * Tests that the forms are working properly,
 * and displaying the proper information to the end user.
 * Especially on an error.
 *
 * @uses \App\Controller\QrCodesController
 */
class FormsTest extends TestCase
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
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->loginUserAdmin();
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::add()
     */
    public function testAdd(): void
    {
        // test failed
        $this->post('/qr-codes/add', [
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The qr code could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="post" accept-charset="utf-8" action="/qr-codes/add">');
        $this->assertResponseContains('<legend>Add Qr Code</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('id="qrkey-error"');
        $this->assertResponseContains('id="name-error"');
        $this->assertResponseContains('id="description-error"');
        $this->assertResponseContains('id="url-error"');
        $this->assertResponseContains('id="bitly-id-error"');
        $this->assertResponseContains('id="source-id-error"');
        // user is added in the controller, so no form element for it.

        // test success
        $this->post('/qr-codes/add', [
            'qrkey' => 'newqrcode',
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
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::edit()
     */
    public function testEdit(): void
    {
        // test fail
        $this->patch('/qr-codes/edit/1', [
            'qrkey' => 'witchinghour', // an existing record
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The qr code could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/qr-codes/edit/1">');
        $this->assertResponseContains('<legend>Edit Qr Code</legend>');
        $this->assertResponseContains('<div class="error-message" id="qrkey-error">This Key already exists.</div>');

        // a bad key
        $this->patch('/qr-codes/edit/1', [
            'qrkey' => 'witching hour', // an existing record
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('<div class="message error" onclick="this.classList.add(\'hidden\');">The qr code could not be saved. Please, try again.</div>');
        $this->assertResponseContains('<div class="qrCodes form content">');
        $this->assertResponseContains('<form method="patch" accept-charset="utf-8" action="/qr-codes/edit/1">');
        $this->assertResponseContains('<legend>Edit Qr Code</legend>');
        // test to make sure the fields that are required are actually tagged as so.
        $this->assertResponseContains('<div class="error-message" id="qrkey-error">Value cannot have a space in it.</div>');

        // test success
        $this->patch('/qr-codes/edit/1', [
            'name' => 'New Category',
            'description' => 'The Description',
        ]);
        $this->assertRedirect();
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/qr-codes');
        $this->assertFlashMessage('The qr code has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
