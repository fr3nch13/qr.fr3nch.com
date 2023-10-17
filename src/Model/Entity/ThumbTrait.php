<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Exception\ThumbException;
use Cake\Core\Configure;
use Cake\ORM\Entity;
use GdImage;

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
    protected bool $regenerateThumb = false;

    /**
     * Here to make the controllers a little more DRY.
     */
    public function getPathThumb(string $size = 'md'): ?string
    {
        if (!in_array($size, ['sm', 'md', 'lg'])) {
            throw new ThumbException(__('Unknown size option.'));
        }

        if ($size === 'sm') {
            return $this->path_sm;
        }

        if ($size === 'md') {
            return $this->path_md;
        }

        if ($size === 'lg') {
            return $this->path_lg;
        }

        return null;
    }

    /**
     * Gets the path to the Small Thumbnail.
     * If it doesn't exist, create it.
     *
     * @return string|null The path to the thumbnail file.
     * @throws \App\Exception\ThumbException When not used in an entity.
     */
    protected function _getPathSm(): ?string
    {
        if (!$this instanceof Entity) {
            throw new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'));
        }

        $thumbPath = $this->getThumbPath('sm');
        if (!$thumbPath) {
            return null;
        }

        if (!file_exists($thumbPath) || $this->regenerateThumb) {
            $this->generateThumb('sm');
        }

        return $thumbPath;
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
        if (!$this instanceof Entity) {
            throw new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'));
        }

        $thumbPath = $this->getThumbPath('md');
        if (!$thumbPath) {
            return null;
        }

        if (!file_exists($thumbPath) || $this->regenerateThumb) {
            $this->generateThumb('md');
        }

        return $thumbPath;
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
        if (!$this instanceof Entity) {
            throw new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'));
        }

        $thumbPath = $this->getThumbPath('lg');
        if (!$thumbPath) {
            return null;
        }

        if (!file_exists($thumbPath) || $this->regenerateThumb) {
            $this->generateThumb('lg');
        }

        return $thumbPath;
    }

    /**
     * Generates the thumbnail image.
     *
     * @param string $size, can be one of sm, md, lg
     * @return bool If the thumb was generated
     * @throws \App\Exception\ThumbException When size unknown, or not used in an entity.
     */
    public function generateThumb(string $size = 'sm'): bool
    {
        if (!$this instanceof Entity) {
            throw new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'));
        }

        if (!in_array($size, ['sm', 'md', 'lg'])) {
            throw new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'));
        }

        // path to the original image.
        $originalPath = $this->path;
        if (!$originalPath) {
            return false;
        }

        $sizes = Configure::read('QrCode.thumbs.' . $size, null);
        if (!$sizes) {
            return false;
        }

        $thumbPath = $this->getThumbPath($size);
        if (!$thumbPath) {
            return false;
        }

        $imageDetails = getimagesize($originalPath);
        // may not be an image?
        if (!$imageDetails) {
            return false;
        }

        $width = $imageDetails[0];
        $height = $imageDetails[1];

        $percent = 100;
        if ($width > $sizes['x']) {
            $percent = floor($sizes['x'] * 100 / $width);
        }

        if (floor($height * $percent / 100) > $sizes['y']) {
            $percent = $sizes['y'] * 100 / $height;
        }

        if ($width > $height) {
            $newWidth = (int)$sizes['x'];
            $newHeight = (int)round($height * $percent / 100);
        } else {
            $newWidth = (int)round($width * $percent / 100);
            $newHeight = (int)$sizes['y'];
        }

        if ($imageDetails[2] == 1) {
            $originalImage = imagecreatefromgif($originalPath);
            $thumbImage = imagecreatetruecolor($newWidth, $newHeight);

            if ($originalImage && $thumbImage) {
                imagecopyresampled($thumbImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                return imagegif($thumbImage, $thumbPath);
            }
        }

        if ($imageDetails[2] == 2) {
            $originalImage = imagecreatefromjpeg($originalPath);
            $thumbImage = imagecreatetruecolor($newWidth, $newHeight);

            if ($originalImage && $thumbImage) {
                imagecopyresampled($thumbImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                return imagejpeg($thumbImage, $thumbPath);
            }
        }

        if ($imageDetails[2] == 3) {
            $originalImage = imagecreatefrompng($originalPath);
            $thumbImage = imagecreatetruecolor($newWidth, $newHeight);

            if (
                $originalImage instanceof GdImage &&
                $thumbImage instanceof GdImage
            ) {
                imagesavealpha($thumbImage, true);
                /** @var int $color The color ints below are hard-coded so how would this return a false? */
                $color = imagecolorallocatealpha($thumbImage, 0, 0, 0, 127);
                imagefill($thumbImage, 0, 0, $color);
                imagecopyresampled($thumbImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                return imagepng($thumbImage, $thumbPath);
            }
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
    public function deleteThumbs(bool $includeOriginal = false): void
    {
        if (!$this instanceof Entity) {
            throw new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'));
        }

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
    public function getThumbPath(string $size = 'sm'): ?string
    {
        if (!$this instanceof Entity) {
            throw new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'));
        }

        if (!in_array($size, ['sm', 'md', 'lg'])) {
            throw new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'));
        }

        // path to the original image.
        $path = $this->path;
        if (!$path) {
            return null;
        }

        $dir = dirname($path);
        $filename = basename($path);
        [$name, $ext] = explode('.', $filename);

        return $dir . DS . $name . '-thumb-' . $size . '.' . $ext;
    }
}
