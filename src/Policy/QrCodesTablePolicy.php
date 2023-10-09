<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Cake\Database\Expression\QueryExpression;
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
                // either owner, or active.
                return $query->where(function (QueryExpression $exp, SelectQuery $query) use ($user) {

                    return $exp->or([
                        ['QrCodes.user_id' => $user->id],
                        ['QrCodes.is_active' => true],
                    ]);
                });
            }

            // admins
            return $query;
        }

        // quests
        return $query->find('active');
    }
}
