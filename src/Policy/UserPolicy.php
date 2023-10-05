<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;

/**
 * User policy
 */
class UserPolicy
{
    /**
     * Only Admins can add User
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canAdd(User $user, User $User): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only Admins and Me can edit User
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canEdit(User $user, User $User): bool
    {
        return $this->isMe($user, $User) || $user->isAdmin();
    }

    /**
     * Only Admins can delete User
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canDelete(User $user, User $User): bool
    {
        return $user->isAdmin();
    }

    /**
     * Any User view a User's public profile.
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canView(User $user, User $User): bool
    {
        return $this->isMe($user, $User) || $user->isAdmin();
    }

    /**
     * Any User view a User's public profile.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canProfile(?User $user, User $User): bool
    {
        return true;
    }

    /**
     * Check if $user is the User
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    protected function isMe(User $user, User $User): bool
    {
        return $user->id === $User->id;
    }
}
