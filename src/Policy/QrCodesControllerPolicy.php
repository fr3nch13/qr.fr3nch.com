<?php
declare(strict_types=1);

namespace App\Policy;

use App\Controller\QrCodesController;
use App\Model\Entity\User;

/**
 * QrCodes Controller policy
 */
class QrCodesControllerPolicy extends AppControllerPolicy
{
    /**
     * Anyone can be forwarded.
     * Specific checks for inactive codes are done at the controller directly.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\QrCodesController $QrCodesController
     * @return bool
     */
    public function canForward(?User $user, QrCodesController $QrCodesController): bool
    {
        return true;
    }

    /**
     * Anyone can see the QR Code Image.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\QrCodesController $QrCodesController
     * @return bool
     */
    public function canShow(?User $user, QrCodesController $QrCodesController): bool
    {
        return true;
    }

    /**
     * Anyone can view a list of qr codes.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\QrCodesController $QrCodesController
     * @return bool
     */
    public function canIndex(?User $user, QrCodesController $QrCodesController): bool
    {
        return true;
    }

    /**
     * Anyone can view a qr code.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\QrCodesController $QrCodesController
     * @return bool
     */
    public function canView(?User $user, QrCodesController $QrCodesController): bool
    {
        return true;
    }

    /**
     * Must be an logged in to add a qr code
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\QrCodesController $QrCodesController
     * @return bool
     */
    public function canAdd(?User $user, QrCodesController $QrCodesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be an logged in to edit a qr code
     * Object policy to test editing the specific qr code is done in \App\Policy\QrCodePolicy::canEdit()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\QrCodesController $QrCodesController
     * @return bool
     */
    public function canEdit(?User $user, QrCodesController $QrCodesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be an logged in to delete a qr code
     * Object policy to test editing the specific qr code is done in \App\Policy\QrCodePolicy::canDelete()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\QrCodesController $QrCodesController
     * @return bool
     */
    public function canDelete(?User $user, QrCodesController $QrCodesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }
}
