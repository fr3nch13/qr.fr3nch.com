<?php
declare(strict_types=1);

namespace App\Lib;

use App\Exception\QrCodeException;
use App\Model\Entity\QrCode;
use Cake\Core\Configure;
use Cake\Routing\Router;
use chillerlan\QRCode\Common\EccLevel;
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
    protected array $config = [];

    /**
     * @var \App\Lib\SVGWithLogoOptions variables.
     */
    protected SVGWithLogoOptions $options;

    /**
     * @var string The data to urlencode and encode into a QR Code.
     */
    protected string $data = '';

    /**
     * @var string The color to use, if none given, then the light and dark will be used.
     */
    protected string $color = '';

    /**
     * @var \App\Model\Entity\QrCode The QR Code entity
     */
    protected QrCode $qrCode;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(QrCode $qrCode)
    {
        $this->qrCode = $qrCode;
        $this->setColor();

        $this->options = new SVGWithLogoOptions([
            'eccLevel' => $this->getConfig('eccLevel', EccLevel::H),
            'outputType' => $this->getConfig('outputType', QROutputInterface::CUSTOM),
            'outputInterface' => $this->getConfig('outputInterface', QRSvgWithLogo::class),
            'svgLogo' => $this->getConfig('svgLogo', WWW_ROOT . 'img' . DS . 'qr_logo.svg'),
            'svgUseFillAttributes' => $this->getConfig('svgUseFillAttributes', true),
            'drawLightModules' => $this->getConfig('drawLightModules', false),
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
        $qrImagePath = Configure::read('App.paths.qr_codes') .
            DS .
            $this->qrCode->id .
            '-' . $this->getColor() .
            '.svg';
        $color = $this->getColor(true);
        $options = clone $this->options;
        $options->setColor($color);
        $QR = new ChillerlanQRCode($options);
        $QR->render($this->data, $qrImagePath);
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

    /**
     * Gets the color to use
     *
     * @param bool $usePound If true, it'll strip the # off fof the begining.
     */
    public function getColor(bool $usePound = false): string
    {
        $color = $this->color;
        if ($usePound && $color) {
            $color = '#' . $color;
        }

        return $color;
    }

    /**
     * Sets and validates the color, uses defaultColor is none is given.
     *
     * @param ?string $color
     * @param string $defaultColor
     * @return void
     * @throws \App\Exception\QrCodeException If the color isn't present, or is invalid
     */
    public function setColor(?string $color = null, string $defaultColor = '#000000'): void
    {
        if ($color) {
            $this->color = $this->validateColor($color);
        } elseif ($this->qrCode->color) {
            $this->color = $this->validateColor($this->qrCode->color);
        } elseif ($defaultColor) {
            $this->color = $this->validateColor($defaultColor);
        } else {
            $color = $this->getConfig('darkcolor', '000000');
            $this->color = $this->validateColor($color);
        }
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
