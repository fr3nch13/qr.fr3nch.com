<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\QrCode;
use Authorization\IdentityInterface;

/**
 * QrCode policy
 */
class QrCodePolicy
{
    /**
     * Check if $user can add QrCode
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\QrCode $qrCode
     * @return bool
     */
    public function canAdd(IdentityInterface $user, QrCode $qrCode)
    {
        // All logged in users can create qr codes.
        return true;
    }

    /**
     * Check if $user can edit QrCode
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\QrCode $qrCode
     * @return bool
     */
    public function canEdit(IdentityInterface $user, QrCode $qrCode)
    {
        return $this->isCreator($user, $qrCode);
    }

    /**
     * Check if $user can delete QrCode
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\QrCode $qrCode
     * @return bool
     */
    public function canDelete(IdentityInterface $user, QrCode $qrCode)
    {
        return $this->isCreator($user, $qrCode);
    }

    /**
     * Check if $user can view QrCode
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\QrCode $qrCode
     * @return bool
     */
    public function canView(IdentityInterface $user, QrCode $qrCode)
    {
        // All logged in users can view a qr code.
        return true;
    }

    protected function isCreator(IdentityInterface $user, QrCode $qrCode)
    {
        return $qrCode->user_id === $user->getIdentifier();
    }
}
