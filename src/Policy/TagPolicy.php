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
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canAdd(IdentityInterface $identity, Tag $Tag): bool
    {
        // All logged in users can create qr codes.
        return true;
    }

    /**
     * Check if $user can edit Tag
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canEdit(IdentityInterface $identity, Tag $Tag): bool
    {
        return $this->isCreator($identity, $Tag) || $this->isAdmin($identity, $Tag);
    }

    /**
     * Check if $user can delete Tag
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canDelete(IdentityInterface $identity, Tag $Tag): bool
    {
        return $this->isCreator($identity, $Tag) || $this->isAdmin($identity, $Tag);
    }

    /**
     * Check if $user can view Tag
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    public function canView(IdentityInterface $identity, Tag $Tag): bool
    {
        // All logged in users can view a qr code.
        return true;
    }

    /**
     * Check if $user created the Tag
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    protected function isCreator(IdentityInterface $identity, Tag $Tag): bool
    {
        return $Tag->user_id === $identity->getIdentifier();
    }

    /**
     * Check if $user is an Admin
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\Tag $Tag
     * @return bool
     */
    protected function isAdmin(IdentityInterface $identity, Tag $Tag): bool
    {
        /** @var \App\Model\Entity\User $entity */
        $entity = $identity->getOriginalData();

        return $entity->is_admin ? true : false;
    }
}
