<?php
declare(strict_types=1);

namespace App\Policy;

use App\Controller\QrImagesController;
use App\Model\Entity\User;

/**
 * Admin QrImages Controller policy
 */
class QrImagesControllerPolicy extends AppControllerPolicy
{
    /**
     * Anyone can see the Image in the admin.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\QrImagesController $QrImagesController
     * @return bool
     */
    public function canShow(?User $user, QrImagesController $QrImagesController): bool
    {
        return true;
    }
}
