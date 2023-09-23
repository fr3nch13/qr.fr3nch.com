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
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canAdd(IdentityInterface $identity, QrCode $QrCode): bool
    {
        // All logged in users can create qr codes.
        return true;
    }

    /**
     * Check if $user can edit QrCode
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canEdit(IdentityInterface $identity, QrCode $QrCode): bool
    {
        return $this->isCreator($identity, $QrCode) || $this->isAdmin($identity, $QrCode);
    }

    /**
     * Check if $user can delete QrCode
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canDelete(IdentityInterface $identity, QrCode $QrCode): bool
    {
        return $this->isCreator($identity, $QrCode) || $this->isAdmin($identity, $QrCode);
    }

    /**
     * Check if $user can view QrCode
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    public function canView(IdentityInterface $identity, QrCode $QrCode): bool
    {
        // All logged in users can view a qr code.
        return true;
    }

    /**
     * Check if $user created the QrCode
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    protected function isCreator(IdentityInterface $identity, QrCode $QrCode): bool
    {
        return $QrCode->user_id === $identity->getIdentifier();
    }

    /**
     * Check if $user is an Admin
     *
     * @param \Authorization\Identity $identity The identity object.
     * @param \App\Model\Entity\QrCode $QrCode
     * @return bool
     */
    protected function isAdmin(IdentityInterface $identity, QrCode $QrCode): bool
    {
        /** @var \App\Model\Entity\User $user */
        $user = $identity->getOriginalData();

        return $user->is_admin ? true : false;
    }
}
