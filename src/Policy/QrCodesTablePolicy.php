<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Cake\ORM\Query\SelectQuery;

/**
 * QrCode policy
 */
class QrCodesTablePolicy
{
    /**
     * Check if $user can view the list of images for a QR Code
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \Cake\ORM\Query\SelectQuery $query
     * @return bool
     */
    public function scopeIndex(?User $user, SelectQuery $query): SelectQuery
    {
        if ($user) {
            return $query;
            //return $this->isCreator($user, $query) || $user->isAdmin();
        }

        return $query->find('active');
    }

    /**
     * Check if $user created the QrCode
     *
     * If this is being checked, with a null, user, it's most likely
     * canShow, and the image is inactive.
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \Cake\ORM\Query\SelectQuery $query
     * @return bool
     */
    protected function isCreator(User $user, SelectQuery $query): bool
    {
        return true;
    }
}
