<?php
declare(strict_types=1);

namespace App\Lib;

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
    protected function header(): string
    {
        [$width, $height] = $this->getOutputDimensions();

        // wSetting the width and height so that we can get a larger image for print environments
        $header = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" ' .
                'class="qr-svg %1$s" ' .
                'viewBox="%2$s" ' .
                'preserveAspectRatio="%3$s" ' .
                'width="%5$s" ' .
                'height="%6$s"' .
            '>%4$s',
            $this->options->cssClass,
            $this->getViewBox(),
            $this->options->svgPreserveAspectRatio,
            $this->options->eol,
            $width * $this->scale * 5, // use the scale option to modify the size
            $height * $this->scale * 5
        );

        if ($this->options->svgAddXmlHeader) {
            $header = sprintf('<?xml version="1.0" encoding="UTF-8"?>%s%s', $this->options->eol, $header);
        }

        return $header;
    }

    /**
     * @inheritDoc
     */
    protected function paths(): string
    {
        $size = (int)ceil($this->moduleCount * $this->options->svgLogoScale);

        // we're calling QRMatrix::setLogoSpace() manually,
        // so QROptions::$addLogoSpace has no effect here
        $this->matrix->setLogoSpace($size, $size);

        $svg = parent::paths();
        // add the logo
        $svg .= $this->getLogo();

        return $svg;
    }

    /**
     * @inheritDoc
     */
    protected function path(string $path, int $M_TYPE): string
    {
        if ($this->options->svgOpacity !== 1) {
            return sprintf(
                '<path class="%s" fill="%s" opacity="%s" d="%s"/>',
                $this->getCssClass($M_TYPE),
                $this->getModuleValue($M_TYPE),
                $this->options->svgOpacity,
                $path
            );
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

        return sprintf(
            '%6$s<g transform="translate(%1$s %1$s) scale(%2$s)" fill="%4$s" class="%3$s">%5$s%6$s</g>',
            ($this->moduleCount - ($this->moduleCount * $this->options->svgLogoScale)) / 2,
            $this->options->svgLogoScale,
            $this->options->svgLogoCssClass,
            $this->options->logoColor,
            $logoContent,
            $this->options->eol
        );
    }
}
