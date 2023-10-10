<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\Users;

use App\Test\TestCase\Controller\BaseControllerTest;
use Cake\Core\Configure;

/**
 * App\Controller\UsersController Test Case
 *
 * Test that the Json output is correct.
 * Specifically that fields are there that should be,
 * and not there that shouldn't be,
 *
 * @uses \App\Controller\Admin\UsersController
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
    }

    /**
     * Test dashboard method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::dashboard()
     */
    public function testDashboard(): void
    {
        $this->loginUserAdmin();
        $this->get('https://localhost/admin.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['activeUser']));
        $this->assertSame(1, $content['activeUser']['id']);
        $this->assertFalse(isset($content['activeUser']['password']));

        $this->loginUserAdmin();
        $this->get('https://localhost/admin/dashboard.json');
        $this->assertResponseOk();

        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/dashboard.json');
        $this->assertResponseOk();
    }

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::index()
     */
    public function testIndex(): void
    {
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['users']));
        $this->assertCount(4, $content['users']);

        $item = $content['users'][1];
        $this->assertSame(2, $item['id']);
        $this->assertFalse(isset($item['password']));
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::view()
     */
    public function testView(): void
    {
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/view/2.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['user']));
        $this->assertSame(2, $content['user']['id']);
        $this->assertFalse(isset($content['user']['password']));
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::add()
     */
    public function testAdd(): void
    {
        $this->loginUserAdmin();
        // a get
        $this->get('https://localhost/admin/users/add.json');
        $this->assertResponseOk();

        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['user']));
        $this->assertTrue(empty($content['user']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        // a post fail
        $this->post('https://localhost/admin/users/add.json', []);
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['user']));
        $this->assertTrue(empty($content['user']));

        $this->assertTrue(isset($content['errors']));
        $this->assertFalse(empty($content['errors']));

        $expected = [
            'name' => [
                '_required' => 'This field is required',
            ],
            'email' => [
                '_required' => 'This field is required',
            ],
            'password' => [
                '_required' => 'This field is required',
            ],
        ];
        $this->assertSame($expected, $content['errors']);

        // a post success
        $this->post('https://localhost/admin/users/add.json', [
            'name' => 'New JSON User',
            'email' => 'newjsonuser@example.com',
            'password' => 'password',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/users/view/5.json');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\Admin\UsersController::edit()
     */
    public function testEdit(): void
    {
        $this->loginUserAdmin();
        // test with admin, get
        $this->loginUserAdmin();
        $this->get('https://localhost/admin/users/edit/1.json');
        $this->assertResponseOk();
        $content = (string)$this->_response->getBody();
        $content = json_decode($content, true);

        $this->assertTrue(isset($content['user']));
        $this->assertFalse(empty($content['user']));

        $this->assertTrue(isset($content['errors']));
        $this->assertTrue(empty($content['errors']));

        // a put success
        $this->put('https://localhost/admin/users/edit/1.json', [
            'name' => 'Updated JSON User',
        ]);
        $this->assertRedirectEquals('https://localhost/admin/users/view/1.json');
        $this->assertFlashMessage('The user has been saved.', 'flash');
        $this->assertFlashElement('flash/success');
    }
}
