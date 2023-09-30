<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Source;
use App\Model\Entity\User;

/**
 * Source policy
 */
class SourcePolicy
{
    /**
     * All Users can view Source
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canView(User $identity, Source $Source): bool
    {
        return true;
    }

    /**
     * Only Admins can add Source
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canAdd(User $identity, Source $Source): bool
    {
        return $identity->isAdmin();
    }

    /**
     * Only Admins can edit Source
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canEdit(User $identity, Source $Source): bool
    {
        return $identity->isAdmin();
    }

    /**
     * Only Admins can delete Source
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canDelete(User $identity, Source $Source): bool
    {
        return $identity->isAdmin();
    }
}
