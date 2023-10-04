<?php
declare(strict_types=1);

namespace App\Policy;

use App\Controller\SourcesController;
use App\Model\Entity\User;

/**
 * Sources Controller policy
 */
class SourcesControllerPolicy extends BaseControllerPolicy
{
    /**
     * Must be logged in to view a list of sources.
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\SourcesController $SourcesController
     * @return bool
     */
    public function canIndex(?User $identity, SourcesController $SourcesController): bool
    {
        if (!$identity) {
            return false;
        }

        return true;
    }

    /**
     * Must be logged in to view a source.
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\SourcesController $SourcesController
     * @return bool
     */
    public function canView(?User $identity, SourcesController $SourcesController): bool
    {
        if (!$identity) {
            return false;
        }

        return true;
    }

    /**
     * Must be an admin to add a source
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\SourcesController $SourcesController
     * @return bool
     */
    public function canAdd(?User $identity, SourcesController $SourcesController): bool
    {
        if (!$identity) {
            return false;
        }

        // Only admins can add a category
        return $identity->isAdmin();
    }

    /**
     * Must be an admin edit a source
     * Object policy to test editing the specific source is done in \App\Policy\SourcePolicy::canEdit()
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\SourcesController $SourcesController
     * @return bool
     */
    public function canEdit(?User $identity, SourcesController $SourcesController): bool
    {
        if (!$identity) {
            return false;
        }

        // Only admins can add a category
        return $identity->isAdmin();
    }

    /**
     * Must be an admin to delete a source
     * Object policy to test editing the specific source is done in \App\Policy\SourcePolicy::canDelete()
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\SourcesController $SourcesController
     * @return bool
     */
    public function canDelete(?User $identity, SourcesController $SourcesController): bool
    {
        if (!$identity) {
            return false;
        }

        // Only admins can add a category
        return $identity->isAdmin();
    }
}
