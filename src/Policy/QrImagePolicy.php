<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\QrImage;
use App\Model\Entity\User;

/**
 * QrImage policy
 */
class QrImagePolicy
{
    /**
     * Check if $user can view the actual image.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \App\Model\Entity\QrImage $QrImage
     * @return bool
     */
    public function canShow(?User $user, QrImage $QrImage): bool
    {
        // All users can view active.
        if ($QrImage->is_active) {
            return true;
        }

        // otherwide you need to own the qr code, or be an admin
        return $this->isCreator($user, $QrImage) || $user->isAdmin();
    }

    /**
     * Check if $user can add QrImage
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\QrImage $QrImage
     * @return bool
     */
    public function canAdd(User $user, QrImage $QrImage): bool
    {
        return $this->isCreator($user, $QrImage) || $user->isAdmin();
    }

    /**
     * Check if $user can edit QrImage
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\QrImage $QrImage
     * @return bool
     */
    public function canEdit(User $user, QrImage $QrImage): bool
    {
        return $this->isCreator($user, $QrImage) || $user->isAdmin();
    }

    /**
     * Check if $user can delete QrImage
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\QrImage $QrImage
     * @return bool
     */
    public function canDelete(User $user, QrImage $QrImage): bool
    {
        return $this->isCreator($user, $QrImage) || $user->isAdmin();
    }

    /**
     * Check if $user created the QrImage
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \App\Model\Entity\QrImage $QrImage
     * @return bool
     */
    protected function isCreator(User $user, QrImage $QrImage): bool
    {
        // make sure the qr code is attached.
        if (!$QrImage->isEmpty('qr_code_id')) {
            return false;
        }

        if (!$QrImage->hasValue('qr_code')) {
            return false;
        }

        return $QrImage->qr_code->user_id === $user->id;
    }
}
