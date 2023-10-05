<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;

/**
 * QrImage Entity
 *
 * @property int $id
 * @property string $name
 * @property string $ext
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property bool $is_active
 * @property int $imorder
 * @property int $qr_code_id
 *
 * Virtual field
 * @property string|null $path Path to the uploaded image.
 *
 * @property \App\Model\Entity\QrCode $qr_code
 */
class QrImage extends Entity
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
        'ext' => true,
        'created' => true,
        'modified' => true,
        'is_active' => true,
        'imorder' => true,
        'qr_code_id' => true,
        'qr_code' => true,
    ];

    /**
     * Gets the path to the QR Image's file.
     *
     * @return string|null The path to the file.
     */
    protected function _getPath(): ?string
    {
        $path = Configure::read('App.paths.qr_images', TMP . 'qr_images' . DS) .
            $this->qr_code_id . DS .
            $this->id . '.' . $this->ext;

        if (file_exists($path) && is_readable($path)) {
            return $path;
        }

        return null;
    }
}
