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
     * Check if $user can view the list of images for a QR Code.
     * Guests can see the active codes.
     * Regular users can see active codes, and inactive ones owned by them.
     * Admin users can see all codes.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \Cake\ORM\Query\SelectQuery $query The initial query.
     * @return \Cake\ORM\Query\SelectQuery The updated query.
     */
    public function scopeIndex(?User $user, SelectQuery $query): SelectQuery
    {
        if ($user) {
            if (!$user->is_admin) {
                return $query->find('ownedBy', user: $user);
            }

            // admins
            return $query;
        }

        // quests
        return $query->find('active');
    }
}
