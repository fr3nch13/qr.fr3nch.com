<?php
declare(strict_types=1);

namespace App\Lib;

use App\Model\Entity\QrCode;
use Cake\Core\Configure;
use Cake\Routing\Router;
use chillerlan\QRCode\QRCode as ChillerlanQRCode;
use GdImage;

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
     * @var string the absolute path to the generated QR code Image
     */
    protected string $qrImagePath;

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

        $this->qrImagePath = Configure::read('App.paths.qr_codes') . DS . $this->qrCode->id . '.png';

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
            $this->qrImagePath,
            $this->config['logoPath']
        );

        // now that the qr code is generated, see if we want to add a border.
        if (isset($this->config['use_border']) && $this->config['use_border']) {
            $this->addBorder();
        }
    }

    /**
     * Add a border around the QR Code
     *
     * @return void
     */
    public function addBorder(): void
    {
        // defaults
        $this->config['border_width'] = $this->config['border_width'] ?? 5;
        $this->config['border_color'] = $this->config['border_color'] ?? [0, 0, 0];

        $img = imagecreatefrompng($this->qrImagePath);
        if ($img instanceof GdImage) {
            $border_color = imagecolorallocate(
                $img,
                $this->config['border_color'][0],
                $this->config['border_color'][1],
                $this->config['border_color'][2]
            );
            if (is_int($border_color)) {
                $x1 = 0;
                $y1 = 0;
                $x2 = imagesx($img) - 1;
                $y2 = imagesy($img) - 1;

                for ($i = 0; $i < $this->config['border_width']; $i++) {
                    imagerectangle($img, $x1++, $y1++, $x2--, $y2--, $border_color);
                }
            }

            imagepng($img, $this->qrImagePath);
            imagedestroy($img);
        }
    }
}
