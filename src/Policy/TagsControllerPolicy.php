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
}
