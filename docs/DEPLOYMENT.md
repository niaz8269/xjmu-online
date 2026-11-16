# xjmu.online on 20i: how it is deployed

Went live on Laravel: **16 Nov 2026, 12:44 UTC** (branch `seo-2027`).

## Server layout (SSH: `xjmu.online@ssh.sg.stackcp.com`, key-based)

| Path | What it is |
|---|---|
| `~/app` | The Laravel application (git clone of `seo-2027`), `.env`, `vendor/`, `storage/`. Not web-accessible. |
| `~/public_html` | The web root of xjmu.online: a published copy of `app/public` plus `index.php` pointing at `../app`. |
| `~/public_html/uploads` | The original uploads folder (applicant documents, blog images). Never touched by a deploy. |
| `~/public_html_old` | The complete old plain-PHP site as it was at the switch. Rollback copy. |
| `~/staging` | Staging app (`https://staging.xjmu.online`, own database `xjmustaging-…`, own uploads copy, email off, noindex). |
| `~/backups` | Database dumps (`live_db_YYYYMMDD_HHMMSS.sql.gz`). |

- PHP: the account default is 8.0 (`~/.htaccess`). `~/public_html/.htaccess` and `~/staging/.htaccess` select **8.3** with
  `AddHandler x-httpd-php83 .php`. Use `/usr/bin/php83` on the command line.
- Production uses the **same live database** as before. Sessions and cache are files (`SESSION_DRIVER=file`,
  `CACHE_STORE=file`), so Laravel added **no tables** and changed no schema. SQL mode is the server default (`DB_STRICT` unset).
- `.env` was generated on the server from the old site's `.env` (database and SMTP values copied, never printed).

## Deploying an update

```sh
cd ~/app && git pull --ff-only
/usr/bin/php83 /usr/local/bin/composer install --no-dev --optimize-autoloader   # only if composer.lock changed
/usr/bin/php83 artisan config:cache && /usr/bin/php83 artisan view:cache
sh deploy/20i-publish.sh ~/app ~/public_html x-httpd-php83
```

Then purge the CDN in 20i → CDN → Edge Caching → **Purge Everything** (public pages are cached for a day).
The publish script copies new/changed static files but does not delete files removed from `public/`; remove those by hand.
Test on staging first: `cd ~/staging && git pull --ff-only && /usr/bin/php83 artisan config:cache && /usr/bin/php83 artisan view:cache`.

## Rollback to the old PHP site (seconds)

```sh
cd ~ && mv public_html/uploads public_html_old/uploads && mv public_html public_html_laravel && mv public_html_old public_html
```

Then purge the CDN. The database was not altered by the migration, so the old site works with it as-is
(accounts or applications created after the switch are in the same database and stay available).

## Caching rules

- Content pages: no cookie, `Cache-Control: public, max-age=86400` (`PublicCache` middleware, `$cacheable` in `routes/web.php`).
- Pages with a form token (login, register, events, testimonial form), portal, admin: `no-cache, private`.
- Admin pages send `X-Robots-Tag: noindex, nofollow`; any non-production copy sends it on every page.
