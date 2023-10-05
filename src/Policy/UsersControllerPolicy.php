<?php
declare(strict_types=1);

namespace App\Policy;

use App\Controller\UsersController;
use App\Model\Entity\User;

/**
 * Users Controller policy
 */
class UsersControllerPolicy extends BaseControllerPolicy
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
     * Must be admin to view a list of users.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canIndex(?User $user, UsersController $UsersController): bool
    {
        if (!$user) {
            return false;
        }

        // Only admins can add a user
        return $user->isAdmin();
    }

    /**
     * Must be logged in to view a user private profile.
     *
     * Object policy to test the specific user is done in \App\Policy\UserPolicy::canView()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canView(?User $user, UsersController $UsersController): bool
    {
        if (!$user) {
            return false;
        }

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

    /**
     * Must be an admin to add a user
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canAdd(?User $user, UsersController $UsersController): bool
    {
        if (!$user) {
            return false;
        }

        // Only admins can add a user
        return $user->isAdmin();
    }

    /**
     * Must be logged in to edit a user
     *
     * Object policy to test editing the specific user is done in \App\Policy\UserPolicy::canEdit()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canEdit(?User $user, UsersController $UsersController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be an admin to delete a user
     *
     * Object policy to test editing the specific user is done in \App\Policy\UserPolicy::canDelete()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canDelete(?User $user, UsersController $UsersController): bool
    {
        if (!$user) {
            return false;
        }

        // Only admins can add a user
        return $user->isAdmin();
    }
}
