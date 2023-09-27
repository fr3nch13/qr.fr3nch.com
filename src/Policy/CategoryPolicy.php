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
     * Check if $user is an Admin
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Category $Category
     * @return bool
     * @todo I think their policy stuff doesn't match their documentation. Going with how it's working right now.
     */
    protected function isAdmin(IdentityInterface $identity, Category $Category): bool
    {
        /** @var \App\Model\Entity\User $entity */
        $entity = $identity->getOriginalData();

        return $entity->is_admin ? true : false;
    }
}
