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
 * @property string|null $path (Virtual field) Path to the generated QR Code file.
 *
 * @property \App\Model\Entity\Source $source The source, mainly used internally to track where the code is located.
 * @property \App\Model\Entity\User $user The user that created and/or owns the code.
 * @property \App\Model\Entity\QrImage[] $qr_images List of Images that this code owns.
 * @property \App\Model\Entity\Tag[] $tags List of Tags that this is assigned to.
 */
class QrCode extends Entity
{
    use ThumbTrait;

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
        'path_sm' => true,
        'path_md' => true,
        'path_lg' => true,
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
        // set in config/app.php or config/app_local.php
        $path = $this->getImagePath();
        if (!$path) {
            return null;
        }

        if (!file_exists($path) || $this->regenerate) {
            $this->regenerateThumb = true;
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

    /**
     * Return where the path to the image should be.
     * No checking here
     *
     * @return string The path.
     */
    public function getImagePath(): ?string
    {
        return Configure::read('App.paths.qr_codes', TMP . 'qr_codes') . DS . $this->id . '.png';
    }
}
