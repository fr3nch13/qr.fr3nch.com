<?php
declare(strict_types=1);

namespace App\Middleware\UnauthorizedHandler;

use Authorization\Exception\Exception;
use Authorization\Middleware\UnauthorizedHandler\RedirectHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @link https://book.cakephp.org/authorization/3/en/middleware.html#how-to-create-a-custom-unauthorizedhandler
 */
class CustomRedirectHandler extends RedirectHandler
{
    public function handle(Exception $exception, ServerRequestInterface $request, array $options = []): ResponseInterface
    {
        if (isset($options['url'])) {
            if (!$request->getAttribute('identity')) {
                $options['url'] = '/users/login';
            }
        }
        $response = parent::handle($exception, $request, $options);
        $request->getFlash()->error(__('You are not authorized to access that location'));

        return $response;
    }
}
