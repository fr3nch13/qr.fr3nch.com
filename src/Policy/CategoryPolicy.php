<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Category;
use App\Model\Entity\User;

/**
 * Category policy
 */
class CategoryPolicy
{
    /**
     * Check if $user can view qr codes
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    public function canView(?User $user, Category $Category): bool
    {
        // All users can view a category.
        return true;
    }

    /**
     * Only Admins can add a Category
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    public function canAdd(User $user, Category $Category): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only Admins can edit a Category
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    public function canEdit(User $user, Category $Category): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only Admins can delete a Category
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    public function canDelete(User $user, Category $Category): bool
    {
        return $user->isAdmin();
    }
}
