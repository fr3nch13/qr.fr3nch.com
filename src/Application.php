<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use App\Controller\CategoriesController;
use App\Controller\PagesController;
use App\Controller\QrCodesController;
use App\Controller\SourcesController;
use App\Controller\TagsController;
use App\Controller\UsersController;
use App\Policy\CategoriesControllerPolicy;
use App\Policy\PagesControllerPolicy;
use App\Policy\QrCodesControllerPolicy;
use App\Policy\SourcesControllerPolicy;
use App\Policy\TagsControllerPolicy;
use App\Policy\UsersControllerPolicy;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Exception\ForbiddenException;
use Authorization\Exception\MissingIdentityException;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Policy\MapResolver;
use Authorization\Policy\OrmResolver;
use Authorization\Policy\ResolverCollection;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CspMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\Middleware\HttpsEnforcerMiddleware;
use Cake\Http\Middleware\SecurityHeadersMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 *
 * @extends \Cake\Http\BaseApplication<\App\Application>
 */
class Application extends BaseApplication implements
    AuthenticationServiceProviderInterface,
    AuthorizationServiceProviderInterface
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        }
        FactoryLocator::add(
            'Table',
            (new TableLocator())->allowFallbackClass(false)
        );

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }

        // Load more plugins here
        // CakePHP's Authorizatio Plugin.
        $this->addPlugin('Authorization');

        // the bootstrapui plugin.
        $this->addPlugin('BootstrapUI');
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Content Security Policy
            // @link https://book.cakephp.org/5/en/security/content-security-policy.html#content-security-policy-middleware
            ->add(new CspMiddleware([
                'script-src' => [
                    'allow' => [
                        // external domains that can load/run javascript.
                        'https://www.google-analytics.com',
                    ],
                    'self' => true,
                    'unsafe-inline' => false,
                    'unsafe-eval' => false,
                    'script-src' => [],
                    'style-src' => [],
                ],
            ], [
                'scriptNonce' => true,
                'styleNonce' => true,
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance.
            // See https://github.com/CakeDC/cakephp-cached-routing
            ->add(new RoutingMiddleware($this))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())

            // Cross Site Request Forgery (CSRF) Protection Middleware
            // https://book.cakephp.org/4/en/security/csrf.html#cross-site-request-forgery-csrf-middleware
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,
            ]))

            // @link https://book.cakephp.org/5/en/tutorials-and-examples/cms/authentication.html
            ->add(new AuthenticationMiddleware($this))

            // @link https://book.cakephp.org/5/en/tutorials-and-examples/cms/authorization.html
            ->add(new AuthorizationMiddleware($this, [
                'requireAuthorizationCheck' => Configure::read('debug'),
                'identityDecorator' => function ($auth, $user) {
                    return $user->setAuthorization($auth);
                },
                'unauthorizedHandler' => [
                    'className' => 'CustomRedirect', // <--- see here
                    'url' => '/',
                    'queryParam' => 'redirect',
                    'exceptions' => [
                        MissingIdentityException::class,
                        ForbiddenException::class,
                    ],
                    'custom_param' => true,
                ],
            ]));

        // @link https://book.cakephp.org/5/en/security/security-headers.html
        $securityHeaders = new SecurityHeadersMiddleware();
        $securityHeaders
            ->setReferrerPolicy()
            ->setXFrameOptions()
            ->noOpen()
            ->noSniff();
        $middlewareQueue->add($securityHeaders);

        $https = new HttpsEnforcerMiddleware([
            'redirect' => true,
            'statusCode' => 302,
            'hsts' => [
                // How long the header value should be cached for.
                'maxAge' => 60 * 60 * 24 * 365,
                // should this policy apply to subdomains?
                'includeSubDomains' => true,
                // Should the header value be cacheable in google's HSTS preload
                // service? While not part of the spec it is widely implemented.
                'preload' => true,
            ],
        ]);
        $middlewareQueue->add($https);

        return $middlewareQueue;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
    }

    /**
     * Bootstrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {
        $this->addOptionalPlugin('Bake');

        $this->addPlugin('Migrations');

        // Load more plugins here
    }

    /**
     * Gets and Configures the Authentication Service
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Authentication\AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $fields = [
            IdentifierInterface::CREDENTIAL_USERNAME => 'email',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password',
        ];

        $authenticationService = new AuthenticationService([
            'unauthenticatedRedirect' => Router::url([
                'prefix' => false,
                'plugin' => null,
                'controller' => 'Users',
                'action' => 'login',
            ]),
            'queryParam' => 'redirect',
        ]);

        // Load identifiers, ensure we check email and password fields
        $authenticationService->loadIdentifier('Authentication.Password', [
            'fields' => $fields,
        ]);

        // Load the authenticators, you want session first
        $authenticationService->loadAuthenticator('Authentication.Session');

        // Configure form data check to pick email and password
        $authenticationService->loadAuthenticator('Authentication.Form', [
            'fields' => $fields,
            'loginUrl' => [
                Router::url([
                    'prefix' => false,
                    'plugin' => false,
                    'controller' => 'Users',
                    'action' => 'login',
                    '_ext' => null,
                ]),
                Router::url([
                    'prefix' => false,
                    'plugin' => false,
                    'controller' => 'Users',
                    'action' => 'login',
                    '_ext' => 'json',
                ]),
            ],
        ]);

        // Used for Remember me
        $authenticationService->loadAuthenticator('Authentication.Cookie', [
            'fields' => $fields,
            'loginUrl' => [
                Router::url([
                    'prefix' => false,
                    'plugin' => false,
                    'controller' => 'Users',
                    'action' => 'login',
                    '_ext' => null,
                ]),
                Router::url([
                    'prefix' => false,
                    'plugin' => false,
                    'controller' => 'Users',
                    'action' => 'login',
                    '_ext' => 'json',
                ]),
            ],
        ]);

        return $authenticationService;
    }

    /**
     * Gets the Authorization Service
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Authorization\AuthorizationServiceInterface
     */
    public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
    {
        // Use the Map Resolver to map controllers to a policy.
        $mapResolver = new MapResolver();

        // map the controllers
        $mapResolver->map(CategoriesController::class, CategoriesControllerPolicy::class);
        $mapResolver->map(QrCodesController::class, QrCodesControllerPolicy::class);
        $mapResolver->map(SourcesController::class, SourcesControllerPolicy::class);
        $mapResolver->map(TagsController::class, TagsControllerPolicy::class);
        $mapResolver->map(UsersController::class, UsersControllerPolicy::class);
        $mapResolver->map(PagesController::class, PagesControllerPolicy::class);

        $ormResolver = new OrmResolver();

        // @link https://book.cakephp.org/authorization/3/en/policy-resolvers.html#using-multiple-resolvers
        $resolver = new ResolverCollection([
            $mapResolver, // make sure this one is first.
            $ormResolver,
        ]);

        return new AuthorizationService($resolver);
    }
}
