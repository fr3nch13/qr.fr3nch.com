<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Migrations\AbstractSeed;

/**
 * QrImage seed.
 */
class QrImageSeed extends AbstractSeed
{
    use \App\Migrations\QrSeedTrait;

    /**
     * The dependant records before this can be added.
     *
     * @return array<int, string> List of required seeds
     */
    public function getDependencies(): array
    {
        return [
            'QrCodeSeed',
        ];
    }

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $this->checkTable('qr_images');
        $table = $this->table('qr_images');

        $data = [
            [
                'id' => 1,
                'name' => 'Front Cover',
                'ext' => 'jpg',
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'is_active' => true,
                'imorder' => 0,
                'qr_code_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Open Pages',
                'ext' => 'jpg',
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'is_active' => true,
                'imorder' => 1,
                'qr_code_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Front Cover',
                'ext' => 'jpg',
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'is_active' => true,
                'imorder' => 0,
                'qr_code_id' => 2,
            ],
            [
                'id' => 4,
                'name' => 'Open Pages',
                'ext' => 'jpg',
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'is_active' => true,
                'imorder' => 1,
                'qr_code_id' => 2,
            ],
            [
                'id' => 5,
                'name' => 'In Hand',
                'ext' => 'jpg',
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'is_active' => true,
                'imorder' => 0,
                'qr_code_id' => 3,
            ],
            [
                'id' => 6,
                'name' => 'Dimensions Top',
                'ext' => 'jpg',
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'is_active' => true,
                'imorder' => 1,
                'qr_code_id' => 3,
            ],
            [
                'id' => 7,
                'name' => 'Dimensions Side',
                'ext' => 'jpg',
                'created' => (new DateTime())->format('Y-m-d H:i:s'),
                'is_active' => true,
                'imorder' => 2,
                'qr_code_id' => 3,
            ],
        ];
        // add or change data here for the seeding.

        $table->insert($data)->save();

        // TODO: After they're been inserted, copy over their images.
        // labels: seeds, assets
        $source = TESTS . 'assets' . DS . 'qr_images';
        $dest = Configure::read('App.paths.qr_images');
        if (is_dir($dest)) {
            $this->rrmdir($dest);
            //usleep( 500 * 1000 );  // give it a half second to catch up.
        }
        mkdir($dest);
        //usleep( 500 * 1000 );  // give it a half second to catch up.
        $this->cpy($source, $dest);
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
