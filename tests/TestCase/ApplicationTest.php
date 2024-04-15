<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.3.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Test\TestCase;

use App\Application;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\Middleware\AuthorizationMiddleware;
use Cake\Core\Configure;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\Middleware\HttpsEnforcerMiddleware;
use Cake\Http\Middleware\SecurityHeadersMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * ApplicationTest class
 */
class ApplicationTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Test bootstrap in production.
     *
     * @return void
     */
    public function testBootstrap()
    {
        $app = new Application(dirname(dirname(__DIR__)) . '/config');
        $app->bootstrap();
        $plugins = $app->getPlugins();

        $this->assertFalse(Configure::read('debug'), 'debug is true?');

        $this->assertTrue($plugins->has('Bake'), 'plugins has Bake?');
        $this->assertFalse($plugins->has('DebugKit'), 'plugins has DebugKit?');
        $this->assertTrue($plugins->has('Migrations'), 'plugins has Migrations?');

        $this->assertTrue($plugins->has('Authentication'), 'plugins has Authentication?');
        $this->assertTrue($plugins->has('Authorization'), 'plugins has Authorization?');
        $this->assertTrue($plugins->has('BootstrapUI'), 'plugins has BootstrapUI?');
        $this->assertTrue($plugins->has('Search'), 'plugins has Search?');
    }

    /**
     * Test bootstrap add DebugKit plugin in debug mode.
     *
     * @return void
     */
    public function testBootstrapInDebug()
    {
        Configure::write('debug', true);
        $app = new Application(dirname(dirname(__DIR__)) . '/config');
        $app->bootstrap();
        $plugins = $app->getPlugins();

        $this->assertTrue($plugins->has('DebugKit'), 'plugins has DebugKit?');
    }

    /**
     * testMiddleware
     *
     * @return void
     */
    public function testMiddleware()
    {
        $app = new Application(dirname(dirname(__DIR__)) . '/config');
        $middleware = new MiddlewareQueue();

        $middleware = $app->middleware($middleware);

        $this->assertInstanceOf(ErrorHandlerMiddleware::class, $middleware->current());
        $middleware->seek(1);
        $this->assertInstanceOf(AssetMiddleware::class, $middleware->current());
        $middleware->seek(2);
        $this->assertInstanceOf(RoutingMiddleware::class, $middleware->current());
        $middleware->seek(3);
        $this->assertInstanceOf(BodyParserMiddleware::class, $middleware->current());
        $middleware->seek(4);
        $this->assertInstanceOf(CsrfProtectionMiddleware::class, $middleware->current());
        $middleware->seek(5);
        $this->assertInstanceOf(AuthenticationMiddleware::class, $middleware->current());
        $middleware->seek(6);
        $this->assertInstanceOf(AuthorizationMiddleware::class, $middleware->current());
        $middleware->seek(7);
        $this->assertInstanceOf(SecurityHeadersMiddleware::class, $middleware->current());
        $middleware->seek(8);
        $this->assertInstanceOf(HttpsEnforcerMiddleware::class, $middleware->current());
        // not used until it's fixed. See Application.php for details.
        //$middleware->seek(9);
        //$this->assertInstanceOf(CspMiddleware::class, $middleware->current());
    }
}
