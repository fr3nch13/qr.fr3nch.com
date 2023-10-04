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
     * @param \App\Model\Entity\User|null $identity The identity object.
     * @param \App\Model\Entity\QrImage $QrImage
     * @return bool
     */
    public function canShow(?User $identity, QrImage $QrImage): bool
    {
        // All users can view active.
        if ($QrImage->is_active) {
            return true;
        }

        // otherwide you need to own the qr code, or be an admin
        return $this->isCreator($identity, $QrImage) || $identity->isAdmin();
    }

    /**
     * Check if $user can add QrImage
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\QrImage $QrImage
     * @return bool
     */
    public function canAdd(User $identity, QrImage $QrImage): bool
    {
        return $this->isCreator($identity, $QrImage) || $identity->isAdmin();
    }

    /**
     * Check if $user can edit QrImage
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\QrImage $QrImage
     * @return bool
     */
    public function canEdit(User $identity, QrImage $QrImage): bool
    {
        return $this->isCreator($identity, $QrImage) || $identity->isAdmin();
    }

    /**
     * Check if $user can delete QrImage
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\QrImage $QrImage
     * @return bool
     */
    public function canDelete(User $identity, QrImage $QrImage): bool
    {
        return $this->isCreator($identity, $QrImage) || $identity->isAdmin();
    }

    /**
     * Check if $user created the QrImage
     *
     * @param \App\Model\Entity\User $identity The identity object.
     * @param \App\Model\Entity\QrImage $QrImage
     * @return bool
     */
    protected function isCreator(User $identity, QrImage $QrImage): bool
    {
        // make sure the qr code is attached.
        if (!$QrImage->isEmpty('qr_code_id')) {
            return false;
        }

        if (!$QrImage->hasValue('qr_code')) {
            return false;
        }

        return $QrImage->qr_code->user_id === $identity->id;
    }
}
