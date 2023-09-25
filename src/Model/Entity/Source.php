<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Source Entity
 *
 * @property int $id
 * @property string $key
 * @property string $name
 * @property string $description
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $user_id
 *
 * @property \App\Model\Entity\QrCode[] $qr_codes
 * @property \App\Model\Entity\User $user
 */
class Source extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'key' => true,
        'name' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'user_id' => true,
        'qr_codes' => true,
        'user' => true,
    ];
}
