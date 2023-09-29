<?php
declare(strict_types=1);

namespace App\Policy;

class ControllerHookPolicy
{
    public function __call(string $name, array $arguments)
    {
        /** @var ?\Authorization\Identity $user */
        [$user, $controller] = $arguments;

        return $controller->isAuthorized($user?->getOriginalData());
    }
}
