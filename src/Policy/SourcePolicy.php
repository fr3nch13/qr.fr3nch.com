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
     * Only Admins can add Source
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canAdd(User $user, Source $Source): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only Admins can edit Source
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canEdit(User $user, Source $Source): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only Admins can delete Source
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\Source $Source
     * @return bool
     */
    public function canDelete(User $user, Source $Source): bool
    {
        return $user->isAdmin();
    }
}
