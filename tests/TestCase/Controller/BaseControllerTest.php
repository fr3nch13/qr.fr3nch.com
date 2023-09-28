<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Model\Table\UsersTable;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class BaseControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
        'app.Sources',
        'app.Categories',
        'app.QrCodes',
        'app.CategoriesQrCodes',
        'app.Tags',
        'app.QrCodesTags',
    ];
    /**
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * Just a dumb test so I can keep the name.
     *
     * @return void
     */
    public function testAssertSame(): void
    {
        $this->assertSame(1, 1);
    }

    /**
     * Logs in a User
     *
     * @param int $id The ID of the user to login.
     * @return void
     */
    public function loginUser(int $id): void
    {
        if (!$this->Users) {
            $config = $this->getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
            /** @var \App\Model\Table\UsersTable $Users */
            $Users = $this->getTableLocator()->get('Users', $config);
            $this->Users = $Users;
        }

        $user = $this->Users->get((int)$id);

        $this->session([
            'Auth' => $user,
        ]);
    }

    /**
     * Log out a User
     *
     * @return void
     */
    public function logoutUser(): void
    {
        $this->session([
            'Auth' => null,
        ]);
    }

    /**
     * Logs in an Admin user
     *
     * @return void
     */
    public function loginUserAdmin(): void
    {
        $this->loginUser(1);
    }

    /**
     * Logs in a Regular user
     *
     * @return void
     */
    public function loginUserRegular(): void
    {
        $this->loginUser(2);
    }

    /**
     * Sets HTTP headers for the *next* request to be identified as AJAX request.
     *
     * @return void
     */
    public function requestAsAjax(): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'text/html',
                'X-Requested-With' => 'XMLHttpRequest',
            ],
        ]);
    }

    /**
     * Tests the Layout is there.
     *
     * @return void
     */
    public function helperTestLayoutNormal(): void
    {
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/default -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/default -->'));
        $this->assertSame(1, substr_count($content, '<html>'));
        $this->assertSame(1, substr_count($content, '<head>'));
        $this->assertSame(1, substr_count($content, '</head>'));
        $this->assertSame(1, substr_count($content, '<body>'));

        // favicons
        $this->assertSame(1, substr_count($content, '<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">'));
        $this->assertSame(1, substr_count($content, '<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">'));
        $this->assertSame(1, substr_count($content, '<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">'));
        $this->assertSame(1, substr_count($content, '<link rel="manifest" href="/site.webmanifest">'));

        // test top navigation
        // test main section

        $this->assertSame(1, substr_count($content, '</body>'));
        $this->assertSame(1, substr_count($content, '</html>'));
    }

    /**
     * Tests the Layout when an Ajax request is made.
     *
     * @return void
     */
    public function helperTestLayoutAjax(): void
    {
        $content = (string)$this->_response->getBody();
        $this->assertSame(0, substr_count($content, '<!-- START: App.layout/default -->'));
        $this->assertSame(0, substr_count($content, '<!-- END: App.layout/default -->'));

        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/ajax -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/ajax -->'));
        $this->assertSame(0, substr_count($content, '<html>'));
        $this->assertSame(0, substr_count($content, '<head>'));
        $this->assertSame(0, substr_count($content, '</head>'));
        $this->assertSame(0, substr_count($content, '<body>'));
        $this->assertSame(0, substr_count($content, '</body>'));
        $this->assertSame(0, substr_count($content, '</html>'));
    }
}
