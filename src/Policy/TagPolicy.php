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
     * All users can view Tag
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canView(?User $identity, Tag $Tag): bool
    {
        return true;
    }

    /**
     * All Users can add Tag
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canAdd(User $identity, Tag $Tag): bool
    {
        // All logged in users can create tags
        return true;
    }

    /**
     * Admins and Createors can edit Tag
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canEdit(User $identity, Tag $Tag): bool
    {
        return $this->isCreator($identity, $Tag) || $identity->isAdmin();
    }

    /**
     * Admins and Createors can delete Tag
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canDelete(User $identity, Tag $Tag): bool
    {
        return $this->isCreator($identity, $Tag) || $identity->isAdmin();
    }

    /**
     * Check if $user created the Tag
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    protected function isCreator(User $identity, Tag $Tag): bool
    {
        return $Tag->user_id === $identity->id;
    }
}
