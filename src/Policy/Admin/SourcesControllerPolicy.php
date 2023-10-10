<?php
declare(strict_types=1);

namespace App\Policy\Admin;

use App\Controller\Admin\SourcesController;
use App\Model\Entity\User;

/**
 * Admin Sources Controller policy
 */
class SourcesControllerPolicy extends AppControllerPolicy
{
    /**
     * Must be logged in to view a list of Sources.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\SourcesController $SourcesController
     * @return bool
     */
    public function canIndex(?User $user, SourcesController $SourcesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be logged in to view a Source.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\SourcesController $SourcesController
     * @return bool
     */
    public function canView(?User $user, SourcesController $SourcesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be an admin to add a Source
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\SourcesController $SourcesController
     * @return bool
     */
    public function canAdd(?User $user, SourcesController $SourcesController): bool
    {
        if (!$user) {
            return false;
        }

        // Only admins can add a source
        return $user->isAdmin();
    }

    /**
     * Must be an admin edit a Source
     * Object policy to test editing the specific source is done in \App\Policy\SourcePolicy::canEdit()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\SourcesController $SourcesController
     * @return bool
     */
    public function canEdit(?User $user, SourcesController $SourcesController): bool
    {
        if (!$user) {
            return false;
        }

        // Only admins can add a source
        return $user->isAdmin();
    }

    /**
     * Must be an admin to delete a Source
     * Object policy to test editing the specific source is done in \App\Policy\SourcePolicy::canDelete()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\SourcesController $SourcesController
     * @return bool
     */
    public function canDelete(?User $user, SourcesController $SourcesController): bool
    {
        if (!$user) {
            return false;
        }

        // Only admins can add a source
        return $user->isAdmin();
    }
}
