<?php
declare(strict_types=1);

namespace App\Lib;

use App\Model\Entity\QrCode;
use Cake\Core\Configure;
use Cake\Routing\Router;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode as ChillerlanQRCode;

/**
 * Library for generating QR codes with chillerlan/QRCode.
 */
class PhpQrGenerator
{
    /**
     * @var array<string, mixed> Config from the config/app_local.php
     */
    public array $config = [];

    /**
     * @var \App\Lib\SVGWithLogoOptions variables.
     */
    public SVGWithLogoOptions $options;

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

        $this->options = new SVGWithLogoOptions([
            'svgLogo' => $this->getConfig('svgLogo', WWW_ROOT . 'img' . DS . 'qr_logo.svg'),
            'svgLogoScale' => $this->getConfig('svgLogoScale', 0.25),
            'svgLogoCssClass' => $this->getConfig('svgLogoCssClass', 'embedded-logo'),
            // not working at the moment.
            // 'svgViewBoxSize' => $this->getConfig('svgViewBoxSize', 500),
            'version' => $this->getConfig('version', 7),
            'outputType' => $this->getConfig('outputType', QROutputInterface::CUSTOM),
            'outputInterface' => $this->getConfig('outputInterface', QRSvgWithLogo::class),
            'outputBase64' => $this->getConfig('outputBase64', false),
            'imageBase64' => $this->getConfig('imageBase64', false),
            'eccLevel' => $this->getConfig('eccLevel', EccLevel::H),
            'addQuietzone' => $this->getConfig('addQuietzone', true),
            'drawLightModules' => $this->getConfig('drawLightModules', true),
            'connectPaths' => $this->getConfig('connectPaths', true),
            'backgroundTransparent' => $this->getConfig('backgroundTransparent', true),
            'keepAsSquare' => $this->getConfig('keepAsSquare', [
                QRMatrix::M_FINDER_DARK,
                QRMatrix::M_FINDER_DOT,
                QRMatrix::M_ALIGNMENT_DARK,
            ]),
            'svgUseFillAttributes' => $this->getConfig('svgUseFillAttributes', true),
            //'svgDefs'  => $this->getConfig('svgDefs', ''),
        ]);

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
        $qrImagePathLight = Configure::read('App.paths.qr_codes') .
            DS .
            $this->qrCode->id .
            '-light' .
            '.svg';
        $color = $this->getConfig('lightcolor', '#FFFFFF');
        $optionsLight = clone $this->options;
        $optionsLight->logoColor = $color;
        $optionsLight->moduleValues = [
            // normally light color
            QRMatrix::M_DATA => false,
            QRMatrix::M_FINDER => false,
            QRMatrix::M_SEPARATOR => false,
            QRMatrix::M_ALIGNMENT => false,
            QRMatrix::M_TIMING => false,
            QRMatrix::M_FORMAT => false,
            QRMatrix::M_VERSION => false,
            QRMatrix::M_QUIETZONE => false,
            QRMatrix::M_LOGO => false,

            // normally dark color
            QRMatrix::M_DATA_DARK => $color,
            QRMatrix::M_FINDER_DARK => $color,
            QRMatrix::M_ALIGNMENT_DARK => $color,
            QRMatrix::M_TIMING_DARK => $color,
            QRMatrix::M_FORMAT_DARK => $color,
            QRMatrix::M_VERSION_DARK => $color,
        ];
        $qrLight = new ChillerlanQRCode($optionsLight);
        $qrLight->render($this->data, $qrImagePathLight);

        $qrImagePathDark = Configure::read('App.paths.qr_codes') .
            DS .
            $this->qrCode->id .
            '-dark' .
            '.svg';
        $color = $this->getConfig('darkcolor', '#000000');
        $optionsDark = clone $this->options;
        $optionsDark->logoColor = $color;
        $optionsDark->moduleValues = [
            // normally light color
            QRMatrix::M_DATA => false,
            QRMatrix::M_FINDER => false,
            QRMatrix::M_SEPARATOR => false,
            QRMatrix::M_ALIGNMENT => false,
            QRMatrix::M_TIMING => false,
            QRMatrix::M_FORMAT => false,
            QRMatrix::M_VERSION => false,
            QRMatrix::M_QUIETZONE => false,
            QRMatrix::M_LOGO => false,

            // normally dark color
            QRMatrix::M_DATA_DARK => $color,
            QRMatrix::M_FINDER_DARK => $color,
            QRMatrix::M_ALIGNMENT_DARK => $color,
            QRMatrix::M_TIMING_DARK => $color,
            QRMatrix::M_FORMAT_DARK => $color,
            QRMatrix::M_VERSION_DARK => $color,
        ];
        $qrDark = new ChillerlanQRCode($optionsDark);
        $qrDark->render($this->data, $qrImagePathDark);

        // uncomment this out later if your want to be able to add a border.
        // $data = $this->QR->render($this->data, $this->qrImagePath);
    }

    /**
     * Checks the config, and returns a default, if not found
     *
     * @param string $key The key in the config to look for.
     * @param mixed|null $default The value to return if the key isn't found.
     * @return mixed The value, or default.
     */
    public function getConfig(string $key, mixed $default = null): mixed
    {
        if (!$this->config) {
            $this->config = Configure::read('QrCode');
        }

        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $default;
    }
}
