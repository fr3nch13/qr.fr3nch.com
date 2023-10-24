<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Lib\PhpQrGenerator;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\ORM\Entity;
use chillerlan\QRCode\Output\QRCodeOutputException;

/**
 * QrCode Entity
 *
 * @property int $id
 * @property string $qrkey The unique string id for this code.
 * @property string $name The human readable name
 * @property string $description Description of the code.
 * @property \Cake\I18n\DateTime|null $created Then it was created (handled by the Timestamp Behavior).
 * @property \Cake\I18n\DateTime|null $modified Then it was last created (handled by the Timestamp Behavior).
 * @property string $url The URL to forward the user to when they scan the code.
 * @property int $hits How many times this code has forwarded a user.
 * @property bool $is_active If this code is active/published.
 * @property int|null $source_id See $source below.
 * @property int|null $user_id see $user below.
 * @property \Cake\I18n\DateTime|null $last_hit The last time the code forwarded a user.
 *
 * @property string|null $path (Virtual field) Path to the generated DARK QR Code file.
 * @property string|null $path_dark (Virtual field) Path to the generated DARK QR Code file.
 * @property string|null $path_light (Virtual field) Path to the generated LIGHT QR Code file.
 *
 * @property \App\Model\Entity\Source $source The source, mainly used internally to track where the code is located.
 * @property \App\Model\Entity\User $user The user that created and/or owns the code.
 * @property \App\Model\Entity\QrImage[] $qr_images List of Images that this code owns.
 * @property \App\Model\Entity\Tag[] $tags List of Tags that this is assigned to.
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
        'last_hit' => true,
        'path' => true,
        'path_dark' => true,
        'path_light' => true,
        'source' => true,
        'user' => true,
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
        return $this->_getPathDark();
    }

    /**
     * Gets the path to the generated QR Code
     *
     * @return string|null The path to the generated QR Code.
     */
    protected function _getPathDark(): ?string
    {
        // set in config/app.php or config/app_local.php
        return $this->getImagePath(false);
    }

    /**
     * Gets the path to the generated QR Code
     *
     * @return string|null The path to the generated QR Code.
     */
    protected function _getPathLight(): ?string
    {
        // set in config/app.php or config/app_local.php
        return $this->getImagePath(true);
    }

    /**
     * Checks the path, and tries to make the codes, then returns the path.
     *
     * @param bool $light If we should return the light, or dark path.
     * @return string The path to the file.
     */
    public function getImagePath(bool $light = false): ?string
    {
        $end = $light ? '-light' : '-dark';

        $path = Configure::read('App.paths.qr_codes', TMP . 'qr_codes') .
            DS .
            $this->id .
            $end .
            '.svg';

        if (!file_exists($path) || $this->regenerate) {
            try {
                $QR = new PhpQrGenerator($this);
                $QR->generate();
            } catch (QRCodeOutputException $e) {
                Log::write('error', __('Unable to generate a missing code to: `{0}`.' .
                    ' Exception: `{1}` Message: `{2}`', [
                    $path,
                    get_class($e),
                    $e->getMessage(),
                ]));
            }
        }

        if (is_readable($path)) {
            return $path;
        }

        return null;
    }
}
