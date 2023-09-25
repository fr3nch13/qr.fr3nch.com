<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * User policy
 */
class UserPolicy
{
    /**
     * Check if $user can add User
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canAdd(IdentityInterface $identity, User $User): bool
    {
        return $this->isAdmin($identity, $User);
    }

    /**
     * Check if $user can edit User
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canEdit(IdentityInterface $identity, User $User): bool
    {
        return $this->isMe($identity, $User) || $this->isAdmin($identity, $User);
    }

    /**
     * Check if $user can delete User
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canDelete(IdentityInterface $identity, User $User): bool
    {
        return $this->isAdmin($identity, $User);
    }

    /**
     * Check if $user can view User
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    public function canView(IdentityInterface $identity, User $User): bool
    {
        // All logged in users can view a qr code.
        return true;
    }

    /**
     * Check if $user created the User
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    protected function isMe(IdentityInterface $identity, User $User): bool
    {
        return $User->id === $identity->getIdentifier();
    }

    /**
     * Check if $user is an Admin
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\User $User
     * @return bool
     */
    protected function isAdmin(IdentityInterface $identity, User $User): bool
    {
        /** @var \App\Model\Entity\User $entity */
        $entity = $identity->getOriginalData();

        return $entity->is_admin ? true : false;
    }
}
