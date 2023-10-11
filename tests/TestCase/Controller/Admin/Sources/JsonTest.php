<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Sources;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\Admin\SourcesController Test Case
 *
 * Test that the Json output is correct.
 * Specifically that fields are there that should be,
 * and not there that shouldn't be,
 *
 * @uses \App\Controller\Admin\SourcesController
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
     * @uses \App\Controller\Admin\SourcesController::index()
     */
    public function testIndex(): void
    {
        $this->get('https://localhost/admin/sources.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['sources']));
        $this->assertCount(3, $content['sources']);

        $item = $content['sources'][1];
        $this->assertSame(2, $item['id']);
        $this->assertFalse(isset($item['user_id']));
        $this->assertFalse(isset($item['user']));
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::view()
     */
    public function testView(): void
    {
        $this->get('https://localhost/admin/sources/view/2.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['source']));
        $this->assertSame(2, $content['source']['id']);
        $this->assertFalse(isset($content['source']['user_id']));
        $this->assertFalse(isset($content['source']['user']));
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::add()
     */
    public function testAdd(): void
    {
        // a get
        $this->get('https://localhost/admin/sources/add.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['source']));
        $this->assertTrue(empty($content['source']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        // a post fail
        $this->post('https://localhost/admin/sources/add.json', []);
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['source']));
        $this->assertTrue(empty($content['source']));

        $this->assertTrue(isset($content['errors']));
        $this->assertFalse(empty($content['errors']));

        $expected = [
            'name' => [
                '_required' => 'This field is required',
            ],
            'description' => [
                '_required' => 'This field is required',
            ],
        ];
        $this->assertSame($expected, $content['errors']);

        // a post success
        $this->post('https://localhost/admin/sources/add.json', [
            'name' => 'New JSON source',
            'description' => 'Description of the Source',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/sources/view/4.json');
        $this->assertFlashMessage('The source has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\SourcesController::edit()
     */
    public function testEdit(): void
    {
        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/sources/edit/1.json');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['source']));
        $this->assertFalse(empty($content['source']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        // a put success
        $this->put('https://localhost/admin/sources/edit/1.json', [
            'name' => 'New JSON source',
            'description' => 'Description of the source',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/sources/view/1.json');
        $this->assertFlashMessage('The source has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
