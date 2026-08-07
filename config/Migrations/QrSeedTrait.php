<?php
declare(strict_types=1);

namespace App\Migrations;

use Cake\Console\ConsoleIo;

/** @mixin \Migrations\BaseSeed */
trait QrSeedTrait
{
    /**
     * Gets the seed I/O object.
     *
     * @return \Cake\Console\ConsoleIo
     */
    public function seedIo(): ConsoleIo
    {
        $io = $this->getIo();
        if (!$io) {
            $io = new ConsoleIo();
            $this->setIo($io);
        }

        return $io;
    }

    /**
     * Checks to make sure we can run the seed first
    *
     * @return bool If we can proceed to seed or not.
     */
    public function checkTable(string $tableName): bool
    {
        $io = $this->seedIo();
        $io->out(__('Checking table: {0}', [$tableName]));

        $result = $this->table($tableName)->getAdapter()->fetchAll('select * from `' . $tableName . '`');
        if (count($result)) {
            $io->warning(__('The table: {0} already has {1} rows.', [
                $tableName,
                count($result),
            ]));
            return false;
        }

        $io->out(__('Adding seeds to table: {0}', [$tableName]));

        return true;
    }
}
