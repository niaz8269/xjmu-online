#!/usr/bin/env python3
"""SEO parity check: compare the live site with another base URL (local, staging, ...).

Usage: python tools/seo_compare.py http://127.0.0.1:8765 [https://xjmu.online]

For every URL in the live sitemap (plus redirect/edge cases) it compares status code, redirect target,
<title>, meta description/robots/keywords, canonical, og:/twitter: tags, h1/h2 text, JSON-LD blocks and
the number of images/links. Read-only: only GET requests, no cookies.
"""
import json, re, sys, urllib.request, urllib.error, urllib.parse
from html.parser import HTMLParser

NEW = sys.argv[1].rstrip('/')
LIVE = (sys.argv[2] if len(sys.argv) > 2 else 'https://xjmu.online').rstrip('/')


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k):
        return None


OPENER = urllib.request.build_opener(NoRedirect)


def get(url):
    req = urllib.request.Request(url, headers={'User-Agent': 'seo-compare/1.0 (Googlebot-like check)'})
    try:
        r = OPENER.open(req, timeout=40)
        return r.status, {k.title(): v for k, v in r.headers.items()}, r.read().decode('utf-8', 'replace')
    except urllib.error.HTTPError as e:
        return e.code, {k.title(): v for k, v in e.headers.items()}, e.read().decode('utf-8', 'replace')
    except Exception as e:  # noqa
        return 0, {}, f'ERR {e}'


class P(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.title = ''; self._t = False; self.meta = {}; self.canon = ''; self.h = []; self._h = None
        self.ld = []; self._ld = False; self.imgs = 0; self.links = 0; self.lang = ''; self.alts = 0; self.hreflang = []

    def handle_starttag(self, tag, attrs):
        a = dict(attrs)
        if tag == 'html': self.lang = a.get('lang', '')
        if tag == 'title': self._t = True
        elif tag == 'meta':
            k = a.get('name') or a.get('property')
            if k: self.meta[k.lower()] = (a.get('content') or '').strip()
        elif tag == 'link':
            rel = (a.get('rel') or '').lower()
            if rel == 'canonical': self.canon = a.get('href', '')
            if rel == 'alternate' and a.get('hreflang'): self.hreflang.append((a['hreflang'], a.get('href', '')))
        elif tag in ('h1', 'h2'): self._h = [tag, '']
        elif tag == 'script' and a.get('type') == 'application/ld+json': self._ld = True; self.ld.append('')
        elif tag == 'img':
            self.imgs += 1
            if a.get('alt'): self.alts += 1
        elif tag == 'a': self.links += 1

    def handle_endtag(self, tag):
        if tag == 'title': self._t = False
        elif tag in ('h1', 'h2') and self._h: self.h.append((self._h[0], re.sub(r'\s+', ' ', self._h[1]).strip())); self._h = None
        elif tag == 'script': self._ld = False

    def handle_data(self, d):
        if self._t: self.title += d
        if self._h is not None: self._h[1] += d
        if self._ld and self.ld: self.ld[-1] += d


def norm_url(u):
    return u.replace(NEW.replace('http://', 'https://'), 'BASE').replace(NEW, 'BASE').replace(LIVE, 'BASE').replace('http://xjmu.online', 'BASE')


def parse(html):
    p = P(); p.feed(html)
    ld = []
    for b in p.ld:
        try: ld.append(json.dumps(json.loads(b), sort_keys=True))
        except Exception: ld.append(re.sub(r'\s+', ' ', b).strip())
    return {
        'title': re.sub(r'\s+', ' ', p.title).strip(), 'lang': p.lang,
        'description': p.meta.get('description', ''), 'robots': p.meta.get('robots', ''), 'keywords': p.meta.get('keywords', ''),
        'canonical': norm_url(p.canon), 'og:title': p.meta.get('og:title', ''), 'og:description': p.meta.get('og:description', ''),
        'og:url': norm_url(p.meta.get('og:url', '')), 'og:image': norm_url(p.meta.get('og:image', '')), 'twitter:title': p.meta.get('twitter:title', ''),
        'headings': p.h, 'jsonld': [norm_url(x) for x in ld], 'imgs': p.imgs, 'imgs_with_alt': p.alts, 'links': p.links,
        'hreflang': [(l, norm_url(h)) for l, h in p.hreflang],
    }


def main():
    _, _, sm = get(LIVE + '/sitemap.xml')
    urls = [u.replace(LIVE, '') or '/' for u in re.findall(r'<loc>([^<]+)</loc>', sm)]
    extras = ['/about.php', '/index.php', '/news', '/news.php', '/careers.php', '/research.html', '/campus-life.html', '/international.html',
              '/login', '/register', '/forgot_password', '/front', '/edit-details', '/admin', '/admin/dashboard', '/sitemap.php', '/does-not-exist-xyz',
              '/robots.txt', '/ads.txt', '/BingSiteAuth.xml', '/google1db727d7fb7694ad.html', '/favicon.ico', '/blogs?page=2', '/blogs?search=mbbs']
    bad = 0
    for path in urls + extras:
        ls, lh, lb = get(LIVE + path)
        ns, nh, nb = get(NEW + path)
        issues = []
        if ls != ns: issues.append(f'status live={ls} new={ns}')
        ll, nl = norm_url(lh.get('Location', '')), norm_url(nh.get('Location', ''))
        if ll.rstrip('/') != nl.rstrip('/') and (ll or nl): issues.append(f'redirect live={ll!r} new={nl!r}')
        if ls == 200 and ns == 200 and 'html' in lh.get('Content-Type', ''):
            a, b = parse(lb), parse(nb)
            for k in a:
                if a[k] != b[k]:
                    if k == 'headings' or k == 'jsonld' or k == 'hreflang':
                        issues.append(f'{k} differ (live {len(a[k])} vs new {len(b[k])})')
                    else:
                        issues.append(f'{k}: live={str(a[k])[:90]!r} new={str(b[k])[:90]!r}')
        elif ls == 200 and ns == 200:
            if lh.get('Content-Type', '').split(';')[0] != nh.get('Content-Type', '').split(';')[0]:
                issues.append(f"content-type live={lh.get('Content-Type')} new={nh.get('Content-Type')}")
            if re.sub(r'\s+', '', lb.replace(LIVE, 'BASE')) != re.sub(r'\s+', '', nb.replace(NEW, 'BASE')):
                issues.append(f'body differs (live {len(lb)}B new {len(nb)}B)')
        bad += bool(issues)
        print(('DIFF ' if issues else 'ok   ') + path[:90])
        for i in issues: print('       - ' + i)
    print(f'\n{len(urls) + len(extras)} URLs checked, {bad} with differences')


main()
