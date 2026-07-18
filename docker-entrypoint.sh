#!/bin/sh

# Install with composer if needed.

# Migrations
composer migrate \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 770 /var/www/html/tmp \
    && find /var/www/html/tmp -type f -exec chmod 660 "{}" \; \
    && find /var/www/html/tmp -type d -exec chmod 770 "{}" \; \
    && chmod -R 770 /var/www/html/logs \
    && find /var/www/html/logs -type f -exec chmod 660 "{}" \; \
    && find /var/www/html/logs -type d -exec chmod 770 "{}" \; \
    && chown www-data:www-data /var/www/html/tmp/prod.sqlite \
    && chmod 600 /var/www/html/tmp/prod.sqlite

# Launch httpd in the foreground
rm -rf /run/apache2/* || true && apachectl -D FOREGROUND
