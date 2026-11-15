# Keeping xjmu.online's Google ranking through the move to Laravel

The site ranks first, so the goal is: **same URLs, same on-page SEO, same server behaviour.** Nothing is
launched until the checks below are green.

## What is already identical (verified with `python tools/seo_compare.py <base-url>`)

Compared live vs the Laravel site for all 34 URLs in the live sitemap + redirect/edge cases:

- HTTP status codes, `<title>`, meta description / keywords / robots, canonical, `og:*` and `twitter:*` tags,
  `<h1>`/`<h2>` text, JSON-LD structured data, image and link counts: **identical** on every sitemap page.
- URLs: every sitemap URL is unchanged (clean URLs, `/blog-post?slug=…`).
- `robots.txt`, `ads.txt`, `BingSiteAuth.xml`, `google1db727d7fb7694ad.html` (Search Console verification): same files.
- `sitemap.xml` is still dynamic (new blogs/events appear automatically).
- Old `.php` URLs 301-redirect to the clean URL (in the app and in `public/.htaccess`), as before.
- Same security headers, caching (images 1 year, CSS/JS 1 month) and compression rules in `public/.htaccess`.

## Known, intentional differences

- `/news`, `/careers`, `/research`, `/international(.html)` now 301 to the real page (events, be-our-partner,
  blogs, admissions) instead of serving a duplicate copy with status 200. None are in the sitemap.
- `/admin` redirects straight to `/login` for logged-out visitors (it was `/admin/` then login). Blocked in robots.txt anyway.

## Go-live checklist (do in this order)

1. **Staging first**: deploy to a subdomain on a *copy* of the data; run `python tools/seo_compare.py https://staging…`
   (must show only the intentional differences above) and check on **HTTPS** (canonical/og URLs must read `https://xjmu.online/…`).
2. **Same domain, same host, same URLs.** Do not change the domain, www/non-www, or any path.
3. Keep the HTTPS (+ non-www) setting exactly as live. The `.htaccess` canonical-host rule stays commented out, as on the old site.
4. Go live in a **quiet window**; keep the old site folder as a one-click rollback (rename `public_html` → `public_html_old`).
5. Re-take the **live database export right before the switch** so the newest users/applications are carried over; copy any
   uploads added since (`public/uploads`).
6. Immediately after the switch: run `seo_compare.py https://xjmu.online https://xjmu.online`-style spot checks, open
   Google Search Console → URL Inspection for `/`, `/mbbs`, `/programs`, a blog post → "Test live URL" and "Request indexing".
7. Watch Search Console (Coverage / Pages, Crawl stats) and rankings daily for 2 weeks; compare against a pre-switch screenshot
   of Performance (clicks, impressions, average position).
8. Do **not** change titles, headings, copy, internal links or page speed-affecting scripts in the same release — change content only after rankings are confirmed stable.
