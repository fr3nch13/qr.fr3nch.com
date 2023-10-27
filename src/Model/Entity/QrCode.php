<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Exception\QrCodeException;
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
 * @property string|null $color The default hexidecimal color to use, if null, then the darkcolor in the config/app_local.php will be used.
 *
 * @property string|null $path (Virtual field) Path to the generated COLOR QR Code file with the color of this entity. If falls back to the $path_dark
 * @property string|null $path_dark (Virtual field) Path to the generated DARK QR Code file.
 * @property string|null $path_light (Virtual field) Path to the generated LIGHT QR Code file.
 * @property string|null $color_active (Virtual field) The actual color being used. Set acter the path is requested.
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
        'color_active' => true,
        'color' => true,
        'path' => true,
        'path_light' => true,
        'path_dark' => true,
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

    protected ?string $_color_active = null;

    /**
     * Gets the active color
     *
     * @return string|null The path to the generated QR Code.
     */
    protected function _getColorActive(): ?string
    {
        return $this->_color_active;
    }

    /**
     * Sets the active color
     *
     * @return void
     */
    protected function _setColorActive(string $color): void
    {
        $this->_color_active = $this->validateColor($color);
    }

    /**
     * Gets the path to the generated QR Code
     *
     * @return string|null The path to the generated QR Code.
     */
    protected function _getPath(): ?string
    {
        return $this->getImagePath();
    }

    /**
     * Gets the path to the generated QR Code
     *
     * @return string|null The path to the generated QR Code.
     */
    protected function _getPathLight(): ?string
    {
        $color = Configure::read('QrCode.lightcolor', 'FFFFFF');

        return $this->getImagePath($color);
    }

    /**
     * Gets the path to the generated QR Code
     *
     * @return string|null The path to the generated QR Code.
     */
    protected function _getPathDark(): ?string
    {
        $color = Configure::read('QrCode.darkcolor', '000000');

        return $this->getImagePath($color);
    }

    /**
     * Checks the path, and tries to make the codes, then returns the path.
     *
     * @param ?string $color If we should use a custom color
     * @return string The path to the file.
     */
    public function getImagePath(?string $color = null): ?string
    {
        // can also be a blank/empty string
        if ($color === null || !trim($color)) {
            $color = $this->color;
        }
        // color not set for entity
        if ($color === null || !trim($color)) {
            $color = Configure::read('QrCode.darkcolor', '000000');
        }

        $this->_setColorActive($color);

        $path = Configure::read('App.paths.qr_codes', TMP . 'qr_codes') .
            DS .
            $this->id .
            '-' . $this->_getColorActive() .
            '.svg';

        if (!file_exists($path) || $this->regenerate) {
            try {
                $QR = new PhpQrGenerator($this);
                if ($this->_getColorActive()) {
                    $QR->setColor($this->_getColorActive());
                }
                $QR->generate();
            } catch (QRCodeOutputException $e) {
                Log::write('error', __('Unable to generate a missing code to: `{0}`.' .
                    ' Exception: `{1}` Message: `{2}`', [
                    $path,
                    get_class($e),
                    $e->getMessage(),
                ]));
            } catch (QrCodeException $e) {
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

    /**
     * Validates the color.
     *
     * @param string $color
     * @return string The validated, and cleaned color.
     * @throws \App\Exception\QrCodeException If the color isn't present, or is invalid
     */
    public function validateColor(string $color): string
    {
        $color = strtolower($color);
        $color = str_replace('#', '', $color);
        if (strlen($color) !== 6 || !preg_match('!^[a-f0-9]{6}$!', $color)) {
            throw new QrCodeException(__('Invalid Color: {0}', [
                $color,
            ]));
        }

        return $color;
    }
}
