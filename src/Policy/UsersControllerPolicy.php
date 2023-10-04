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
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canLogin(?User $identity, UsersController $UsersController): bool
    {
        return true;
    }

    /**
     * Anyone can try to logout
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canLogout(?User $identity, UsersController $UsersController): bool
    {
        return true;
    }

    /**
     * Must be admin to view a list of users.
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canIndex(?User $identity, UsersController $UsersController): bool
    {
        if (!$identity) {
            return false;
        }

        // Only admins can add a category
        return $identity->isAdmin();
    }

    /**
     * Only admins and Me can view the private profile page.
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canView(?User $identity, UsersController $UsersController): bool
    {
        if (!$identity) {
            return false;
        }

        return true;
    }

    /**
     * Anyone can view a User's public profile.
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canProfile(?User $identity, UsersController $UsersController): bool
    {
        return true;
    }

    /**
     * Must be an admin to add a user
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canAdd(?User $identity, UsersController $UsersController): bool
    {
        if (!$identity) {
            return false;
        }

        // Only admins can add a category
        return $identity->isAdmin();
    }

    /**
     * Must be logged in to edit a user
     * Object policy to test editing the specific user is done in \App\Policy\UserPolicy::canEdit()
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canEdit(?User $identity, UsersController $UsersController): bool
    {
        if (!$identity) {
            return false;
        }

        return true;
    }

    /**
     * Must be an admin to delete a user
     * Object policy to test editing the specific user is done in \App\Policy\UserPolicy::canDelete()
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\UsersController $UsersController
     * @return bool
     */
    public function canDelete(?User $identity, UsersController $UsersController): bool
    {
        if (!$identity) {
            return false;
        }

        // Only admins can add a category
        return $identity->isAdmin();
    }
}
