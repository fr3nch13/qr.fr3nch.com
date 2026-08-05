# Project Context

## Runtime and framework

- PHP 8.5 or newer
- CakePHP 5.4
- CakePHP Migrations 5
- PHPUnit 13
- PHPStan 2
- CakePHP CodeSniffer 5
- Application code is under `src/`; templates are under `templates/`.

## Database lifecycle

Application migrations are stored in `config/Migrations/`. Migrations 5 requires 14-digit `YYYYMMDDHHMMSS` migration versions and migrations extend `Migrations\BaseMigration`.

Production upgrades must preserve legacy migration history. Run database deployment commands in this order:

```sh
bin/cake migrations upgrade
bin/cake migration_ids
bin/cake migrations migrate
```

`migration_ids` reconciles this application's legacy 16-digit versions and archives legacy `phinxlog` tables. The command is idempotent and is run by `docker-entrypoint.sh` before Apache starts.

Seeds are stored in `config/Seeds/`, extend `Migrations\BaseSeed`, and use dependencies to establish insertion order. The shared `App\Migrations\QrSeedTrait` skips populated tables on forced reruns. `QrImageSeed` copies fixture assets, so redirect `App.paths.qr_images` to a temporary directory during isolated validation.

## Tests and quality checks

Tests use the `test` datasource from `DATABASE_TEST_URL`. `tests/bootstrap.php` applies application and Stats plugin migrations through `Migrations\TestSuite\Migrator`. When an existing migration's schema changes without a version change, validate against a new temporary database because an existing `tmp/tests.sqlite` may retain the old schema.

```sh
DATABASE_TEST_URL="sqlite:///$TMPDIR/qr-tests.sqlite" vendor/bin/phpunit
vendor/bin/phpstan analyse --configuration=phpstan.neon --no-progress
vendor/bin/phpcs
```

The full image test surface requires PHP's GD extension. Without GD, QR image thumbnail tests fail with undefined `imagecreatefromgif()` or `imagecreatefromjpeg()` calls.

## Production image

The production Docker stage copies the built application as `www-data`, marks `docker-entrypoint.sh` executable, and starts through that script. Build from the repository root with the existing production target and arguments in the Dockerfile; do not embed environment-specific credentials in the image.
