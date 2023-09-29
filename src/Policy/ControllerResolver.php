<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\Policy\Exception\MissingPolicyException;
use Authorization\Policy\ResolverInterface;
use Cake\Controller\Controller;

class ControllerResolver implements ResolverInterface
{
    public function getPolicy(mixed $resource): mixed
    {
        if ($resource instanceof Controller) {
            return new ControllerHookPolicy();
        }

        throw new MissingPolicyException([get_class($resource)]);
    }
}
