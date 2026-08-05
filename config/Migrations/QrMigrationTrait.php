<?php
declare(strict_types=1);

namespace App\Migrations;

use Cake\Console\ConsoleIo;
use Migrations\Db\Adapter\MysqlAdapter;

/** @mixin \Migrations\BaseMigration */
trait QrMigrationTrait
{
    /**
     * Gets the migration I/O object.
     *
     * @return \Cake\Console\ConsoleIo
     */
    public function migrationIo(): ConsoleIo
    {
        $io = $this->getIo();
        if (!$io) {
            $io = new ConsoleIo();
            $this->setIo($io);
        }

        return $io;
    }

    /**
     * The default table options
     *
    * @return array{
    *   id: false,
    *   primary_key: list<string>,
    *   engine: string,
    *   encoding: string,
    *   collation: string,
    *   comment: string,
    *   row_format: string
    * } The array of options
     */
    public function tableOptions(): array
    {
        return [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'Dynamic',
        ];
    }

    /**
     * The default options for the primary key
     *
    * @return array{null: false, limit: int, precision: int, identity: true}
     */
    public function primaryKeyOptions(): array
    {
        return [
            'null' => false,
            'limit' => MysqlAdapter::INT_REGULAR,
            'precision' => 10,
            'identity' => true,
        ];
    }

    /**
     * Run stuff before Change
     *
     * @return void
     */
    public function beforeChange(): void
    {
        $this->migrationIo()->out(__('--- Running Migration: {0}:beforeChange ---', [self::class]));
        if ($this->getAdapter()->getAdapterType() == 'mysql') {
            $this->execute('SET UNIQUE_CHECKS = 0;');
            $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
            $this->execute("ALTER DATABASE CHARACTER SET 'utf8mb4';");
            $this->execute("ALTER DATABASE COLLATE='utf8mb4_general_ci';");
        }
    }

    /**
     * Run stuff after Change
     *
     * @return void
     */
    public function afterChange(): void
    {
        $this->migrationIo()->out(__('--- Running Migration: {0}:afterChange ---', [self::class]));
        if ($this->getAdapter()->getAdapterType() == 'mysql') {
            $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
            $this->execute('SET UNIQUE_CHECKS = 1;');
        }
    }
}
