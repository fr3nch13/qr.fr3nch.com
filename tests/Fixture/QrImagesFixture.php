<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\Core\Configure;
use Cake\Datasource\ConnectionInterface;
use Cake\I18n\DateTime;

/**
 * QrImagesFixture
 */
class QrImagesFixture extends CoreFixture
{
    /**
     * Table property
     *
     * @var string
     */
    public string $table = 'qr_images';

    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->io->out(__('--- Init Fixture: {0} ---', [self::class]));

        $this->records = [
            [
                'id' => 1,
                'name' => 'Front Cover',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => true,
                'imorder' => 0,
                'qr_code_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Open Pages',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => true,
                'imorder' => 1,
                'qr_code_id' => 1,
            ],
            [
                // inactive and owned by admin
                'id' => 3,
                'name' => 'Front Cover',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => false,
                'imorder' => 0,
                'qr_code_id' => 2,
            ],
            [
                // this entity is intentionally missing it's file for unit testing.
                'id' => 4,
                'name' => 'Open Pages',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => true,
                'imorder' => 1,
                'qr_code_id' => 2,
            ],
            [
                // owned by reqular.
                'id' => 5,
                'name' => 'In Hand',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => true,
                'imorder' => 0,
                'qr_code_id' => 3,
            ],
            [
                // owned by reqular.
                'id' => 6,
                'name' => 'Dimensions Top',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => true,
                'imorder' => 1,
                'qr_code_id' => 3,
            ],
            [
                // inactive and owned by reqular.
                'id' => 7,
                'name' => 'Dimensions Side',
                'ext' => 'jpg',
                'created' => new DateTime(),
                'is_active' => false,
                'imorder' => 2,
                'qr_code_id' => 3,
            ],
            [
                // Gif image.
                'id' => 8,
                'name' => 'Bananna Dance',
                'ext' => 'gif',
                'created' => new DateTime(),
                'is_active' => true,
                'imorder' => 2,
                'qr_code_id' => 1,
            ],
        ];
        parent::init();
    }

    /**
     * Inserts the records into the database before each test.
     *
     * TODO: Remove this fixture from places that don't need it
     * Since it copies files and slows down tests.
     * labels: fixtures, tests
     *
     * @return bool
     */
    public function insert(ConnectionInterface $connection): bool
    {
        if (parent::insert($connection)) {
            $source = TESTS . 'assets' . DS . 'qr_images';
            $dest = Configure::read('App.paths.qr_images');
            if (is_dir($dest)) {
                $this->rrmdir($dest);
                //usleep(500 * 1000); // give it a half second to catch up.
            }
            mkdir($dest);
            //usleep(500 * 1000); // give it a half second to catch up.
            $this->cpy($source, $dest);

            return true;
        }

        return false;
    }

    /**
     * Copies assets into place
     *
     * @param string $source The directory to copy
     * @param string $dest The directory to copy the source to
     * @return void
     */
    private function cpy(string $source, string $dest): void
    {
        if (is_dir($source)) {
            $dir_handle = opendir($source);
            if ($dir_handle) {
                while ($file = readdir($dir_handle)) {
                    if ($file != '.' && $file != '..') {
                        if (is_dir($source . DS . $file)) {
                            if (!is_dir($dest . DS . $file)) {
                                mkdir($dest . DS . $file);
                            }
                            $this->cpy($source . DS . $file, $dest . DS . $file);
                        } else {
                            copy($source . DS . $file, $dest . DS . $file);
                        }
                    }
                }
                closedir($dir_handle);
            }
        } else {
            copy($source, $dest);
        }
    }

    /**
     * Removes copied assets
     *
     * @param string $dir The directory to recursivly remove
     * @return void
     */
    private function rrmdir(string $dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            if ($objects) {
                foreach ($objects as $object) {
                    if ($object != '.' && $object != '..') {
                        if (is_dir($dir . DS . $object) && !is_link($dir . DS . $object)) {
                            $this->rrmdir($dir . DS . $object);
                        } else {
                            unlink($dir . DS . $object);
                        }
                    }
                }
            }
            rmdir($dir);
        }
    }
}
