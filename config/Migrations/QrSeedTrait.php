<?php
declare(strict_types=1);

namespace App\Migrations;

use Cake\Console\ConsoleIo;

/**
 * Here in migrations because the seed mingration command 
 * wants to treat is as a seed.
 */
trait QrSeedTrait
{
    /**
     * @var \Cake\Console\ConsoleIo console object
     */
    public $io;

    /**
     * Makes the I/O object
     * 
     * @return void
     */
    public function makeIo(): void
    {
        if (!$this->io) {
            $this->io = new ConsoleIo();
        }
    }

    /**
     * Checks to make sure we can run the seed first
     * 
     * @return bool If we can proceed to seed or not.
     */
    public function checkTable(string $tableName): bool
    {
        $this->makeIo();

        $this->io->out(__('Checking table: {0}', [$tableName]));
        
        $result = $this->table($tableName)->getAdapter()->fetchAll('select * from `' . $tableName . '`');
        if (count($result)) {
            $this->io->warning(__('The table: {0} already has {1} rows.', [
                $tableName,
                count($result),
            ]));
            return false;
        }

        $this->io->out(__('Adding seeds to table: {0}', [$tableName]));

        return true;
    }
}