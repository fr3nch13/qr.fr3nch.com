<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Category Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $parent_id
 * @property int|null $user_id
 *
 * @property \App\Model\Entity\Category $parent_category
 * @property \App\Model\Entity\Category[] $child_categories
 * @property \App\Model\Entity\QrCode[] $qr_codes
 * @property \App\Model\Entity\User $user
 */
class Category extends Entity
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
        'description' => true,
        'created' => true,
        'modified' => true,
        'parent_id' => true,
        'parent_category' => true,
        'child_categories' => true,
        'user_id' => true,
        'qr_codes' => true,
        'user' => true,
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
