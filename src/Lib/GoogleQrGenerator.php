<?php
declare(strict_types=1);

namespace App\Lib;

use App\Model\Entity\QrCode;
use Cake\Core\Configure;
use Cake\Routing\Router;
use GdImage;

/**
 * Library for generating QR codes with Google's Chart API.
 *
 * @link https://developers.google.com/chart/infographics/docs/qr_codes
 */
class GoogleQrGenerator
{
    /**
     * @var string The placeholder url to google's api,
     */
    protected string $googleUrl = 'https://chart.googleapis.com/chart?' .
        'cht=qr&chld={level}|{margin}&chs={size}x{size}&chl={data}';

    /**
     * @var array<string, mixed> Configuration variables.
     */
    public array $config = [
        'level' => 'H',
        'margin' => '1',
        'size' => '200',
        'data' => null,
        'logoPath' => null,
        'qrcolor' => [],
    ];

    /**
     * @var string The data to urlencode and encode into a QR Code.
     */
    public string $data = '';

    /**
     * @var \App\Model\Entity\QrCode The QR Code entity
     */
    public QrCode $qrCode;

    /**
     * @var \GdImage|null  The generated QR Code Image
     */
    public ?GdImage $QR;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(QrCode $qrCode)
    {
        $config = Configure::read('QrCode', []);
        $this->config = array_merge($this->config, $config);
        $this->qrCode = $qrCode;

        $this->data = Router::url([
            '_full' => true,
            'plugin' => false,
            'prefix' => false,
            'controller' => 'QrCodes',
            'action' => 'forward',
            $this->qrCode->qrkey,
        ]);
        $QR = imagecreatefrompng($this->compileUrl());
        if ($QR instanceof GdImage) {
            $this->QR = $QR;
        }
    }

    /**
     * Generate the url from google.
     *
     * @return void
     */
    public function generate(): void
    {
        if ($this->QR && $this->config['logoPath'] && is_readable($this->config['logoPath'])) {
            /** @var string $contents */
            $contents = file_get_contents($this->config['logoPath']);

            /** @var \GdImage $logo */
            $logo = imagecreatefromstring($contents);

            $QR_width = imagesx($this->QR);
            $QR_height = imagesy($this->QR);

            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);

            // Scale logo to fit in the QR Code
            $logo_qr_width = $QR_width / 3;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;

            imagecopyresampled(
                $this->QR,
                $logo,
                (int)($QR_width / 3),
                (int)($QR_height / 3),
                0,
                0,
                (int)$logo_qr_width,
                (int)$logo_qr_height,
                (int)$logo_width,
                (int)$logo_height
            );
        }
    }

    /**
     * Save the QR Code
     *
     * @return bool
     */
    public function save(): bool
    {
        $result = null;
        $this->generate();
        if ($this->QR instanceof GdImage) {
            $result = imagepng($this->QR, TMP . 'qr_codes' . DS . $this->qrCode->id . '.png');
            imagedestroy($this->QR);
        }

        return $result ? true : false;
    }

    /**
     * Compile the url
     *
     * @return string The compiled URl
     */
    public function compileUrl(): string
    {
        return __($this->googleUrl, [
            'level' => $this->config['level'],
            'margin' => $this->config['margin'],
            'size' => $this->config['size'],
            'data' => urlencode($this->data),
        ]);
    }
}
