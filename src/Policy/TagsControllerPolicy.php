<?php
declare(strict_types=1);

namespace App\Policy;

use App\Controller\TagsController;
use App\Model\Entity\User;

/**
 * Tags Controller policy
 */
class TagsControllerPolicy extends AppControllerPolicy
{
    /**
     * Anyone can view a list of tags.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\TagsController $TagsController
     * @return bool
     */
    public function canIndex(?User $user, TagsController $TagsController): bool
    {
        return true;
    }

    /**
     * Anyone can view a tag.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\TagsController $TagsController
     * @return bool
     */
    public function canView(?User $user, TagsController $TagsController): bool
    {
        return true;
    }

    /**
     * Must be an logged in to add a tag
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\TagsController $TagsController
     * @return bool
     */
    public function canAdd(?User $user, TagsController $TagsController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be logged in to edit a tag
     * Object policy to test editing the specific tag is done in \App\Policy\TagPolicy::canEdit()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\TagsController $TagsController
     * @return bool
     */
    public function canEdit(?User $user, TagsController $TagsController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be logged to delete a tag
     * Object policy to test editing the specific tag is done in \App\Policy\TagPolicy::canDelete()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\TagsController $TagsController
     * @return bool
     */
    public function canDelete(?User $user, TagsController $TagsController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }
}
