<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrImagesController Test Case
 *
 * Tests that the proper http method is being used.
 *
 * @uses \App\Controller\QrImagesController
 */
class CrudTest extends BaseControllerTest
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
        $this->enableCsrfToken();
        $this->enableSecurityToken();
    }

    /**
     * Test qr-code method
     *
     * @uses \App\Controller\QrImagesController::qrCode()
     * @return void
     */
    public function testShow(): void
    {
        // get
        $this->get('https://localhost/qr-images/show/1');
        $this->assertResponseOk();

        // post
        $this->post('https://localhost/qr-images/show/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // patch
        $this->patch('https://localhost/qr-images/show/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // put
        $this->put('https://localhost/qr-images/show/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');

        // delete
        $this->delete('https://localhost/qr-images/show/1');
        $this->assertResponseCode(405);
        $this->assertResponseContains('Method Not Allowed');
    }
}
