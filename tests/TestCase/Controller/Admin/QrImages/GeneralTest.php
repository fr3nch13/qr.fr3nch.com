<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\QrImages;

use App\Model\Table\QrImagesTable;
use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\QrImagesController Test Case
 *
 * Tests the other aspects of the controller
 *
 * @uses \App\Controller\Admin\QrImagesController
 */
class GeneralTest extends BaseControllerTest
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\QrImagesTable
     */
    protected $QrImages;

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
        $this->loginUserAdmin();

        $config = $this->getTableLocator()->exists('QrImages') ? [] : ['className' => QrImagesTable::class];
        /** @var \App\Model\Table\QrImagesTable $QrImages */
        $QrImages = $this->getTableLocator()->get('QrImages', $config);
        $this->QrImages = $QrImages;
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::show()
     */
    public function testShow(): void
    {
        $this->get('https://localhost/admin/qr-images/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
        $this->assertTrue(isset($headers['Last-Modified']));
        $this->assertNotNull($headers['Last-Modified'][0]);
    }

    /**
     * Test show thub method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShowThumbSm(): void
    {
        $this->get('https://localhost/admin/qr-images/show/1?thumb=sm');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertFalse(isset($headers['Content-Disposition']));
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
    }

    /**
     * Test show thumb method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShowThumbMd(): void
    {
        $this->get('https://localhost/admin/qr-images/show/1?thumb=md');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertFalse(isset($headers['Content-Disposition']));
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
    }

    /**
     * Test show thumb method
     *
     * @return void
     * @uses \App\Controller\QrCodesController::show()
     */
    public function testShowThumbLg(): void
    {
        $this->get('https://localhost/admin/qr-images/show/1?thumb=lg');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertFalse(isset($headers['Content-Disposition']));
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
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
        $this->get('https://localhost/admin/qr-images/show/1?download=1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertFalse(isset($headers['Cache-Control']));
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
        $this->assertSame('attachment; filename="Front Cover.jpg"', $headers['Content-Disposition'][0]);
        $this->assertSame('binary', $headers['Content-Transfer-Encoding'][0]);
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::show()
     */
    public function testShowMissingImageDebugOn(): void
    {
        $qrImage = $this->QrImages->get(1, contain: ['QrCodes']);
        $path = Configure::read('App.paths.qr_images') . DS . $qrImage->qr_code_id . DS . $qrImage->id . '.' . $qrImage->ext;
        $this->assertTrue(is_readable($path));

        unlink($path);
        $this->assertFalse(is_readable($path));

        $this->get('https://localhost/admin/qr-images/show/1');
        $this->assertResponseCode(404);
        $this->assertResponseContains('Unable to find the image file.');
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::show()
     */
    public function testShowMissingImageDebugOff(): void
    {
        $qrImage = $this->QrImages->get(1, contain: ['QrCodes']);
        $path = Configure::read('App.paths.qr_images') . DS . $qrImage->qr_code_id . DS . $qrImage->id . '.' . $qrImage->ext;
        $this->assertTrue(is_readable($path));

        unlink($path);
        $this->assertFalse(is_readable($path));

        Configure::write('debug', false);
        $this->get('https://localhost/admin/qr-images/show/1');
        $this->assertResponseCode(404);
        $this->helperTestError400('/admin/qr-images/show/1');
    }

    /**
     * Test show method
     *
     * @return void
     * @uses \App\Controller\Admin\QrImagesController::show()
     */
    public function testShowHeadersDebugOff(): void
    {
        // check cache policy when debug is off.
        Configure::write('debug', false);
        $this->get('https://localhost/admin/qr-images/show/1');
        $this->assertResponseOk();
        $this->assertResponseNotEmpty();
        $headers = $this->_response->getHeaders();
        $this->assertTrue(isset($headers['Cache-Control']));
        $this->assertSame('public, max-age=3600', $headers['Cache-Control'][0]);
        $this->assertTrue(isset($headers['Last-Modified']));
        $this->assertNotNull($headers['Last-Modified'][0]);
        $this->assertSame('image/jpeg', $headers['Content-Type'][0]);
        $this->assertGreaterThan(0, $headers['Content-Length'][0]);
    }
}
