<?php
declare(strict_types=1);

namespace App\Lib;

use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRMarkupSVG;

/**
 * Handle adding the logo file.
 *
 * @property \App\Lib\SVGWithLogoOptions $options
 */
class QRSvgWithLogo extends QRMarkupSVG
{
    /**
     * @inheritDoc
     */
    protected function paths(): string
    {
        $size = (int)ceil($this->moduleCount * $this->options->svgLogoScale);

        // we're calling QRMatrix::setLogoSpace() manually, so QROptions::$addLogoSpace has no effect here
        $this->matrix->setLogoSpace($size, $size);

        $svg = parent::paths();
        $svg .= $this->getLogo();

        return $svg;
    }

    /**
     * @inheritDoc
     */
    protected function path(string $path, int $M_TYPE): string
    {
        $this->options->svgOpacity = '1';
        if (
            in_array($M_TYPE, [
            QRMatrix::M_DATA,
            QRMatrix::M_FINDER,
            QRMatrix::M_SEPARATOR,
            QRMatrix::M_ALIGNMENT,
            QRMatrix::M_TIMING,
            QRMatrix::M_FORMAT,
            QRMatrix::M_VERSION,
            QRMatrix::M_QUIETZONE,
            QRMatrix::M_LOGO,
            ]) &&
            !$this->options->moduleValues[$M_TYPE]
        ) {
            $this->options->svgOpacity = '0';
        }

        return parent::path($path, $M_TYPE);
    }

    /**
     * returns a <g> element that contains the SVG logo and positions it properly within the QR Code
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/SVG/Element/g
     * @see https://developer.mozilla.org/en-US/docs/Web/SVG/Attribute/transform
     */
    protected function getLogo(): string
    {
        // remove the xml tag from the logo.
        $logoContent = file_get_contents($this->options->svgLogo);

        if ($logoContent === false) {
            $logoContent = '';
        }

        $logoContent = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $logoContent);

        // @todo: customize the <g> element to your liking (css class, style...)
        return sprintf(
            '%6$s<g transform="translate(%1$s %1$s) scale(%2$s)" fill="%4$s" class="%3$s">%5$s	%5$s%6$s</g>',
            ($this->moduleCount - ($this->moduleCount * $this->options->svgLogoScale)) / 2,
            $this->options->svgLogoScale,
            $this->options->svgLogoCssClass,
            $this->options->logoColor,
            $logoContent,
            $this->options->eol
        );
    }
}
