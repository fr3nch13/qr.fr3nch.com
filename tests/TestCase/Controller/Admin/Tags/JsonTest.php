<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Tags;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\TagsController Test Case
 *
 * Test that the Json output is correct.
 * Specifically that fields are there that should be,
 * and not there that shouldn't be,
 *
 * @uses \App\Controller\Admin\TagsController
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
     * @uses \App\Controller\Admin\TagsController::index()
     */
    public function testIndex(): void
    {
        $this->get('https://localhost/admin/tags.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['tags']));
        $this->assertCount(5, $content['tags']);

        $item = $content['tags'][1];
        $this->assertSame(2, $item['id']);
        $this->assertFalse(isset($item['user_id']));
        $this->assertFalse(isset($item['user']));
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::add()
     */
    public function testAdd(): void
    {
        // a get
        $this->get('https://localhost/admin/tags/add.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['tag']));
        $this->assertTrue(empty($content['tag']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        // a post fail
        $this->post('https://localhost/admin/tags/add.json', []);
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
        $this->post('https://localhost/admin/tags/add.json', [
            'name' => 'New JSON Tag',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/tags.json');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\TagsController::edit()
     */
    public function testEdit(): void
    {
        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/tags/edit/1.json');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['tag']));
        $this->assertFalse(empty($content['tag']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        // a put success
        $this->put('https://localhost/admin/tags/edit/1.json', [
            'name' => 'New JSON Tag',
            'description' => 'Description of the tag',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/tags.json');
        $this->assertFlashMessage('The tag has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
