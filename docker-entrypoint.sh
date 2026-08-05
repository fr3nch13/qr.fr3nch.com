#!/bin/sh
set -eu

mkdir -p tmp/uploads logs
chown -R www-data:www-data tmp logs

bin/cake migrations upgrade
bin/cake migration_ids
bin/cake migrations migrate

exec apache2-foreground
