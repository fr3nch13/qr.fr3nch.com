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
     * Check if $user can view a qr code
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canView(?User $user, QrCode $QrCode): bool
    {
        // All users can view active.
        if ($QrCode->is_active) {
            return true;
        } else {
            // otherwise only admins and owners can view inactive images
            if ($user) {
                return $this->isCreator($user, $QrCode) || $user->isAdmin();
            }
        }

        return false;
    }

    /**
     * Check if $user can view a qr codes' actual code
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canShow(?User $user, QrCode $QrCode): bool
    {
        // All users can view active.
        if ($QrCode->is_active) {
            return true;
        } else {
            // otherwise only admins and owners can view inactive images
            if ($user) {
                return $this->isCreator($user, $QrCode) || $user->isAdmin();
            }
        }

        return false;
    }

    /**
     * Check if the QR Code is allowed to be followed.
     * Allowing as the forward method will do the checking.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Model\Entity\QrCode|null $QrCode
     * @return bool
     */
    public function canForward(?User $user, ?QrCode $QrCode): bool
    {
        return false;
    }

    /**
     * Check if $user can add QrCode
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canAdd(User $user, QrCode $QrCode): bool
    {
        // All logged in users can create qr codes.
        return true;
    }

    /**
     * Check if $user can view the list of images for a qrcode.
     *
     * It's routing over to here, but is called from the QrImagesController::qrCode() method.
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canQrCode(User $user, QrCode $QrCode): bool
    {
        return $this->isCreator($user, $QrCode) || $user->isAdmin();
    }

    /**
     * Check if $user can edit QrCode
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canEdit(User $user, QrCode $QrCode): bool
    {
        return $this->isCreator($user, $QrCode) || $user->isAdmin();
    }

    /**
     * Check if $user can delete QrCode
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canDelete(User $user, QrCode $QrCode): bool
    {
        return $this->isCreator($user, $QrCode) || $user->isAdmin();
    }

    /**
     * Check if $user created the QrCode
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    protected function isCreator(User $user, QrCode $QrCode): bool
    {
        return $QrCode->user_id === $user->id;
    }
}
