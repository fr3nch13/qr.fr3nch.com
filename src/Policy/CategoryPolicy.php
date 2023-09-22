<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Category;
use Authorization\IdentityInterface;

/**
 * Category policy
 */
class CategoryPolicy
{
    /**
     * Check if $user can add Category
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Category $Category)
    {
        // All logged in users can create qr codes.
        return $this->isAdmin($user, $Category);
    }

    /**
     * Check if $user can edit Category
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Category $Category)
    {
        return $this->isAdmin($user, $Category);
    }

    /**
     * Check if $user can delete Category
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Category $Category)
    {
        return $this->isAdmin($user, $Category);
    }

    /**
     * Check if $user can view Category
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    public function canView(IdentityInterface $user, Category $Category)
    {
        // All logged in users can view a qr code.
        return true;
    }

    protected function isAdmin(IdentityInterface $user, Category $Category)
    {
        return $user->getOriginalData()->is_admin ? true : false;
    }
}
