#!/bin/sh
php ./bin/migrate.php
exec php -S 0.0.0.0:80 -t public