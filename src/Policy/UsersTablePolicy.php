<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Cake\ORM\Query\SelectQuery;

/**
 * UsersTable policy
 */
class UsersTablePolicy
{
    /**
     * Check if $user can view the list of Users.
     *
     * I'll have a Coke.
     * This is already limited to Admins.
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
