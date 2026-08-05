<?php

declare(strict_types=1);

namespace App\Test\TestCase\Command;

use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Database\Connection;
use Cake\Database\Driver\Sqlite;
use Cake\Datasource\ConnectionInterface;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;

class MigrationIdsCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    private const CONNECTION = 'migration_ids_test';

    private Connection $connection;

    protected function setUp(): void
    {
        parent::setUp();

        ConnectionManager::setConfig(self::CONNECTION, [
            'className' => Connection::class,
            'driver' => Sqlite::class,
            'database' => ':memory:',
        ]);
        $connection = ConnectionManager::get(self::CONNECTION);
        $this->assertInstanceOf(Connection::class, $connection);
        $this->connection = $connection;
    }

    protected function tearDown(): void
    {
        ConnectionManager::drop(self::CONNECTION);

        parent::tearDown();
    }

    public function testRejectsNonDatabaseDatasource(): void
    {
        ConnectionManager::drop(self::CONNECTION);
        $datasource = $this->createStub(ConnectionInterface::class);
        ConnectionManager::setConfig(self::CONNECTION, $datasource);

        $this->exec('migration_ids --connection ' . self::CONNECTION);

        $this->assertExitError();
        $this->assertErrorContains('The configured datasource must be a database connection.');
    }

    public function testFreshDatabaseNeedsNoReconciliation(): void
    {
        $this->exec('migration_ids --connection ' . self::CONNECTION);

        $this->assertExitSuccess();
        $this->assertOutputContains('No migration history needs reconciliation.');
    }

    public function testRejectsLegacyHistoryWithoutUnifiedStorage(): void
    {
        $this->connection->execute('CREATE TABLE phinxlog (version BIGINT NOT NULL PRIMARY KEY)');

        $this->exec('migration_ids --connection ' . self::CONNECTION);

        $this->assertExitError();
        $this->assertErrorContains(
            'Legacy migration tables exist but cake_migrations does not. Run migrations upgrade first.',
        );
        $this->assertContains('phinxlog', $this->tables());
    }

    public function testReconcilesMigrationIdsAndArchivesLegacyTables(): void
    {
        $this->createUnifiedHistoryTable();
        $this->connection->execute('CREATE TABLE phinxlog (version BIGINT NOT NULL PRIMARY KEY)');
        $this->connection->execute('CREATE TABLE fr3nch13_stats_phinxlog (version BIGINT NOT NULL PRIMARY KEY)');
        $this->connection->execute('CREATE TABLE phinxlog_legacy (version BIGINT NOT NULL PRIMARY KEY)');

        $this->insertMigration('2023092102021501');

        $this->insertMigration('2023101110520001');
        $this->insertMigration('2023101110520001');

        $this->insertMigration('2023101716440001');
        $this->insertMigration('20231017164400');
        $this->insertMigration('20231017164400');

        $this->insertMigration('20231024163300');
        $this->insertMigration('2023092102021501', 'Fr3nch13/Stats');

        $this->exec('migration_ids --connection ' . self::CONNECTION);

        $this->assertExitSuccess();
        $this->assertOutputContains('Reconciled <info>5</info> migration history row(s).');
        $this->assertOutputContains('Renamed phinxlog to phinxlog_legacy_2.');
        $this->assertOutputContains(
            'Renamed fr3nch13_stats_phinxlog to fr3nch13_stats_phinxlog_legacy.',
        );

        $rows = $this->connection->execute(
            'SELECT version, plugin FROM cake_migrations ORDER BY id',
        )->fetchAll('assoc');
        $appVersions = array_map(
            static fn(array $row): string => (string)$row['version'],
            array_filter($rows, static fn(array $row): bool => $row['plugin'] === null),
        );
        sort($appVersions);

        $this->assertSame([
            '20230921020215',
            '20231011105200',
            '20231017164400',
            '20231024163300',
        ], $appVersions);
        $this->assertContains(
            ['version' => 2023092102021501, 'plugin' => 'Fr3nch13/Stats'],
            $rows,
        );
        $this->assertSame([
            'cake_migrations',
            'fr3nch13_stats_phinxlog_legacy',
            'phinxlog_legacy',
            'phinxlog_legacy_2',
        ], $this->migrationTables());

        $this->exec('migration_ids --connection ' . self::CONNECTION);

        $this->assertExitSuccess();
        $this->assertOutputContains('Reconciled <info>0</info> migration history row(s).');
        $this->assertOutputContains('Migration history is already using cake_migrations.');
    }

    private function createUnifiedHistoryTable(): void
    {
        $this->connection->execute(
            'CREATE TABLE cake_migrations ('
            . 'id INTEGER PRIMARY KEY AUTOINCREMENT, '
            . 'version BIGINT NOT NULL, '
            . 'plugin VARCHAR(100) NULL'
            . ')',
        );
    }

    private function insertMigration(string $version, ?string $plugin = null): void
    {
        $this->connection->execute(
            'INSERT INTO cake_migrations (version, plugin) VALUES (:version, :plugin)',
            compact('version', 'plugin'),
        );
    }

    /** @return list<string> */
    private function tables(): array
    {
        return array_values($this->connection->getDriver()->schemaDialect()->listTables());
    }

    /** @return list<string> */
    private function migrationTables(): array
    {
        $tables = array_values(array_filter(
            $this->tables(),
            static fn(string $table): bool => $table === 'cake_migrations' || str_contains($table, 'phinxlog'),
        ));
        sort($tables);

        return $tables;
    }
}
