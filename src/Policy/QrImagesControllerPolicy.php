<?php
declare(strict_types=1);

namespace App\Policy;

use App\Controller\QrImagesController;
use App\Model\Entity\User;

/**
 * QrImages Controller policy
 */
class QrImagesControllerPolicy extends BaseControllerPolicy
{
    /**
     * Must be an logged in to view a list of images related to a code.
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\QrImagesController $QrImagesController
     * @return bool
     */
    public function canQrCode(?User $identity, QrImagesController $QrImagesController): bool
    {
        if (!$identity) {
            return false;
        }

        return true;
    }

    /**
     * Anyone can see the Image.
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\QrImagesController $QrImagesController
     * @return bool
     */
    public function canShow(?User $identity, QrImagesController $QrImagesController): bool
    {
        return true;
    }

    /**
     * Must be an logged in to view an image details page
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\QrImagesController $QrImagesController
     * @return bool
     */
    public function canView(?User $identity, QrImagesController $QrImagesController): bool
    {
        if (!$identity) {
            return false;
        }

        return true;
    }

    /**
     * Must be an logged in to add a image
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\QrImagesController $QrImagesController
     * @return bool
     */
    public function canAdd(?User $identity, QrImagesController $QrImagesController): bool
    {
        if (!$identity) {
            return false;
        }

        return true;
    }

    /**
     * Must be an logged in to edit a image
     * Object policy to test editing the specific image is done in \App\Policy\QrCodePolicy::canEdit()
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\QrImagesController $QrImagesController
     * @return bool
     */
    public function canEdit(?User $identity, QrImagesController $QrImagesController): bool
    {
        if (!$identity) {
            return false;
        }

        return true;
    }

    /**
     * Must be an logged in to delete a image
     * Object policy to test editing the specific image is done in \App\Policy\QrCodePolicy::canDelete()
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Controller\QrImagesController $QrImagesController
     * @return bool
     */
    public function canDelete(?User $identity, QrImagesController $QrImagesController): bool
    {
        if (!$identity) {
            return false;
        }

        return true;
    }
}
