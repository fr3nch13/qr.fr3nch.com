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
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Source $Source): bool
    {
        // All logged in users can create qr codes.
        return $this->isAdmin($user, $Source);
    }

    /**
     * Check if $user can edit Source
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Source $Source): bool
    {
        return $this->isAdmin($user, $Source);
    }

    /**
     * Check if $user can delete Source
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Source $Source): bool
    {
        return $this->isAdmin($user, $Source);
    }

    /**
     * Check if $user can view Source
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canView(IdentityInterface $user, Source $Source): bool
    {
        // All logged in users can view a qr code.
        return true;
    }

    protected function isAdmin(IdentityInterface $user, Source $Source)
    {
        return $user->getOriginalData()->is_admin ? true : false;
    }
}
