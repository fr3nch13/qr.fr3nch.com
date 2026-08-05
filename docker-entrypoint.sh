#!/bin/sh
set -eu

bin/cake migrations upgrade
bin/cake migration_ids
bin/cake migrations migrate

exec apache2-foreground
