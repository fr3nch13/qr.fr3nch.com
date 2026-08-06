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

## Release and deployment

The CI workflow owns release publishing and Lightsail deployment. A release-tag push runs the full development-image quality, test, coverage, and asset pipeline before publishing `fr3nch13702/qr.fr3nch.com:latest` and the matching application-version tag, then deploys that application-version tag.

A weekly Sunday 06:00 UTC scheduled run resolves the newest valid release tag merged into `main`, checks out that immutable tag in every job, and reruns the same pipeline. Scheduled Docker builds disable the build cache so current base-image and APT packages are included. Composer and npm dependencies remain pinned by the tag's committed `composer.lock` and `package-lock.json`; Dependabot owns updates to those lockfiles.

The deployment records the exact image used by the last successful internal
health check, including its release tag, image ID, repository digest, and
container ID. Inspect it on the Lightsail host with:

```sh
sudo cat /opt/qr-fr3nch-com/deployed-image.env
```

### GitHub configuration

Create a protected GitHub Environment named `production`. Configure these
Environment Variables:

| Variable | Required | Purpose |
| --- | --- | --- |
| `DEPLOY_HOST` | Yes | Lightsail instance hostname or IP address. |
| `DEPLOY_USER` | Yes | Non-root SSH user allowed to run the bootstrap with `sudo`. |
| `FULL_BASE_URL` | Yes | Public application URL, `https://qr.fr3nch.com`. |
| `FILESYSTEM_DRIVER` | Yes | `local` or `s3`. |
| `FILESYSTEM_LOCAL_PATH` | Yes | Container path used by local storage, even when S3 is selected as the primary driver. |
| `FILESYSTEM_PREFIX` | No | Upload prefix; defaults from the S3 prefix and environment. |
| `AWS_S3_REGION` | For S3 | AWS region used by uploads and cache storage. |
| `AWS_S3_BUCKET` | For S3 | S3 bucket name. |
| `AWS_S3_ENV` | No | Environment segment for S3 cache prefixes; defaults to `development`. |
| `AWS_S3_PREFIX` | No | Root S3 prefix; defaults to `fr3nch.com`. |

Configure these Environment Secrets in `production`:

| Secret | Required | Purpose |
| --- | --- | --- |
| `DEPLOY_SSH_PRIVATE_KEY` | Yes | Private key for `DEPLOY_USER`. |
| `DATABASE_URL` | Yes | Production CakePHP database connection URL. |
| `SECURITY_SALT` | Yes | Production CakePHP security salt. |
| `AWS_S3_ACCESS_KEY_ID` | For S3 | S3 access key used by uploads and cache storage. |
| `AWS_S3_SECRET_ACCESS_KEY` | For S3 | S3 secret access key. |
| `EMAIL_TRANSPORT_DEFAULT_URL` | No | CakePHP mail transport URL. |

Configure these Repository Secrets because image publication runs before the
job enters the `production` Environment:

| Secret | Required | Purpose |
| --- | --- | --- |
| `DOCKERHUB_USERNAME` | Yes | Docker Hub login and image-pull username. |
| `DOCKERHUB_TOKEN` | Yes | Docker Hub access token used for publish and deploy. |
| `CODECOV_TOKEN` | No | Codecov upload token when tokenless uploads are unavailable. |

The deployment currently derives the application root, container port, host
port, lock path, domain, image repository, and TLS paths in version-controlled
configuration; they are not GitHub settings.

Validate workflow changes with:

```sh
composer run-script actionlint
composer run-script deploy-test
```
