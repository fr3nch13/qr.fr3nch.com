<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\QrCodes;

use App\Model\Table\QrCodesTable;
use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\QrCodesController Test Case
 *
 * Tests the other aspects of the controller
 *
 * @uses \App\Controller\Admin\QrCodesController
 */
class GeneralTest extends BaseControllerTest
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\QrCodesTable
     */
    protected $QrCodes;

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

        $config = $this->getTableLocator()->exists('QrCodes') ? [] : ['className' => QrCodesTable::class];
        /** @var \App\Model\Table\QrCodesTable $QrCodes */
        $QrCodes = $this->getTableLocator()->get('QrCodes', $config);
        $this->QrCodes = $QrCodes;
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::forward()
     */
    public function testForward(): void
    {
        $entity = $this->QrCodes->get(1);
        $this->assertSame(0, $entity->hits);
        // guest
        $this->get('https://localhost/admin/f/sownscribe');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Ff%2Fsownscribe');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');
        $entity = $this->QrCodes->get(1);
        $this->assertSame(0, $entity->hits);

        $this->get('https://localhost/admin/f/inactive');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Ff%2Finactive');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        $this->get('https://localhost/admin/f/dontexist');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Ff%2Fdontexist');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        $this->loginUserAdmin();

        $this->get('https://localhost/admin/f/sownscribe');
        $this->assertRedirectEquals('https://amazon.com/path/to/details/page');
        $entity = $this->QrCodes->get(1);
        $this->assertSame(0, $entity->hits);

        $this->get('https://localhost/admin/qr-codes/forward/sownscribe');
        $this->assertRedirectEquals('https://amazon.com/path/to/details/page');
        $entity = $this->QrCodes->get(1);
        $this->assertSame(0, $entity->hits);

        $this->get('https://localhost/admin/f/inactive');
        $this->assertRedirectEquals('https://google.com');

        $this->get('https://localhost/admin/qr-codes/forward/inactive');
        $this->assertRedirectEquals('https://google.com');

        $this->get('https://localhost/admin/f/dontexist');
        $this->assertRedirectEquals('https://localhost/admin/qr-codes');
        $this->assertFlashMessage('A QR Code with the key: `dontexist` could not be found.', 'flash');
        $this->assertFlashElement('flash/error');

        $this->get('https://localhost/admin/f/');
        $this->assertRedirectEquals('https://localhost/admin/qr-codes');
        $this->assertFlashMessage('No key was given.', 'flash');
        $this->assertFlashElement('flash/error');
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::show()
     */
    public function testShow(): void
    {
        // guest
        $this->get('https://localhost/admin/qr-codes/show/1');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fadmin%2Fqr-codes%2Fshow%2F1');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertSame('image/svg+xml', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/show/1?l=0');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertSame('image/svg+xml', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/show/1?l=1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertSame('image/svg+xml', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
    }

    /**
     * Test show thumb method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShowDark(): void
    {
        $this->loginUserAdmin();

        $this->get('https://localhost/admin/qr-codes/show/1?l=0');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertFalse(isset($headers['Content-Disposition']));
        $this->assertSame('image/svg+xml', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
    }

    /**
     * Test show thumb method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShowLight(): void
    {
        $this->loginUserAdmin();

        $this->get('https://localhost/admin/qr-codes/show/1?l=1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertFalse(isset($headers['Content-Disposition']));
        $this->assertSame('image/svg+xml', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
    }

    /**
     * Test show download method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShowDownload(): void
    {
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/show/1?download=1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertSame('image/svg+xml', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
        $this->assertSame('attachment; filename="QR-sownscribe-dark.svg"', $headers['Content-Disposition'][0]);
        $this->assertSame('binary', $headers['Content-Transfer-Encoding'][0]);

        // dark
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/show/1?l=0&download=1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertSame('image/svg+xml', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
        $this->assertSame('attachment; filename="QR-sownscribe-dark.svg"', $headers['Content-Disposition'][0]);
        $this->assertSame('binary', $headers['Content-Transfer-Encoding'][0]);

        // light
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/qr-codes/show/1?l=1&download=1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertSame('image/svg+xml', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
        $this->assertSame('attachment; filename="QR-sownscribe-light.svg"', $headers['Content-Disposition'][0]);
        $this->assertSame('binary', $headers['Content-Transfer-Encoding'][0]);
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::show()
     */
    public function testShowMissingImage(): void
    {
        $this->loginUserAdmin();

        $qrCode = $this->QrCodes->get(1);
        $originalPath = Configure::read('App.paths.qr_codes');

        $path = Configure::read('App.paths.qr_codes') . DS . $qrCode->id . '-light.svg';
        $this->assertTrue(is_readable($path));
        $path = Configure::read('App.paths.qr_codes') . DS . $qrCode->id . '-dark.svg';
        $this->assertTrue(is_readable($path));

        Configure::write('App.paths.qr_codes', TMP . 'dontexist');
        $path = Configure::read('App.paths.qr_codes') . DS . $qrCode->id . '-light.svg';
        $this->assertFalse(is_readable($path));
        $path = Configure::read('App.paths.qr_codes') . DS . $qrCode->id . '-dark.svg';
        $this->assertFalse(is_readable($path));

        $this->get('https://localhost/admin/qr-codes/show/1');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unable to find the image file.');

        Configure::write('debug', false);
        $this->get('https://localhost/admin/qr-codes/show/1');
        $this->assertResponseCode(404);
        $this->helperTestError400('/admin/qr-codes/show/1');
        Configure::write('debug', true);
        Configure::write('App.paths.qr_codes', $originalPath);
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\Admin\QrCodesController::show()
     */
    public function testShowHeadersNoDebug(): void
    {
        $this->loginUserAdmin();

        // check cache policy when debug is off.
        Configure::write('debug', false);

        $this->get('https://localhost/admin/qr-codes/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertTrue(isset($headers['Cache-Control']));
        $this->assertSame('public, max-age=3600', $headers['Cache-Control'][0]);
        $this->assertTrue(isset($headers['Last-Modified']));
        $this->assertSame('image/svg+xml', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // dark
        $this->get('https://localhost/admin/qr-codes/show/1?l=0');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertTrue(isset($headers['Cache-Control']));
        $this->assertSame('public, max-age=3600', $headers['Cache-Control'][0]);
        $this->assertTrue(isset($headers['Last-Modified']));
        $this->assertSame('image/svg+xml', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        // light
        $this->get('https://localhost/admin/qr-codes/show/1?l=1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertTrue(isset($headers['Cache-Control']));
        $this->assertSame('public, max-age=3600', $headers['Cache-Control'][0]);
        $this->assertTrue(isset($headers['Last-Modified']));
        $this->assertSame('image/svg+xml', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);

        Configure::write('debug', true);
    }
}
