#!/bin/sh
# Publish the Laravel app's public/ folder into a 20i web root (shared hosting: the web root is not app/public).
# Usage: sh deploy/20i-publish.sh <app-dir> <web-root> [php-handler]
#   e.g. sh ~/app/deploy/20i-publish.sh ~/app ~/public_html x-httpd-php83
# Copies static files (never touches <web-root>/uploads), writes index.php pointing at the app, and puts the
# PHP-version handler at the top of .htaccess. Safe to re-run after every `git pull`.
set -e
APP=$(cd "$1" && pwd); WEB="$2"; HANDLER="${3:-x-httpd-php83}"
mkdir -p "$WEB"
# static files: everything in public/ except uploads, index.php and .htaccess (handled below)
(cd "$APP/public" && tar cf - --exclude=./uploads --exclude=./index.php --exclude=./.htaccess .) | (cd "$WEB" && tar xf -)
REL=$(basename "$APP")
cat > "$WEB/index.php" <<PHP
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// The application lives outside the web root, in ../$REL (published by deploy/20i-publish.sh).
if (file_exists(\$maintenance = __DIR__.'/../$REL/storage/framework/maintenance.php')) {
    require \$maintenance;
}

require __DIR__.'/../$REL/vendor/autoload.php';

/** @var Application \$app */
\$app = require_once __DIR__.'/../$REL/bootstrap/app.php';
\$app->usePublicPath(__DIR__);

\$app->handleRequest(Request::capture());
PHP
{ printf '#+PHPVersion (site-specific; overrides the account default)\nAddHandler %s .php\n#-PHPVersion\n\n' "$HANDLER"; cat "$APP/public/.htaccess"; } > "$WEB/.htaccess"
echo "published $APP/public -> $WEB (handler $HANDLER)"
