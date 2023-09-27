<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Test\TestCase\Controller\LoggedInTrait;
use Cake\Core\Configure;
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
     * Logs in a User
     *
     * @param int $id The ID of the user to login.
     * @return void
     */
    public function loginUser(int $id): void
    {
        if (!$this->Users) {
            $config = $this->getTableLocator()->exists('Users') ? [] : ['className' => \App\Model\Table\UsersTable::class];
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
     * Tests the Layout is there.
     *
     * @return void
     */
    public function helperTestLayoutNormal(): void
    {
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/default -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/default -->'));
    }

    /**
     * Tests the Layout when an Ajax request is made.
     *
     * @param string $content The html content to test.
     * @return void
     */
    public function helperTestLayoutAjax(string $content): void
    {
        $this->assertSame(0, substr_count($content, '<!-- START: App.layout/default -->'));
        $this->assertSame(0, substr_count($content, '<!-- END: App.layout/default -->'));

        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/ajax -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/ajax -->'));
    }
}
