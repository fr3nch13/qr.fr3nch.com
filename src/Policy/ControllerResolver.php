<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\Policy\Exception\MissingPolicyException;
use Authorization\Policy\ResolverInterface;
use Cake\Controller\Controller;

class ControllerResolver implements ResolverInterface
{
    /**
     * Calls the ControllerHookPolicy to check controller actions.
     *
     * @param mixed $resource Namelt the Controller to check
     * @return mixed Mainly a bool if the authorization succedded or failed.
     */
    public function getPolicy(mixed $resource): mixed
    {
        if ($resource instanceof Controller) {
            return new ControllerHookPolicy();
        }

        throw new MissingPolicyException([get_class($resource)]);
    }
}
