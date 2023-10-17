<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Tag;
use App\Model\Entity\User;

/**
 * Tag policy
 */
class TagPolicy
{
    /**
     * Logged in can add Tag
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canAdd(User $user, Tag $Tag): bool
    {
        // All logged in users can create tags
        return true;
    }

    /**
     * Admins and Creators can edit Tag
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canEdit(User $user, Tag $Tag): bool
    {
        return $this->isCreator($user, $Tag) || $user->isAdmin();
    }

    /**
     * Admins and Creators can delete Tag
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canDelete(User $user, Tag $Tag): bool
    {
        return $this->isCreator($user, $Tag) || $user->isAdmin();
    }

    /**
     * Check if $user created the Tag
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    protected function isCreator(User $user, Tag $Tag): bool
    {
        return $Tag->user_id === $user->id;
    }
}
