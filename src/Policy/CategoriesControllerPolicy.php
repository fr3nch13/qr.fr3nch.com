<?php
declare(strict_types=1);

namespace App\Policy;

use App\Controller\CategoriesController;
use App\Model\Entity\User;

/**
 * Categories Controller policy
 */
class CategoriesControllerPolicy extends BaseControllerPolicy
{
    /**
     * Anyone can view a list of categories.
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\CategoriesController $CategoriesController
     * @return bool
     */
    public function canIndex(?User $identity, CategoriesController $CategoriesController): bool
    {
        return true;
    }

    /**
     * Anyone can view a category.
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\CategoriesController $CategoriesController
     * @return bool
     */
    public function canView(?User $identity, CategoriesController $CategoriesController): bool
    {
        return true;
    }

    /**
     * Must be an admin to add a category
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\CategoriesController $CategoriesController
     * @return bool
     */
    public function canAdd(?User $identity, CategoriesController $CategoriesController): bool
    {
        if (!$identity) {
            return false;
        }

        // Only admins can add a category
        return $identity->isAdmin();
    }

    /**
     * Must be an admin to edit a category
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\CategoriesController $CategoriesController
     * @return bool
     */
    public function canEdit(?User $identity, CategoriesController $CategoriesController): bool
    {
        if (!$identity) {
            return false;
        }

        // Only admins can edit a category
        return $identity->isAdmin();
    }

    /**
     * Must be an admin to delete a category
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\CategoriesController $CategoriesController
     * @return bool
     */
    public function canDelete(?User $identity, CategoriesController $CategoriesController): bool
    {
        if (!$identity) {
            return false;
        }

        // Only admins can delete a category
        return $identity->isAdmin();
    }
}
