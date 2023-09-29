<?php
declare(strict_types=1);

namespace App\Policy;

/**
 * Hook to allow authorization of controller
 * actions before object authorizations where needed.
 */
class ControllerHookPolicy
{
    /**
     * Calls this hook
     *
     * @param string $name The policy have to check
     * @param array<int, null|\Authorization\Identity|\App\Controller\AppController> $arguments The user and controller objects
     * @return bool If the authorization passed or failed.
     */
    public function __call(string $name, array $arguments): bool
    {
        /** @var ?\Authorization\Identity $identity */
        $identity = $arguments[0];

        /** @var ?\App\Model\Entity\User $user */
        $user = $identity?->getOriginalData();

        /** @var \App\Controller\AppController $controller */
        $controller = $arguments[1];

        return $controller->isAuthorized($user);
    }
}
