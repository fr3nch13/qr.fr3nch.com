<?php
declare(strict_types=1);

namespace App\Policy\Admin;

use App\Controller\Admin\QrCodesController;
use App\Model\Entity\User;

/**
 * Admin QrCodes Controller policy
 */
class QrCodesControllerPolicy extends AppControllerPolicy
{
    /**
     * Must be an logged in to view the QR Code Image.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\QrCodesController $QrCodesController
     * @return bool
     */
    public function canShow(?User $user, QrCodesController $QrCodesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be logged in view a list of QR Codes.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\QrCodesController $QrCodesController
     * @return bool
     */
    public function canIndex(?User $user, QrCodesController $QrCodesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be an logged in to view a QR Code.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\QrCodesController $QrCodesController
     * @return bool
     */
    public function canView(?User $user, QrCodesController $QrCodesController): bool
    {
        if (!$user) {
            return false;
        }

        return true;
    }

    /**
     * Must be an logged in to add a QR Code.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\QrCodesController $QrCodesController
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
     * Must be an logged in to edit a QR Code.
     * Object policy to test editing the specific Qr Code is done in \App\Policy\QrCodePolicy::canEdit()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\QrCodesController $QrCodesController
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
     * Must be an logged in to delete a QR Code.
     * Object policy to test editing the specific Qr Code is done in \App\Policy\QrCodePolicy::canDelete()
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\Admin\QrCodesController $QrCodesController
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
