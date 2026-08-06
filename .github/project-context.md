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

The application container serves plain HTTP on port `8080`. Host Nginx owns
HTTP-to-HTTPS redirects, TLS termination, and certificates. `App.fullBaseUrl`
remains disabled so normal application links stay relative rather than exposing
the internal container address.

## Release and deployment

The CI workflow owns release publishing and Lightsail deployment. A release-tag push runs the full development-image quality, test, coverage, and asset pipeline before publishing `fr3nch13702/qr.fr3nch.com:latest` and the matching application-version tag, then deploys that application-version tag.

A weekly Sunday 06:00 UTC scheduled run resolves the newest valid release tag merged into `main`, checks out that immutable tag in every job, and reruns the same pipeline. Scheduled Docker builds disable the build cache so current base-image and APT packages are included. Composer and npm dependencies remain pinned by the tag's committed `composer.lock` and `package-lock.json`; Dependabot owns updates to those lockfiles.

The host persists the complete CakePHP `tmp` directory at
`/opt/qr-fr3nch-com/tmp` and bind-mounts it to `/var/www/html/tmp`. This retains
uploads, generated QR files, caches, sessions, and other runtime files across
container replacement. Back up this host directory according to the
application's data-retention requirements.

Certbot is installed in `/opt/certbot` through a Python virtual environment and
uses `/opt/certbot/webroot` for HTTP-01 challenges. The bootstrap labels that
webroot as `httpd_sys_content_t` so host Nginx can read challenges while SELinux
is enforcing. This repository owns an independent certificate containing only
`qr.fr3nch.com`, with its lineage under
`/etc/letsencrypt/live/qr.fr3nch.com/`; the `fr3nch.com` project manages its own
certificate and Nginx file. Each tag or weekly scheduled deployment upgrades
Certbot, retries initial HTTP-01 issuance when necessary, checks for due
renewals, and keeps an HTTP proxy active if issuance is unavailable.
`certbot-renew.timer` also runs twice daily and reloads Nginx after a successful
renewal. The Nginx plugin is intentionally not installed because deployment
owns the server configuration. Verify certificate automation on the host with:

```sh
sudo systemctl status certbot-renew.timer
sudo systemctl list-timers certbot-renew.timer
sudo /opt/certbot/bin/certbot certificates
sudo /opt/certbot/bin/certbot renew --dry-run
```

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
| `CERTBOT_EMAIL` | Yes | Let's Encrypt registration and expiry-notification email address. |

Configure these Environment Secrets in `production`:

| Secret | Required | Purpose |
| --- | --- | --- |
| `DEPLOY_SSH_PRIVATE_KEY` | Yes | Private key for `DEPLOY_USER`. |
| `DATABASE_URL` | Yes | Production CakePHP database connection URL. |
| `SECURITY_SALT` | Yes | Production CakePHP security salt. |
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

For an AWS Lightsail managed MySQL database, set `DATABASE_URL` to this template
as a `production` Environment Secret:

```text
mysql://USERNAME:PASSWORD@LIGHTSAIL_DATABASE_ENDPOINT:3306/DATABASE_NAME?encoding=utf8mb4&timezone=UTC
```

Use the database endpoint shown in the Lightsail console, not an `http://` or
`https://` URL. Percent-encode reserved URL characters in the username,
password, and database name. For example, `@` becomes `%40`, `:` becomes `%3A`,
`/` becomes `%2F`, and `#` becomes `%23`.

Validate workflow changes with:

```sh
composer run-script actionlint
composer run-script deploy-test
```
