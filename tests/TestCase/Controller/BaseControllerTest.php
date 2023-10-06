<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Model\Table\UsersTable;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use HtmlValidator\Exception\ServerException as ValidatorServerException;
use HtmlValidator\Validator as HtmlValidator;

/**
 * Base Controller test for the other tests that use
 * Controllers request/response to test code.
 *
 * @property \Cake\Http\Response $_response
 */
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
        'app.Tags',
        'app.QrCodes',
        'app.QrImages',
        'app.CategoriesQrCodes',
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
     * Logs in a Regular user
     *
     * @return void
     */
    public function loginGuest(): void
    {
        $this->logoutUser();
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
     * Uses an HTML validator to validate the compiled html.
     *
     * @return void
     */
    public function helperValidateHTML(): void
    {
        $content = (string)$this->_response->getBody();
        try {
            $validator = new HtmlValidator();
            $result = $validator->validateDocument($content);
            $this->assertFalse($result->hasErrors(), (string)$result);
            $this->assertFalse($result->hasWarnings(), (string)$result);

            // Incase validator.nu throws an error.
        } catch (ValidatorServerException $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Tests alerts
     *
     * @param string $message The alert message.
     * @param string $type The alert type
     * @return void
     */
    public function helperTestAlert(string $message, string $type): void
    {
        $content = (string)$this->_response->getBody();
        // container
        $this->assertSame(1, substr_count($content, '<div role="alert" class="alert alert-dismissible ' .
            'fade show d-flex align-items-center alert-' . $type . '">'));
        // icon
        $this->assertSame(1, substr_count($content, '<i class="me-2 bi bi-exclamation-triangle-fill bi-xl"></i>'));
        // message
        $this->assertSame(1, substr_count($content, '<div>' . $message . '</div>'));
        // button
        $this->assertSame(1, substr_count($content, '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'));
    }

    /**
     * Tests form errors
     *
     * @param string $message The error message.
     * @param string $id The field id
     * @return void
     */
    public function helperTestFormFieldError(string $message, string $id): void
    {
        $content = (string)$this->_response->getBody();

        // message
        $needle = '<div id="' . $id . '" class="ms-0 invalid-feedback">' . $message . '</div>';

        $this->assertSame(1, substr_count($content, $needle));
    }

    /**
     * Tests the right template is inlcuded.
     *
     * @param string $action The path the form should submit to
     * @param string $method The method the form should be using. Defaults to post.
     * @param bool $isFile If the form is a file upload form, Defaults to false
     * @return void
     */
    public function helperTestFormTag(string $action, string $method = 'post', bool $isFile = false): void
    {
        $content = (string)$this->_response->getBody();

        $fileString = '';
        if ($isFile) {
            $fileString = 'enctype="multipart/form-data" ';
        }

        // if the method is a put or a patch, look for the cake internal translation.
        $otherMethod = null;
        if (in_array(strtolower($method), ['put', 'patch'])) {
            $otherMethod = $method;
            $method = 'post';
            // look for the hidenn method that cakephp uses
            // <input type="hidden" name="_method" value="PUT">
            $this->assertSame(1, substr_count($content, '<input type="hidden" name="_method" value="' .
                strtoupper($otherMethod) . '">'));
        }

        $formString = '<form ' . $fileString . 'method="' . $method .
            '" accept-charset="utf-8" role="form" action="' . $action . '">';

        $this->assertSame(1, substr_count($content, $formString));
    }

    /**
     * Tests for inserted template comments
     *
     * This allows me to test that the templates are working and getting data,
     * without having to specifically test html markup, as it will change.
     *
     * @param int $count The number of times this comment should exist.
     * @param string $coment The coment string to look for
     * @param string $namespace The namespace of the template. Defaults to 'App'
     * @return void
     */
    public function helperTestObjectComment(int $count, string $coment, string $namespace = 'App'): void
    {
        $templateString = $namespace . '.' . $coment;
        $content = (string)$this->_response->getBody();
        $this->assertSame($count, substr_count($content, '<!-- OBJECT_COMMENT: ' . $templateString . ' -->'));
    }

    /**
     * Tests the right template is inlcuded.
     *
     * @param string $templatePath If included, also look for the actual error path as well.
     * @param string $namespace The namespace of the template. Defaults to 'App'
     * @return void
     */
    public function helperTestTemplate(string $templatePath, string $namespace = 'App'): void
    {
        $templateString = $namespace . '.' . $templatePath;
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: ' . $templateString . ' -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: ' . $templateString . ' -->'));
    }

    /**
     * Tests the Layout is there.
     *
     * @return void
     */
    public function helperTestLayoutBase(): void
    {
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/base -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/base -->'));
        $this->assertSame(1, substr_count($content, '<html lang="en">'));
        $this->assertSame(1, substr_count($content, '<head>'));
        $this->assertSame(1, substr_count($content, '</head>'));
        $this->assertSame(1, substr_count($content, '<body>'));

        // favicons
        $this->assertSame(1, substr_count($content, '<link href="/favicon.ico" type="image/x-icon" rel="icon"><link href="/favicon.ico" type="image/x-icon" rel="shortcut icon">'));
        $this->assertSame(1, substr_count($content, '<link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">'));
        $this->assertSame(1, substr_count($content, '<link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">'));
        $this->assertSame(1, substr_count($content, '<link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">'));
        $this->assertSame(1, substr_count($content, '<link rel="manifest" href="/img/site.webmanifest">'));

        $this->assertSame(1, substr_count($content, '</body>'));
        $this->assertSame(1, substr_count($content, '</html>'));
    }

    /**
     * Tests that we're using the default layout
     *
     * @return void
     */
    public function helperTestLayoutDefault(): void
    {
        $this->helperTestLayoutBase();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/default -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/default -->'));

        // test other specific to this layout.
    }

    /**
     * Tests that we're using the pages/generic layout.
     * Mainly used by the PagesController's templates.
     *
     * @return void
     */
    public function helperTestLayoutPagesGeneric(): void
    {
        $this->helperTestLayoutBase();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/pages/generic -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/pages/generic -->'));

        // test other specific to this layout.
    }

    /**
     * Tests that we're using the pages/index layout
     *
     * @return void
     */
    public function helperTestLayoutPagesIndex(): void
    {
        $this->helperTestLayoutBase();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/pages/index -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/pages/index -->'));

        // test other specific to this layout.
    }

    /**
     * Tests that we're using the pages/view layout
     *
     * @return void
     */
    public function helperTestLayoutPagesView(): void
    {
        $this->helperTestLayoutBase();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/pages/view -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/pages/view -->'));

        // test other specific to this layout.
    }

    /**
     * Tests that we're using the pages/dashboard layout
     *
     * @return void
     */
    public function helperTestLayoutPagesDashboard(): void
    {
        $this->helperTestLayoutBase();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/pages/dashboard -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/pages/dashboard -->'));

        // test other specific to this layout.
    }

    /**
     * Tests that we're using the pages/form layout
     *
     * @return void
     */
    public function helperTestLayoutPagesForm(): void
    {
        $this->helperTestLayoutBase();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/pages/form -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/pages/form -->'));

        // test other specific to this layout.
    }

    /**
     * Tests that we're using the login layout
     *
     * @return void
     */
    public function helperTestLayoutLogin(): void
    {
        $this->helperTestLayoutBase();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/login -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/login -->'));

        // test other specific to this layout.
    }

    /**
     * Tests that we're using the Error/error400 page
     *
     * @param ?string $path If included, also look for the actual error path as well.
     * @return void
     */
    public function helperTestError400(?string $path = null): void
    {
        $this->helperTestLayoutError();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.Error/error400 -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.Error/error400 -->'));

        if ($path) {
            $this->assertSame(1, substr_count($content, 'The requested address <strong>\'' . $path . '\'</strong> was not found on this server.'));
        }

        // test other specific to this layout.
    }

    /**
     * Tests that we're using the Error/error500 page
     *
     * @return void
     */
    public function helperTestError500(): void
    {
        $this->helperTestLayoutError();
        $content = (string)$this->_response->getBody();
        $this->assertSame(1, substr_count($content, '<!-- START: App.Error/error500 -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.Error/error400 -->'));

        // test other specific to this layout.
    }

    /**
     * Tests that we're using the error layout
     *
     * @return void
     */
    public function helperTestLayoutError(): void
    {
        $content = (string)$this->_response->getBody();
        debug($content);
        $this->assertSame(1, substr_count($content, '<!-- START: App.layout/error -->'));
        $this->assertSame(1, substr_count($content, '<!-- END: App.layout/error -->'));

        // test other specific to this layout.
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
