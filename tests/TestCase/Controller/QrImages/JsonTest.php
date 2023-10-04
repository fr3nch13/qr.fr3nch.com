<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\QrImages;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\QrImagesController Test Case
 *
 * Test that the Json output is correct.
 * Specifically that fields are there that should be,
 * and not there that shouldn't be,
 *
 * @uses \App\Controller\QrImagesController
 */
class JsonTest extends BaseControllerTest
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
        $this->requestAsJson();
        $this->loginUserAdmin();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::index()
     */
    public function testIndex(): void
    {
        $this->get('https://localhost/qr-images');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['qrImages']));
        $this->assertCount(2, $content['qrImages']);

        $first = reset($content['qrImages']);
        $this->assertSame(1, $first['id']);
        $this->assertTrue(isset($first['tags']));
        $this->assertTrue(isset($first['categories']));
        $this->assertTrue(isset($first['source']));
        $this->assertFalse(isset($first['user_id']));
        $this->assertFalse(isset($first['user']));
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::view()
     */
    public function testView(): void
    {
        $this->get('https://localhost/qr-images/view/1');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['qrImage']));
        $this->assertSame(1, $content['qrImage']['id']);
        $this->assertTrue(isset($content['qrImage']['tags']));
        $this->assertTrue(isset($content['qrImage']['categories']));
        $this->assertTrue(isset($content['qrImage']['source']));
        $this->assertFalse(isset($content['qrImage']['user_id']));
        $this->assertFalse(isset($content['qrImage']['user']));
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::add()
     */
    public function testAdd(): void
    {
        // a get
        $this->get('https://localhost/qr-images/add');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['qrImage']));
        $this->assertTrue(empty($content['qrImage']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        $this->assertTrue(isset($content['sources']));
        $this->assertCount(2, $content['sources']);
        $this->assertTrue(isset($content['categories']));
        $this->assertCount(3, $content['categories']);
        $this->assertTrue(isset($content['tags']));
        $this->assertCount(4, $content['tags']);

        // a post fail
        $this->post('https://localhost/qr-images/add.json', []);
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['qrImage']));
        $this->assertTrue(empty($content['qrImage']));

        $this->assertTrue(isset($content['errors']));
        $this->assertFalse(empty($content['errors']));
        $expected = [
            'qrkey' => [
                '_required' => 'This field is required',
            ],
            'name' => [
                '_required' => 'This field is required',
            ],
            'description' => [
                '_required' => 'This field is required',
            ],
            'url' => [
                '_required' => 'This field is required',
            ],
            'source_id' => [
                '_required' => 'This field is required',
            ],
        ];
        $this->assertSame($expected, $content['errors']);

        $this->assertTrue(isset($content['sources']));
        $this->assertCount(2, $content['sources']);
        $this->assertTrue(isset($content['categories']));
        $this->assertCount(3, $content['categories']);
        $this->assertTrue(isset($content['tags']));
        $this->assertCount(4, $content['tags']);

        // a post success
        $this->post('https://localhost/qr-images/add.json', [
            'qrkey' => 'newjsonkey',
            'name' => 'New JSON QR Code',
            'description' => 'Description of the code',
            'url' => 'https://amazon.com/path/to/forward',
            'source_id' => 1,
        ]);
                $this->assertRedirectEquals('/');
        $this->assertFlashMessage('The qr code has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\QrImagesController::edit()
     */
    public function testEdit(): void
    {
        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/qr-images/edit/1');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['qrImage']));
        $this->assertFalse(empty($content['qrImage']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        $this->assertTrue(isset($content['sources']));
        $this->assertCount(2, $content['sources']);
        $this->assertTrue(isset($content['categories']));
        $this->assertCount(3, $content['categories']);
        $this->assertTrue(isset($content['tags']));
        $this->assertCount(4, $content['tags']);

        // a fail as qrkey can't be updated via forms/entities
        $this->patch('https://localhost/qr-images/edit/1.json', [
            'qrkey' => 'newjsonkey',
            'name' => 'New JSON QR Code',
            'description' => 'Description of the code',
            'url' => 'https://amazon.com/path/to/forward',
            'source_id' => 1,
        ]);
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['qrImage']));
        $this->assertFalse(empty($content['qrImage']));

        $this->assertTrue(isset($content['errors']));
        $this->assertFalse(empty($content['errors']));

        $this->assertSame('QR Key can not be updated.', $content['errors']['qrkey']['update']);

        $this->assertTrue(isset($content['sources']));
        $this->assertCount(2, $content['sources']);
        $this->assertTrue(isset($content['categories']));
        $this->assertCount(3, $content['categories']);
        $this->assertTrue(isset($content['tags']));
        $this->assertCount(4, $content['tags']);

        // a patch success
        $this->patch('https://localhost/qr-images/edit/1.json', [
            'name' => 'New JSON QR Code',
            'description' => 'Description of the code',
            'url' => 'https://amazon.com/path/to/forward',
            'source_id' => 1,
        ]);
                $this->assertRedirectEquals('/');
        $this->assertFlashMessage('The qr code has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
