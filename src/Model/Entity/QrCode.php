<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * QrCode Entity
 *
 * @property int $id
 * @property string $qrkey
 * @property string $name
 * @property string $description
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property string $url
 * @property int|null $source_id
 * @property int|null $user_id
 *
 * @property \App\Model\Entity\Source $source
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Category[] $categories
 * @property \App\Model\Entity\Tag[] $tags
 */
class QrCode extends Entity
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
        'qrkey' => true,
        'name' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'url' => true,
        'source_id' => true,
        'user_id' => true,
        'source' => true,
        'user' => true,
        'categories' => true,
        'tags' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected array $_hidden = [
        'user_id',
        'user',
    ];
}
