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
            'svgLogoCssClass' => $this->getConfig('svgLogoCssClass', 'dark'),
            'version' => $this->getConfig('version', 5),
            'outputType' => $this->getConfig('outputType', QROutputInterface::CUSTOM),
            'outputInterface' => $this->getConfig('outputInterface', QRSvgWithLogo::class),
            'outputBase64' => $this->getConfig('outputBase64', false),
            'imageBase64' => $this->getConfig('imageBase64', false),
            'eccLevel' => $this->getConfig('eccLevel', EccLevel::H),
            'addQuietzone' => $this->getConfig('addQuietzone', true),
            'drawLightModules' => $this->getConfig('drawLightModules', true),
            'connectPaths' => $this->getConfig('connectPaths', true),
            'drawCircularModules' => $this->getConfig('drawCircularModules', true),
            'circleRadius' => $this->getConfig('circleRadius', 0.45),
            'backgroundTransparent' => $this->getConfig('backgroundTransparent', true),
            'keepAsSquare' => $this->getConfig('keepAsSquare', [
                QRMatrix::M_FINDER_DARK,
                QRMatrix::M_FINDER_DOT,
                QRMatrix::M_ALIGNMENT_DARK,
            ]),
            'svgDefs' => $this->getConfig('svgDefs', '
                <style><![CDATA[
                    .dark {
                        fill: ' . $this->getConfig('darkcolor', '#000000') . ';
                    }
                    .light {
                        fill: ' . $this->getConfig('lightcolor', '#FFFFFF') . ';
                        fill-opacity: ' . ($this->getConfig('backgroundTransparent', true) ? '0' : '0') . ';
                    }
                ]]></style>'),
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

        $optionsLight = clone $this->options;
        $optionsLight->svgDefs = $this->getConfig('svgDefsLight', '
            <style><![CDATA[
                .dark {
                    fill: ' . $this->getConfig('lightcolor', '#FFFFFF') . ';
                }
                .light {
                    fill: ' . $this->getConfig('darkcolor', '#000000') . ';
                    fill-opacity: ' . ($this->getConfig('backgroundTransparent', true) ? '0' : '1') . ';
                }
            ]]></style>');
        $qrLight = new ChillerlanQRCode($optionsLight);
        $qrLight->render($this->data, $qrImagePathLight);

        $qrImagePathDark = Configure::read('App.paths.qr_codes') .
            DS .
            $this->qrCode->id .
            '-dark' .
            '.svg';

        $optionsDark = clone $this->options;
        $optionsDark->svgDefs = $this->getConfig('svgDefsDark', '
            <style><![CDATA[
                .dark {
                    fill: ' . $this->getConfig('darkcolor', '#000000') . ';
                }
                .light {
                    fill: ' . $this->getConfig('lightcolor', '#FFFFFF') . ';
                    fill-opacity: ' . ($this->getConfig('backgroundTransparent', true) ? '0' : '1') . ';
                }
            ]]></style>');
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
