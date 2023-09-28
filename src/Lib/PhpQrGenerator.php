<?php
declare(strict_types=1);

namespace App\Lib;

use App\Model\Entity\QrCode;
use Cake\Core\Configure;
use Cake\Routing\Router;
use chillerlan\QRCode\QRCode as ChillerlanQRCode;

/**
 * Library for generating QR codes with chillerlan/QRCode.
 */
class PhpQrGenerator
{
    /**
     * @var array<string, mixed> Config from the config/app_local.php
     */
    public array $config;

    /**
     * @var \App\Lib\LogoOptions variables.
     */
    public LogoOptions $options;

    /**
     * @var string The data to urlencode and encode into a QR Code.
     */
    public string $data = '';

    /**
     * @var \App\Model\Entity\QrCode The QR Code entity
     */
    public QrCode $qrCode;

    /**
     * @var \chillerlan\QRCode\QRCode The generated QR Code Image
     */
    public ChillerlanQRCode $QR;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(QrCode $qrCode)
    {
        $this->qrCode = $qrCode;
        $defaults = [
            'logoPath' => WWW_ROOT . 'img' . DS . 'qr_logo.png',
            'positivecolor' => [0, 0, 0],
            'negativecolor' => [255, 255, 255],
            'scale' => 5,
        ];

        $this->config = Configure::read('QrCode', $defaults);

        // check some of the config options
        $this->config['scale'] = $this->config['scale'] ?? 5;
        $this->config['positivecolor'] = $this->config['positivecolor'] ?? [0, 0, 0];
        $this->config['negativecolor'] = $this->config['negativecolor'] ?? [255, 255, 255];
        $this->config['logoPath'] = $this->config['logoPath'] ?? WWW_ROOT . 'img' . DS . 'qr_logo.png';

        $this->options = new LogoOptions();

        $this->options->returnResource = true;
        $this->options->outputType = ChillerlanQRCode::OUTPUT_IMAGE_PNG;
        $this->options->eccLevel = ChillerlanQRCode::ECC_H;
        $this->options->imageBase64 = false;
        $this->options->logoSpaceWidth = 13; // this is the max
        $this->options->logoSpaceHeight = 13; // this is the max
        $this->options->scale = $this->config['scale'];
        $this->options->imageTransparent = false;
        $this->options->moduleValues = [
            // @link https://github.com/chillerlan/php-qrcode/blob/4.3.4/examples/image.php#L25
            // positivecolor is normally the black color
            // negativecolor is normally the white color
            // 3 outer squares
            1536 => $this->config['positivecolor'],
            // light (false), white is the transparency color and is enabled by default
            6 => $this->config['negativecolor'],
            // darkmodule
            512 => $this->config['positivecolor'],
            // data
            1024 => $this->config['positivecolor'],
            4 => $this->config['negativecolor'],
            // finder dot, dark (true)
            5632 => $this->config['positivecolor'],
            // alignment
            2560 => $this->config['positivecolor'],
            10 => $this->config['negativecolor'],
            // format
            3584 => $this->config['positivecolor'],
            14 => $this->config['negativecolor'],
            // version
            4096 => $this->config['positivecolor'],
            16 => $this->config['negativecolor'],
            // timing
            3072 => $this->config['positivecolor'],
            12 => $this->config['negativecolor'],
            // seperator
            8 => $this->config['negativecolor'],
            // quietzone
            18 => $this->config['negativecolor'],
            // logo (requires a call to QRMatrix::setLogoSpace())
            20 => $this->config['negativecolor'],

            // these are normally the white part
            //6    => $this->config['negativecolor'], // light (false), white is the transparency color and is enabled by default

        ];

        $this->data = Router::url([
            '_full' => true,
            'plugin' => false,
            'prefix' => false,
            'controller' => 'QrCodes',
            'action' => 'forward',
            $this->qrCode->qrkey,
        ]);
    }

    /**
     * Generate the url from google.
     *
     * @return void
     */
    public function generate(): void
    {
        $this->QR = new ChillerlanQRCode($this->options);

        $qrOutputInterface = new QRImageWithLogo($this->options, $this->QR->getMatrix($this->data));
        $qrOutputInterface->dump(
            TMP . 'qr_codes' . DS . $this->qrCode->id . '.png',
            $this->config['logoPath']
        );
    }
}
