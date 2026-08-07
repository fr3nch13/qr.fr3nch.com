FROM php:8.5-apache-trixie AS php-extensions

# Build PHP extensions with their compile-time dependencies.
RUN apt-get update \
    && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y --no-install-recommends libfreetype6-dev libicu-dev libjpeg62-turbo-dev libpng-dev libsqlite3-dev libzip-dev \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j"$(nproc)" gd intl mysqli pdo_mysql pdo_sqlite zip

FROM golang:latest AS actionlint-build

RUN GOBIN=/out go install github.com/rhysd/actionlint/cmd/actionlint@latest

FROM php:8.5-apache-trixie AS base

# Install runtime libraries and the app's database client.
RUN apt-get update \
    && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y --no-install-recommends libfreetype6 libjpeg62-turbo libpng16-16t64 mariadb-client libicu76 libzip5 \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

COPY --from=php-extensions /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=php-extensions /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Enable Apache modules commonly needed by CakePHP apps.
COPY resources/docker/apache-proxy-scheme.conf /etc/apache2/conf-available/proxy-scheme.conf
RUN a2enmod rewrite headers setenvif \
    && a2enconf proxy-scheme \
    && sed -i 's/^Listen 80$/Listen 8080/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
RUN mkdir -p /var/www/html/tmp/uploads /var/www/html/logs \
    && chown -R www-data:www-data /var/www/html/tmp /var/www/html/logs
EXPOSE 8080

# This is the target stage specifically for development builds. It is not used in production.
FROM base AS development

# Support VS Code sandboxed tool execution and frontend asset builds in the development container.
RUN apt-get update \
    && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y --no-install-recommends awscli bubblewrap gh git git-lfs jq nodejs npm openssh-client ripgrep socat \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=actionlint-build /out/actionlint /usr/local/bin/actionlint
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
CMD ["apache2-foreground"]

# This is the target stage specifically for devcontainer builds. It is not used in production.
FROM development AS devcontainer

# Install devcontainer-only tooling.
RUN curl -fsSL https://hermes-agent.nousresearch.com/install.sh | bash -s -- --non-interactive

FROM development AS application-build

COPY . /var/www/html
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist --optimize-autoloader \
    && npm ci \
    && npm run build \
    && rm -rf node_modules

# This is the target stage for production builds. It is not used in development.
FROM base AS production

COPY --from=application-build --chown=www-data:www-data /var/www/html /var/www/html
RUN chmod 755 /var/www/html/docker-entrypoint.sh
RUN chmod 755 /var/www/html/resources/scripts/docker-healthcheck.sh
HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 CMD ["/var/www/html/resources/scripts/docker-healthcheck.sh"]

CMD ["./docker-entrypoint.sh"]
