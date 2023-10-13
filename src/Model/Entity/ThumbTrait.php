<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Exception\ThumbException;
use Cake\Core\Configure;

/**
 * Managed the thumbnail files
 *
 * @property string|null $path_sm (Virtual field) Path to the generated Small Thumbnail.
 * @property string|null $path_md (Virtual field) Path to the generated Medium Thumbnail.
 * @property string|null $path_lg (Virtual field) Path to the generated Large Thumbnail.
 */
trait ThumbTrait
{
    /**
     * @var bool If we should regenerate the thumbnail file.
     */
    protected $regenerateThumb = false;

    /**
     * Gets the path to the Small Thumbnail.
     * If it doesn't exist, create it.
     *
     * @return string|null The path to the thumbnail file.
     * @throws \App\Exception\ThumbException When not used in an entity.
     */
    protected function _getPathSm(): ?string
    {
        assert(
            $this instanceof \Cake\ORM\Entity,
            new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'))
        );

        $thumbPath = $this->getThumbPath('sm');
        if (!$thumbPath) {
            return null;
        }

        if (!file_exists($thumbPath) || $this->regenerateThumb) {
            $this->generateThumb('sm');
        }

        if (is_readable($thumbPath)) {
            return $thumbPath;
        }

        return null;
    }

    /**
     * Gets the path to the Medium Thumbnail.
     * If it doesn't exist, create it.
     *
     * @return string|null The path to the thumbnail file.
     * @throws \App\Exception\ThumbException When not used in an entity.
     */
    protected function _getPathMd(): ?string
    {
        assert(
            $this instanceof \Cake\ORM\Entity,
            new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'))
        );

        $thumbPath = $this->getThumbPath('md');
        if (!$thumbPath) {
            return null;
        }

        if (!file_exists($thumbPath) || $this->regenerateThumb) {
            $this->generateThumb('md');
        }

        if (is_readable($thumbPath)) {
            return $thumbPath;
        }

        return null;
    }

    /**
     * Gets the path to the Large Thumbnail.
     * If it doesn't exist, create it.
     *
     * @return string|null The path to the thumbnail file.
     * @throws \App\Exception\ThumbException When not used in an entity.
     */
    protected function _getPathLg(): ?string
    {
        assert(
            $this instanceof \Cake\ORM\Entity,
            new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'))
        );

        $thumbPath = $this->getThumbPath('lg');
        if (!$thumbPath) {
            return null;
        }

        if (!file_exists($thumbPath) || $this->regenerateThumb) {
            $this->generateThumb('lg');
        }

        if (is_readable($thumbPath)) {
            return $thumbPath;
        }

        return null;
    }

    /**
     * Generates the thumbnail image.
     *
     * @param string $size, can be one of sm, md, lg
     * @return string The path to the generated thumbnail.
     * @throws \App\Exception\ThumbException When size unknown, or not used in an entity.
     */
    protected function generateThumb(string $size= 'sm'): bool
    {
        assert(
            $this instanceof \Cake\ORM\Entity,
            new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'))
        );
        assert(
            in_array($size, ['sm', 'md', 'lg']),
            new ThumbException(__('Unknown size option.'))
        );

        // path to the original image.
        $originalPath = $this->path;
        if (!$originalPath) {
            return false;
        }

        $size = Configure::read('QrCodes.thumbs.' . $size, null);
        if (!$size) {
            return false;
        }

        $thumbPath = $this->getThumbPath($size);

        $imageDetails = getimagesize($originalPath);
        $width = $imageDetails[0];
        $height = $imageDetails[1];

        $percent = 100;
        if($width > $size['x']) {
            $percent = floor(($size['x'] * 100) / $width);
        }

        if(floor(($height * $percent)/100)>$size['y']) {
            $percent = (($size['y'] * 100) / $height);
        }

        if($width > $height) {
            $newWidth = $size['x'];
            $newHeight = round(($height*$percent)/100);
        }else{
            $newWidth=round(($width*$percent)/100);
            $newHeight=$size['y'];
        }

        if ($imageDetails[2] == 1) {
            $originalImage = imagecreatefromgif($originalPath);
            $thumbImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($thumbImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            return imagegif($thumbImage, $thumbPath);
        }

        if ($imageDetails[2] == 2) {
            $originalImage = imagecreatefromjpeg($originalPath);
            $thumbImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($thumbImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            return imagejpeg($thumbImage, $thumbPath);
        }

        if ($imageDetails[2] == 3) {
            $originalImage = imagecreatefrompng($originalPath);
            $thumbImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($thumbImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            return imagepng($thumbImage, $thumbPath);
        }

        return false;
    }

    /**
     * Deletes the thumbnail files.
     *
     * @param bool $includeOriginal If we also want to delete the original file as well.
     * @return void
     * @throws \App\Exception\ThumbException When not used in an entity.
     */
    protected function deleteThumbs(bool $includeOriginal = false): void
    {
        assert(
            $this instanceof \Cake\ORM\Entity,
            new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'))
        );

        // yes I know they may generate the thumbnails.
        $paths = [
            $this->path_sm,
            $this->path_md,
            $this->path_lg,
        ];
        if ($includeOriginal) {
            $paths[] = $this->path;
        }

        foreach ($paths as $path) {
            if ($path && is_file($path)) {
                unlink($path);
            }
        }
    }

    /**
     * Creates the thumbpath string bsed on size.
     *
     * @param string $size, can be one of sm, md, lg
     * @return string|null The path to the thumb file.
     * @throws \App\Exception\ThumbException When size unknown, or not used in an entity.
     */
    protected function getThumbPath(string $size= 'sm'): ?string
    {
        assert(
            $this instanceof \Cake\ORM\Entity,
            new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'))
        );
        assert(
            in_array($size, ['sm', 'md', 'lg']),
            new ThumbException(__('Unknown size option.'))
        );

        // path to the original image.
        $path = $this->path;
        if (!$path) {
            return null;
        }

        $dir = dirname($path);
        $filename = basename($path);
        list($name, $ext) = explode('.', $filename);

        return $dir . DS . $name . '-thumb-' . $size . '.' . $ext;
    }

}
