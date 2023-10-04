<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Categories;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\CategoriesController Test Case
 *
 * Test that the Json output is correct.
 * Specifically that fields are there that should be,
 * and not there that shouldn't be,
 *
 * @uses \App\Controller\CategoriesController
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
     * @uses \App\Controller\CategoriesController::index()
     */
    public function testIndex(): void
    {
        $this->get('/categories');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['categories']));
        $this->assertCount(3, $content['categories']);

        $item = $content['categories'][1];
        $this->assertSame(2, $item['id']);
        $this->assertTrue(isset($item['qr_codes']));
        $this->assertTrue(isset($item['parent_category']));
        $this->assertFalse(isset($item['user_id']));
        $this->assertFalse(isset($item['user']));
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::view()
     */
    public function testView(): void
    {
        $this->get('/categories/view/2');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['category']));
        $this->assertSame(2, $content['category']['id']);
        $this->assertTrue(isset($content['category']['qr_codes']));
        $this->assertTrue(isset($content['category']['parent_category']));
        $this->assertFalse(isset($content['category']['user_id']));
        $this->assertFalse(isset($content['category']['user']));
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::add()
     */
    public function testAdd(): void
    {
        // a get
        $this->get('/categories/add');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['category']));
        $this->assertTrue(empty($content['category']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        $this->assertTrue(isset($content['parentCategories']));
        $this->assertCount(3, $content['parentCategories']);

        // a post fail
        $this->post('/categories/add.json', []);
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['category']));
        $this->assertTrue(empty($content['category']));

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

        $this->assertTrue(isset($content['parentCategories']));
        $this->assertCount(3, $content['parentCategories']);

        // a post success
        $this->post('/categories/add.json', [
            'qrkey' => 'newjsonkey',
            'name' => 'New JSON QR Code',
            'description' => 'Description of the code',
            'url' => 'https://amazon.com/path/to/forward',
            'source_id' => 1,
        ]);
                $this->assertRedirectContains('/categories');
        $this->assertFlashMessage('The category has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\CategoriesController::edit()
     */
    public function testEdit(): void
    {
        // test with admin, get
        $this->loginUserAdmin();
        $this->get('/categories/edit/1');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['category']));
        $this->assertFalse(empty($content['category']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        $this->assertTrue(isset($content['parentCategories']));
        $this->assertCount(3, $content['parentCategories']);

        // a patch success
        $this->patch('/categories/edit/1.json', [
            'name' => 'New JSON Category',
            'description' => 'Description of the category',
        ]);
        $this->assertRedirectContains('/categories');
        $this->assertFlashMessage('The category has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
