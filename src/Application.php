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

use App\Controller\Admin\QrCodesController as AdminQrCodesController;
use App\Controller\Admin\QrImagesController as AdminQrImagesController;
use App\Controller\Admin\SourcesController as AdminSourcesController;
use App\Controller\Admin\TagsController as AdminTagsController;
use App\Controller\Admin\UsersController as AdminUsersController;
use App\Controller\PagesController;
use App\Controller\QrCodesController;
use App\Controller\QrImagesController;
use App\Controller\TagsController;
use App\Controller\UsersController;
use App\Event\QrCodeListener;
use App\Policy\Admin\QrCodesControllerPolicy as AdminQrCodesControllerPolicy;
use App\Policy\Admin\QrImagesControllerPolicy as AdminQrImagesControllerPolicy;
use App\Policy\Admin\SourcesControllerPolicy as AdminSourcesControllerPolicy;
use App\Policy\Admin\TagsControllerPolicy as AdminTagsControllerPolicy;
use App\Policy\Admin\UsersControllerPolicy as AdminUsersControllerPolicy;
use App\Policy\PagesControllerPolicy;
use App\Policy\QrCodesControllerPolicy;
use App\Policy\QrImagesControllerPolicy;
use App\Policy\TagsControllerPolicy;
use App\Policy\UsersControllerPolicy;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\AbstractIdentifier;
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
        if (Configure::read('debug') === true && !$this->getPlugins()->has('DebugKit')) {
            $this->addOptionalPlugin('DebugKit');
        }

        // Load more plugins here
        if (!$this->getPlugins()->has('Authentication')) {
            $this->addPlugin('Authentication');
        }

        // CakePHP's Authorization Plugin.
        if (!$this->getPlugins()->has('Authorization')) {
            $this->addPlugin('Authorization');
        }

        // the friendsofcake/bootstrapui plugin.
        if (!$this->getPlugins()->has('BootstrapUI')) {
            $this->addPlugin('BootstrapUI');
        }

        // the friendsofcake/search plugin.
        if (!$this->getPlugins()->has('Search')) {
            $this->addPlugin('Search');
        }

        // my stats plugin
        if (!$this->getPlugins()->has('Fr3nch13/Stats')) {
            $this->addPlugin('Fr3nch13/Stats');
        }

        // register the event listeners.
        $this->registerEventListeners();
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
                'requireAuthorizationCheck' => false,
                'identityDecorator' => function ($auth, $user) {
                    return $user->setAuthorization($auth); //turns the user entity directly into the identity object.
                },
                'unauthorizedHandler' => [
                    'className' => 'CustomRedirect',
                    'url' => Router::url('/admin', true),
                    'queryParam' => 'redirect',
                    'exceptions' => [
                        MissingIdentityException::class,
                        ForbiddenException::class,
                    ],
                    'custom_param' => true,
                ],
            ]));

            /*
            // TODO: Enable when whatever unsafe-inline is fixed.
            // It's causing inline onclicks to be blocked, even though unsafe-inline is set to true.

            // Content Security Policy
            // @link https://book.cakephp.org/5/en/security/content-security-policy.html#content-security-policy-middleware
            // @link https://github.com/paragonie/csp-builder
            ->add(new CspMiddleware([
                'script-src' => [
                    'self' => true,
                    'unsafe-inline' => true,
                    'unsafe-eval' => false,
                    'allow' => [
                        // external domains that can load/run javascript.
                        //'https://www.google-analytics.com',
                    ],
                ],
            ], [
                'scriptNonce' => true,
                'styleNonce' => true,
            ]))
            */

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
            'disableOnDebug' => true,
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
     * Gets and Configures the Authentication Service
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Authentication\AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $fields = [
            AbstractIdentifier::CREDENTIAL_USERNAME => 'email',
            AbstractIdentifier::CREDENTIAL_PASSWORD => 'password',
        ];

        $authenticationService = new AuthenticationService([
            'unauthenticatedRedirect' => Router::url([
                'prefix' => false,
                'plugin' => false,
                'controller' => 'Users',
                'action' => 'login',
            ]),
            'queryParam' => 'redirect',
        ]);

        // Load identifiers, ensure we check email and password fields
        $authenticationService->loadIdentifier('Authentication.Password', [
            'fields' => $fields,
            'resolver' => [
                'className' => 'Authentication.Orm',
                'userModel' => 'Users',
                'finder' => 'active',
            ],
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
        $mapResolver->map(QrCodesController::class, QrCodesControllerPolicy::class);
        $mapResolver->map(QrImagesController::class, QrImagesControllerPolicy::class);
        $mapResolver->map(TagsController::class, TagsControllerPolicy::class);
        $mapResolver->map(UsersController::class, UsersControllerPolicy::class);
        $mapResolver->map(PagesController::class, PagesControllerPolicy::class);

        // admin controllers
        $mapResolver->map(AdminQrCodesController::class, AdminQrCodesControllerPolicy::class);
        $mapResolver->map(AdminQrImagesController::class, AdminQrImagesControllerPolicy::class);
        $mapResolver->map(AdminSourcesController::class, AdminSourcesControllerPolicy::class);
        $mapResolver->map(AdminTagsController::class, AdminTagsControllerPolicy::class);
        $mapResolver->map(AdminUsersController::class, AdminUsersControllerPolicy::class);

        $ormResolver = new OrmResolver();

        // @link https://book.cakephp.org/authorization/3/en/policy-resolvers.html#using-multiple-resolvers
        $resolver = new ResolverCollection([
            // make sure this one is first.
            // we want to check the general controller actions,
            $mapResolver,
            // before we check the individual entities,
            // or scopes for index pages.
            $ormResolver,
        ]);

        return new AuthorizationService($resolver);
    }

    /**
     * Register event listeners globally.
     *
     * Called in self::bootstrap()
     *
     * @return void
     */
    protected function registerEventListeners(): void
    {
        /** @var \Cake\Event\EventManager $eventManager */
        $eventManager = $this->getEventManager();
        // make sure they're only getting registered globally, once.
        // TODO: Hacky as we're tracking the event key, not if the listener itself is already registered.
        // Maybe use listeners('QrCode.onHit')
        if (empty($eventManager->prioritisedListeners('QrCode.onHit'))) {
            $eventManager->on(new QrCodeListener());
        }
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
        if (!$this->getPlugins()->has('Bake')) {
            $this->addOptionalPlugin('Bake');
        }

        if (!$this->getPlugins()->has('Migrations')) {
            $this->addPlugin('Migrations');
        }

        // Load more plugins here
    }
}
