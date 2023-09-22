<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Tag;
use Authorization\IdentityInterface;

/**
 * Tag policy
 */
class TagPolicy
{
    /**
     * Check if $user can add Tag
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Tag $Tag): bool
    {
        // All logged in users can create qr codes.
        return true;
    }

    /**
     * Check if $user can edit Tag
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Tag $Tag): bool
    {
        return $this->isCreator($user, $Tag);
    }

    /**
     * Check if $user can delete Tag
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Tag $Tag): bool
    {
        return $this->isCreator($user, $Tag);
    }

    /**
     * Check if $user can view Tag
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canView(IdentityInterface $user, Tag $Tag): bool
    {
        // All logged in users can view a qr code.
        return true;
    }

    protected function isCreator(IdentityInterface $user, Tag $Tag)
    {
        return $Tag->user_id === $user->getIdentifier();
    }

    protected function isAdmin(IdentityInterface $user, Tag $Tag)
    {
        return $user->getOriginalData()->is_admin ? true : false;
    }
}
