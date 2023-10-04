<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\QrCode;
use App\Model\Entity\User;

/**
 * QrCode policy
 */
class QrCodePolicy
{
    /**
     * Check if $user can view qr codes
     *
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canView(?User $identity, QrCode $QrCode): bool
    {
        // All users can view qr codes.
        return true;
    }

    /**
     * Check if $user can add QrCode
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canAdd(User $identity, QrCode $QrCode): bool
    {
        // All logged in users can create qr codes.
        return true;
    }

    /**
     * Check if $user can view the list of images for a qrcode.
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canQrCode(User $identity, QrCode $QrCode): bool
    {
        return $this->isCreator($identity, $QrCode) || $identity->isAdmin();
    }

    /**
     * Check if $user can edit QrCode
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canEdit(User $identity, QrCode $QrCode): bool
    {
        return $this->isCreator($identity, $QrCode) || $identity->isAdmin();
    }

    /**
     * Check if $user can delete QrCode
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canDelete(User $identity, QrCode $QrCode): bool
    {
        return $this->isCreator($identity, $QrCode) || $identity->isAdmin();
    }

    /**
     * Check if $user created the QrCode
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    protected function isCreator(User $identity, QrCode $QrCode): bool
    {
        return $QrCode->user_id === $identity->id;
    }
}
