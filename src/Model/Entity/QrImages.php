<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * QrImages Entity
 *
 * @property int $id
 * @property string $name
 * @property string $filename
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property bool $is_active
 * @property int $qr_code_id
 *
 * @property \App\Model\Entity\QrCode $qr_code
 */
class QrImages extends Entity
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
        'name' => true,
        'created' => true,
        'modified' => true,
        'is_active' => true,
        'qr_code_id' => true,
        'qr_code' => true,
    ];
}
