<?php
declare(strict_types=1);

namespace App\Lib;

use chillerlan\QRCode\QRCodeException;
use chillerlan\QRCode\QROptions;
use const \PHP_EOL;

class SVGWithLogoOptions extends QROptions
{
    // path to svg logo
    public string $svgLogo;

    // logo scale in % of QR Code size, clamped to 10%-30%
    public float $svgLogoScale = 0.20;

    // css class for the logo (defined in $svgDefs)
    public string $svgLogoCssClass = '';

    // make sure we get the xml returned.
    public bool $outputBase64 = false;

    public string $eol = PHP_EOL;

    // the name is specific as it's called within chillerlan's code
    // phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

    /**
     * Checks that the logo file exist.
     *
     * @param string $svgLogo The path to the logo file
     * @return void
     * @throws \chillerlan\QRCode\QRCodeException if the logo file can't be found, or is unreadable.
     */
    protected function set_svgLogo(string $svgLogo): void
    {
        if (!file_exists($svgLogo) || !is_readable($svgLogo)) {
            throw new QRCodeException('invalid svg logo');
        }

        // @todo: validate svg

        $this->svgLogo = $svgLogo;
    }

    /**
     * Sets the scale of the log to fit within the logo section.
     *
     * @param float$svgLogoScale The scale we're trying to use.
     * @return void
     */
    protected function set_svgLogoScale(float $svgLogoScale): void
    {
        $this->svgLogoScale = max(0.05, min(0.3, $svgLogoScale));
    }

    // phpcs:enable
}
