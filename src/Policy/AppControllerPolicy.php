<?php
declare(strict_types=1);

namespace App\Policy;

use App\Controller\AppController;
use Authorization\IdentityInterface;
use Authorization\Policy\BeforePolicyInterface;
use Authorization\Policy\ResultInterface;
use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;

/**
 * Base Controller policy
 */
class AppControllerPolicy implements BeforePolicyInterface
{
    /**
     * Summary of before
     *
     * @param ?\Authorization\IdentityInterface $identity The user or null
     * @param mixed $resource The controller
     * @param string $action The name of the action trying to be accessed
     * @return \Authorization\Policy\ResultInterface|bool|null
     * @throws \Cake\Http\Exception\NotFoundException if the action doesn't exist.
     */
    public function before(?IdentityInterface $identity, mixed $resource, string $action): ResultInterface|bool|null
    {
        // throw a 404 here for all missing actions.
        if (!empty($action) && $resource instanceof AppController) {
            if (!method_exists($resource, $action)) {
                $message = __('Page Not Found');
                if (Configure::read('debug')) {
                    $message = __('Missing Action `{0}::{1}()`', [
                        get_class($resource),
                        $action,
                    ]);
                }

                throw new NotFoundException($message);
            }
        }

        // fall through
        // always return null so that the other, more specific policy checks can happen.
        return null;
    }
}
