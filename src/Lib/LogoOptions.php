<?php
declare(strict_types=1);

namespace App\Lib;

use chillerlan\QRCode\QROptions;

class LogoOptions extends QROptions
{
    // size in QR modules, multiply with QROptions::$scale for pixel size
    public int $logoSpaceWidth;
    public int $logoSpaceHeight;
}
