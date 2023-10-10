<?php
declare(strict_types=1);

namespace App\Policy\Admin;

use App\Controller\Admin\QrImagesController;
use App\Model\Entity\User;

/**
 * Admin QrImages Controller policy
 */
class QrImagesControllerPolicy extends AppControllerPolicy
{
    /**
     * Must be an logged in to view a list of Images related to a QR Code.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\QrImagesController $QrImagesController
     * @return bool
     */
    public function canQrCode(?User $user, QrImagesController $QrImagesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be an logged in can see the Image in the admin.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\QrImagesController $QrImagesController
     * @return bool
     */
    public function canShow(?User $user, QrImagesController $QrImagesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be an logged in to view an Image details page
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\QrImagesController $QrImagesController
     * @return bool
     */
    public function canView(?User $user, QrImagesController $QrImagesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be an logged in to add an Image.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\QrImagesController $QrImagesController
     * @return bool
     */
    public function canAdd(?User $user, QrImagesController $QrImagesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be an logged in to edit an Image.
     * Object policy to test editing the specific image is done in \App\Policy\QrCodePolicy::canEdit()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\QrImagesController $QrImagesController
     * @return bool
     */
    public function canEdit(?User $user, QrImagesController $QrImagesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be an logged in to delete an Image.
     * Object policy to test editing the specific image is done in \App\Policy\QrCodePolicy::canDelete()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\QrImagesController $QrImagesController
     * @return bool
     */
    public function canDelete(?User $user, QrImagesController $QrImagesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }
}
