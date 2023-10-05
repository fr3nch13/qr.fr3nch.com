<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Cake\ORM\Query\SelectQuery;

/**
 * QrImage policy
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

    /**
     * Check if $user created the QrImage
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
