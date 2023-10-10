<?php
declare(strict_types=1);

namespace App\Policy;

use App\Controller\PagesController;
use App\Model\Entity\User;

/**
 * Pages Controller policy
 */
class PagesControllerPolicy extends AppControllerPolicy
{
    /**
     * Anyone can view pages.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\PagesController $PagesController
     * @return bool
     */
    public function canDisplay(?User $user, PagesController $PagesController): bool
    {
        return true;
    }
}
