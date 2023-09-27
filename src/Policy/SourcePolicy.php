<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Source;
use Authorization\IdentityInterface;

/**
 * Source policy
 */
class SourcePolicy
{
    /**
     * Check if $user can add Source
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canAdd(IdentityInterface $identity, Source $Source): bool
    {
        // All logged in users can create qr codes.
        return $this->isAdmin($identity, $Source);
    }

    /**
     * Check if $user can edit Source
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canEdit(IdentityInterface $identity, Source $Source): bool
    {
        return $this->isAdmin($identity, $Source);
    }

    /**
     * Check if $user can delete Source
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canDelete(IdentityInterface $identity, Source $Source): bool
    {
        return $this->isAdmin($identity, $Source);
    }

    /**
     * Check if $user can view Source
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canView(IdentityInterface $identity, Source $Source): bool
    {
        // All logged in users can view a qr code.
        return true;
    }

    /**
     * Check if $user is an Admin
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    protected function isAdmin(IdentityInterface $identity, Source $Source): bool
    {
        /** @var \App\Model\Entity\User $entity */
        $entity = $identity->getOriginalData();

        return $entity->is_admin ? true : false;
    }
}
