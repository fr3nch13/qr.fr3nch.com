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
     * Anyone can see the Qr Code Image.
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
     * Anyone can view a list of Qr Codes.
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
     * Anyone can view a Qr Code.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Controller\QrCodesController $QrCodesController
     * @return bool
     */
    public function canView(?User $user, QrCodesController $QrCodesController): bool
    {
        return true;
    }
}
