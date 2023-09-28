<?php
declare(strict_types=1);

/**
 * GdImage with logo output example
 *
 * @created      18.11.2020
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2020 smiley
 * @license      MIT
 * @noinspection PhpComposerExtensionStubsInspection, PhpIllegalPsrClassPathInspection
 *
 * blatently copied from
 * @link https://github.com/chillerlan/php-qrcode/blob/main/examples/imageWithLogo.php
 */

namespace App\Lib;

use chillerlan\QRCode\Output\QRCodeOutputException;
use chillerlan\QRCode\Output\QRImage;
use function imagecopyresampled, imagecreatefrompng, imagesx, imagesy, is_file, is_readable;

/*
 * Makes an image with a Logo overlayed on it.
 *
 */

class QRImageWithLogo extends QRImage
{
    /**
     * @param string|null $file
     * @param string|null $logo
     * @return string
     * @throws \chillerlan\QRCode\Output\QRCodeOutputException
     */
    public function dump(?string $file = null, ?string $logo = null): string
    {
        // set returnResource to true to skip further processing for now
        /** @var \App\Lib\LogoOptions $options */
        $options = $this->options;
        $options->returnResource = true;

        if (!$logo) {
            throw new QRCodeOutputException('logo is not set');
        }
        if (!is_file($logo) || !is_readable($logo)) {
            throw new QRCodeOutputException('invalid logo');
        }

        $this->matrix->setLogoSpace(
            $options->logoSpaceWidth,
            $options->logoSpaceHeight
            // not utilizing the position here
        );

        // there's no need to save the result of dump() into $this->image here
        parent::dump($file);

        /** @var \GdImage $im */
        $im = imagecreatefrompng($logo);

        // get logo image size
        $w = imagesx($im);
        $h = imagesy($im);

        // set new logo size, leave a border of 1 module (no proportional resize/centering)
        $lw = ($options->logoSpaceWidth - 2) * $options->scale;
        $lh = ($options->logoSpaceHeight - 2) * $options->scale;

        // get the qrcode size
        $ql = $this->matrix->size() * $options->scale;

        // scale the logo and copy it over. done!
        /** @var \GdImage $image */
        $image = $this->image;

        imagecopyresampled(
            $image,
            $im,
            (int)($ql - $lw) / 2,
            (int)($ql - $lh) / 2,
            0,
            0,
            (int)$lw,
            (int)$lh,
            (int)$w,
            (int)$h
        );

        $imageData = $this->dumpImage();

        if ($file !== null) {
            $this->saveToFile($imageData, $file);
        }

        /*
        // not using this now, but leaving here for future reference.
        if ($options->imageBase64) {
            $imageData = 'data:image/' . $options->outputType . ';base64,' . base64_encode($imageData);
        }
        */

        return $imageData;
    }
}
