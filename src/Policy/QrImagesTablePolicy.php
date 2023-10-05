<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Cake\ORM\Query\SelectQuery;

/**
 * QrImagesTable policy
 */
class QrImagesTablePolicy
{
    /**
     * Check if $user can view the list of images for a QR Code.
     * Right now, any logged in user can view a list of all images.
     *
     * @param \App\Model\Entity\User $user The identity object.
     * @param \Cake\ORM\Query\SelectQuery $query
     * @return bool
     */
    public function scopeQrCode(User $user, SelectQuery $query, mixed ...$optionalArgs): SelectQuery
    {
        return $query;
    }
}
