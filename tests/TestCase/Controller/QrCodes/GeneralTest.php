<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrCodes;

use App\Model\Table\QrCodesTable;
use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;
use Cake\I18n\DateTime;

/**
 * App\Controller\QrCodesController Test Case
 *
 * Tests the other aspects of the controller
 *
 * @uses \App\Controller\QrCodesController
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
     * @uses \App\Controller\QrCodesController::forward()
     */
    public function testForward(): void
    {
        $this->get('https://localhost/?k=sownscribe');
        $this->assertRedirectEquals('https://localhost/f/sownscribe');

        $this->get('https://localhost/f/sownscribe');
        $this->assertRedirectEquals('https://amazon.com/path/to/details/page');

        $this->get('https://localhost/qr-codes/forward/sownscribe');
        $this->assertRedirectEquals('https://amazon.com/path/to/details/page');

        $this->get('https://localhost/?k=inactive');
        $this->assertRedirectEquals('https://localhost/f/inactive');

        $this->get('https://localhost/f/inactive');
        $this->assertRedirectEquals('https://localhost/');
        $this->assertFlashMessage('This QR Code is inactive.', 'flash');
        $this->assertFlashElement('flash/warning');

        $this->get('https://localhost/?k=dontexist');
        $this->assertRedirectEquals('https://localhost/f/dontexist');

        $this->get('https://localhost/f/dontexist');
        $this->assertRedirectEquals('https://localhost/');
        $this->assertFlashMessage('A QR Code with the key: `dontexist` could not be found.', 'flash');
        $this->assertFlashElement('flash/error');

        $this->get('https://localhost/f/');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unknown ID');
    }

    /**
     * Test to see if we get a hit recorded correctly.
     *
     * @return void
     * @uses \App\Controller\QrCodesController::forward()
     */
    public function testForwardHit(): void
    {
        $entity = $this->QrCodes->get(1);
        $this->assertSame(0, $entity->hits);
        $this->assertNull($entity->last_hit);

        $this->get('https://localhost/f/sownscribe');
        $this->assertRedirectEquals('https://amazon.com/path/to/details/page');

        $entity = $this->QrCodes->get(1);
        $this->assertSame(1, $entity->hits);
        $this->assertInstanceOf(DateTime::class, $entity->last_hit);

        $this->get('https://localhost/qr-codes/forward/sownscribe');
        $this->assertRedirectEquals('https://amazon.com/path/to/details/page');
        $entity = $this->QrCodes->get(1);
        $this->assertSame(2, $entity->hits);
        $this->assertInstanceOf(DateTime::class, $entity->last_hit);
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShow(): void
    {
        $this->get('https://localhost/qr-codes/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertSame('image/png', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShowInactive(): void
    {
        $this->get('https://localhost/qr-codes/show/4');
        $this->assertRedirectEquals('https://localhost/users/login?redirect=%2Fqr-codes%2Fshow%2F4');
        // from \App\Middleware\UnauthorizedHandler\CustomRedirectHandler
        $this->assertFlashMessage('You are not authorized to access that location', 'flash');
        $this->assertFlashElement('flash/error');

        $this->loginUserRegular();
        $this->get('https://localhost/qr-codes/show/4');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertSame('image/png', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::show()
     */
    public function testShowMissingImage(): void
    {
        $qrCode = $this->QrCodes->get(1);
        $originalPath = Configure::read('App.paths.qr_codes');

        $path = Configure::read('App.paths.qr_codes') . DS . $qrCode->id . '.png';
        $this->assertTrue(is_readable($path));

        Configure::write('App.paths.qr_codes', TMP . 'dontexist');
        $path = Configure::read('App.paths.qr_codes') . DS . $qrCode->id . '.png';

        $this->assertFalse(is_readable($path));

        $this->get('https://localhost/qr-codes/show/1');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unable to find the image file.');

        Configure::write('debug', false);
        $this->get('https://localhost/qr-codes/show/1');
        $this->assertResponseCode(404);
        $this->helperTestError400('/qr-codes/show/1');
        Configure::write('debug', true);
        Configure::write('App.paths.qr_codes', $originalPath);
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShowHeadersNoDebug(): void
    {
        // check cache policy when debug is off.
        Configure::write('debug', false);
        $this->get('https://localhost/qr-codes/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertTrue(isset($headers['Cache-Control']));
        $this->assertSame('public, max-age=3600', $headers['Cache-Control'][0]);
        $this->assertTrue(isset($headers['Last-Modified']));
        $this->assertSame('image/png', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
        Configure::write('debug', true);
    }
}
