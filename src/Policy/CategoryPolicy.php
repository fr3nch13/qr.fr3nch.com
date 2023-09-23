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
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    public function canAdd(IdentityInterface $identity, Category $Category): bool
    {
        // All logged in users can create qr codes.
        return $this->isAdmin($identity, $Category);
    }

    /**
     * Check if $user can edit Category
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    public function canEdit(IdentityInterface $identity, Category $Category): bool
    {
        return $this->isAdmin($identity, $Category);
    }

    /**
     * Check if $user can delete Category
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    public function canDelete(IdentityInterface $identity, Category $Category): bool
    {
        return $this->isAdmin($identity, $Category);
    }

    /**
     * Check if $user can view Category
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    public function canView(IdentityInterface $identity, Category $Category): bool
    {
        // All logged in users can view a qr code.
        return true;
    }

    /**
     * Check if $user created the Category
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    protected function isCreator(IdentityInterface $identity, Category $Category): bool
    {
        return $Category->user_id === $identity->getIdentifier();
    }

    /**
     * Check if $user is an Admin
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     */
    protected function isAdmin(IdentityInterface $identity, Category $Category): bool
    {
        /** @var \App\Model\Entity\User $user */
        $user = $identity->getOriginalData();

        return $user->is_admin ? true : false;
    }
}
