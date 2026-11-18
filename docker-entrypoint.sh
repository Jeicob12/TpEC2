#!/bin/sh
set -eu
php /var/www/html/registrar.php
exec docker-php-entrypoint "$@"
