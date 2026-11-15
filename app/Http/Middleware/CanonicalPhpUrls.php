<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * SEO: 301-redirect old `/page.php` (and `/index.php`) GET URLs to the clean canonical URL, exactly like the
 * original site's .htaccess did, so Google consolidates duplicates. Processing endpoints are excluded.
 * Done in the app as well as in public/.htaccess so it works on any server (e.g. `artisan serve`, nginx).
 */
class CanonicalPhpUrls
{
    private const EXCLUDED = '/^(?:save_|get_|send_|submit_|download_|export_|review_)/i';

    public function handle(Request $request, Closure $next)
    {
        // International page aliases from the original .htaccess
        if ($request->isMethod('GET') && preg_match('#^/international(?:\.php|\.html)?$#i', $request->getPathInfo())) {
            return redirect('/admissions', 301);
        }

        if ($request->isMethod('GET') && preg_match('#^/([^./]+)\.php$#i', $request->getPathInfo(), $m)) {
            $name = $m[1];
            if (! preg_match(self::EXCLUDED, $name)) {
                $query = (string) $request->server->get('QUERY_STRING', ''); // raw, keeps the original parameter order
                $target = strcasecmp($name, 'index') === 0 ? '/' : '/' . $name;
                return redirect($target . ($query ? '?' . $query : ''), 301);
            }
        }
        return $next($request);
    }
}
