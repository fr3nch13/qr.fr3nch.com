<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Tags;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\TagsController Test Case
 *
 * Test that the Json output is correct.
 * Specifically that fields are there that should be,
 * and not there that shouldn't be,
 *
 * @uses \App\Controller\TagsController
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
     * @uses \App\Controller\TagsController::index()
     */
    public function testIndex(): void
    {
        $this->get('https://localhost/tags');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['tags']));
        $this->assertCount(4, $content['tags']);

        $item = $content['tags'][1];
        $this->assertSame(2, $item['id']);
        $this->assertFalse(isset($item['user_id']));
        $this->assertFalse(isset($item['user']));
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\TagsController::view()
     */
    public function testView(): void
    {
        $this->get('https://localhost/tags/view/2');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['tag']));
        $this->assertSame(2, $content['tag']['id']);
        $this->assertTrue(isset($content['tag']['qr_codes']));
        $this->assertFalse(isset($content['tag']['user_id']));
        $this->assertFalse(isset($content['tag']['user']));
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\TagsController::add()
     */
    public function testAdd(): void
    {
        // a get
        $this->get('https://localhost/tags/add');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['tag']));
        $this->assertTrue(empty($content['tag']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        // a post fail
        $this->post('https://localhost/tags/add.json', []);
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['tag']));
        $this->assertTrue(empty($content['tag']));

        $this->assertTrue(isset($content['errors']));
        $this->assertFalse(empty($content['errors']));

        $expected = [
            'name' => [
                '_required' => 'This field is required',
            ],
        ];
        $this->assertSame($expected, $content['errors']);

        // a post success
        $this->post('https://localhost/tags/add.json', [
            'name' => 'New JSON Tag',
        ]);
                $this->assertRedirectEquals('https://localhost/tags');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\TagsController::edit()
     */
    public function testEdit(): void
    {
        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/tags/edit/1');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['tag']));
        $this->assertFalse(empty($content['tag']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        // a patch success
        $this->patch('https://localhost/tags/edit/1.json', [
            'name' => 'New JSON Category',
            'description' => 'Description of the tag',
        ]);
                $this->assertRedirectEquals('https://localhost/tags');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
