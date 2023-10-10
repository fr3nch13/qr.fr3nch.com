<?php
declare(strict_types=1);

namespace App\Middleware\UnauthorizedHandler;

use Authorization\Exception\Exception;
use Authorization\Middleware\UnauthorizedHandler\RedirectHandler;
use Cake\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @link https://book.cakephp.org/authorization/3/en/middleware.html#how-to-create-a-custom-unauthorizedhandler
 */
class CustomRedirectHandler extends RedirectHandler
{
    /**
     * Handles the exceptions thrown by the Authorization middleware
     *
     * @param \Authorization\Exception\Exception $exception The thrown exception to handle
     * @param \Cake\Http\ServerRequest $request the incoming request.
     * @param array<string, mixed> $options The related options to check/manipulate
     * @return \Psr\Http\Message\ResponseInterface The response object.
     */
    public function handle(
        Exception $exception,
        ServerRequestInterface $request,
        array $options = []
    ): ResponseInterface {
        if (isset($options['url'])) {
            if (!$request->getAttribute('identity')) {
                $options['url'] = Router::url([
                    '_full' => true,
                    'prefix' => false,
                    'plugin' => false,
                    'controller' => 'Users',
                    'action' => 'login',
                ]);
            }
        }

        $response = parent::handle($exception, $request, $options);
        $request->getFlash()->error(__('You are not authorized to access that location'));

        return $response;
    }
}
