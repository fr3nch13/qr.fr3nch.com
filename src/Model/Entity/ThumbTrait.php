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
            self::class instanceof \Cake\ORM\Entity,
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
            self::class instanceof \Cake\ORM\Entity,
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
            self::class instanceof \Cake\ORM\Entity,
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
            self::class instanceof \Cake\ORM\Entity,
            new ThumbException(__('Must be an instance of `\Cake\ORM\Entity`.'))
        );
        assert(
            in_array($size, ['sm', 'md', 'lg']),
            new ThumbException(__('Unknown size option.'))
        );

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
            self::class instanceof \Cake\ORM\Entity,
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
            self::class instanceof \Cake\ORM\Entity,
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
