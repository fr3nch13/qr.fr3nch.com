<?php
declare(strict_types=1);

namespace App\Policy;

use App\Controller\UsersController;
use App\Model\Entity\User;

/**
 * Users Controller policy
 */
class UsersControllerPolicy extends AppControllerPolicy
{
    /**
     * Anyone can try to login
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canLogin(?User $user, UsersController $UsersController): bool
    {
        return true;
    }

    /**
     * Anyone can try to logout
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canLogout(?User $user, UsersController $UsersController): bool
    {
        return true;
    }

    /**
     * Anyone can view a User's public profile.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canProfile(?User $user, UsersController $UsersController): bool
    {
        return true;
    }
}
