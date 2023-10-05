<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Cake\ORM\Query\SelectQuery;

/**
 * QrCodesTable policy
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
        }

        return $query->find('active');
    }
}
