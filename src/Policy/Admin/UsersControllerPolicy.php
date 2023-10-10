<?php
declare(strict_types=1);

namespace App\Policy\Admin;

use App\Controller\Admin\UsersController;
use App\Model\Entity\User;

/**
 * Admin Users Controller policy
 */
class UsersControllerPolicy extends AppControllerPolicy
{
    /**
     * The dashboard is the main admin page for all logged in users.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\UsersController $UsersController
     * @return bool
     */
    public function canDashboard(?User $user, UsersController $UsersController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be admin to view a list of users.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\UsersController $UsersController
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
     * @param \App\Controller\Admin\UsersController $UsersController
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
     * Must be an admin to add a user
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\UsersController $UsersController
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
     * @param \App\Controller\Admin\UsersController $UsersController
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
     * @param \App\Controller\Admin\UsersController $UsersController
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
