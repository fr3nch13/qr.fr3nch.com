<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Cake\ORM\Query\SelectQuery;

/**
 * SourcesTable policy
 */
class SourcesTablePolicy
{
    /**
     * Check if $user can view the list of Sources.
     *
     * @param \App\Model\Entity\User|null $user The identity object.
     * @param \Cake\ORM\Query\SelectQuery $query The initial query.
     * @return \Cake\ORM\Query\SelectQuery The updated query.
     */
    public function scopeIndex(?User $user, SelectQuery $query): SelectQuery
    {
        return $query;
    }
}
