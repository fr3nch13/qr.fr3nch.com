<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Lib\PhpQrGenerator;
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
 * @property int $hits
 * @property bool $is_active
 * @property int|null $source_id
 * @property int|null $user_id
 *
 * Virtual field
 * @property string|null $path Path to the generated QR Code file.
 *
 * @property \App\Model\Entity\Source $source
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Category[] $categories
 * @property \App\Model\Entity\QrImages[] $qr_images
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
        'hits' => true,
        'is_active' => true,
        'source_id' => true,
        'user_id' => true,
        'source' => true,
        'user' => true,
        'categories' => true,
        'qr_images' => true,
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

    /**
     * If we should regenerate the QR code.
     * Set this like $qrCode->regenerate = true;
     * before calling $qrCode->path;
     *
     * @var bool
     */
    public bool $regenerate = false;

    /**
     * Gets the path to the generated QR Code
     *
     * @return string|null The path to the generated QR Code.
     */
    protected function _getPath(): ?string
    {
        $path = TMP . 'qr_codes' . DS . $this->id . '.png';
        if (!file_exists($path) || $this->regenerate) {
            $QR = new PhpQrGenerator($this);
            $QR->generate();

        }

        if (is_readable($path)) {
            return $path;
        }

        return null;
    }
}
