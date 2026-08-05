<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;

class MigrationIdsCommand extends Command
{
    private const MIGRATION_IDS = [
        '2023092102021501' => '20230921020215',
        '2023101110520001' => '20231011105200',
        '2023101716440001' => '20231017164400',
        '2023102416330001' => '20231024163300',
    ];

    public static function getDescription(): string
    {
        return 'Reconcile legacy application migration IDs with CakePHP Migrations 5.';
    }

    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return $parser
            ->setDescription(static::getDescription())
            ->addOption('connection', [
                'short' => 'c',
                'default' => 'default',
                'help' => 'The datasource connection to use.',
            ]);
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $connection = ConnectionManager::get((string)$args->getOption('connection'));
        if (!$connection instanceof Connection) {
            $io->error('The configured datasource must be a database connection.');

            return static::CODE_ERROR;
        }

        $schema = $connection->getDriver()->schemaDialect();
        $legacyTables = array_values(array_filter(
            $schema->listTables(),
            static fn(string $table): bool => $table === 'phinxlog' || str_ends_with($table, '_phinxlog'),
        ));
        if (!$schema->hasTable('cake_migrations')) {
            if ($legacyTables !== []) {
                $io->error('Legacy migration tables exist but cake_migrations does not. Run migrations upgrade first.');

                return static::CODE_ERROR;
            }

            $io->success('No migration history needs reconciliation.');

            return static::CODE_SUCCESS;
        }

        $updated = $connection->transactional(function (Connection $connection): int {
            $updated = 0;
            foreach (self::MIGRATION_IDS as $oldVersion => $newVersion) {
                $updated += $this->reconcileVersion($connection, (string)$oldVersion, $newVersion);
            }

            return $updated;
        });

        $io->out(sprintf('Reconciled <info>%d</info> migration history row(s).', $updated));

        foreach ($legacyTables as $legacyTable) {
            $backupTable = $this->nextBackupTable($connection, $legacyTable);
            $driver = $connection->getDriver();
            $connection->execute(sprintf(
                'ALTER TABLE %s RENAME TO %s',
                $driver->quoteIdentifier($legacyTable),
                $driver->quoteIdentifier($backupTable),
            ));
            $io->success(sprintf('Renamed %s to %s.', $legacyTable, $backupTable));
        }
        if ($legacyTables === []) {
            $io->success('Migration history is already using cake_migrations.');
        }

        return static::CODE_SUCCESS;
    }

    private function reconcileVersion(Connection $connection, string $oldVersion, string $newVersion): int
    {
        $rows = $connection->execute(
            'SELECT id, version FROM cake_migrations '
            . 'WHERE plugin IS NULL AND version IN (:oldVersion, :newVersion) ORDER BY id',
            compact('oldVersion', 'newVersion'),
        )->fetchAll('assoc');

        $oldRows = array_values(array_filter(
            $rows,
            static fn(array $row): bool => (string)$row['version'] === $oldVersion,
        ));
        $newRows = array_values(array_filter(
            $rows,
            static fn(array $row): bool => (string)$row['version'] === $newVersion,
        ));

        if ($oldRows === [] && count($newRows) <= 1) {
            return 0;
        }

        $updated = 0;
        if ($newRows === [] && $oldRows !== []) {
            $row = array_shift($oldRows);
            $connection->execute(
                'UPDATE cake_migrations SET version = :newVersion WHERE id = :id',
                ['newVersion' => $newVersion, 'id' => $row['id']],
            );
            $updated++;
        } else {
            array_shift($newRows);
        }

        foreach ([...$oldRows, ...$newRows] as $row) {
            $connection->execute(
                'DELETE FROM cake_migrations WHERE id = :id',
                ['id' => $row['id']],
            );
            $updated++;
        }

        return $updated;
    }

    private function nextBackupTable(Connection $connection, string $legacyTable): string
    {
        $schema = $connection->getDriver()->schemaDialect();
        $backupTable = $legacyTable . '_legacy';
        $suffix = 2;

        while ($schema->hasTable($backupTable)) {
            $backupTable = $legacyTable . '_legacy_' . $suffix;
            $suffix++;
        }

        return $backupTable;
    }
}
