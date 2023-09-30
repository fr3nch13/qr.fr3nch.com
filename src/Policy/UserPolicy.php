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
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canAdd(User $identity, User $User): bool
    {
        return $identity->isAdmin();
    }

    /**
     * Only Admins and Me can edit User
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canEdit(User $identity, User $User): bool
    {
        return $this->isMe($identity, $User) || $identity->isAdmin();
    }

    /**
     * Only Admins can delete User
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canDelete(User $identity, User $User): bool
    {
        return $identity->isAdmin();
    }

    /**
     * Any User view a User's public profile.
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canView(User $identity, User $User): bool
    {
        return $this->isMe($identity, $User) || $identity->isAdmin();
    }

    /**
     * Any User view a User's public profile.
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canProfile(?User $identity, User $User): bool
    {
        return true;
    }

    /**
     * Check if $identity is the User
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    protected function isMe(User $identity, User $User): bool
    {
        return $identity->id === $User->id;
    }
}
