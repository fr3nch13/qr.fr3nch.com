<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * QrCodesTag Entity
 *
 * @property int $id
 * @property int $tag_id
 * @property int $qr_code_id
 *
 * @property \App\Model\Entity\Tag $tag
 * @property \App\Model\Entity\QrCode $qr_code
 */
class QrCodesTag extends Entity
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
        'tag_id' => true,
        'qr_code_id' => true,
        'tag' => true,
        'qr_code' => true,
    ];
}
