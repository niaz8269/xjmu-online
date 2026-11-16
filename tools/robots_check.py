#!/usr/bin/env python3
"""Googlebot-style robots.txt check: is every sitemap URL, and every CSS/JS/image/font a page loads, crawlable?

Usage: python tools/robots_check.py [https://xjmu.online]

Implements Google's matching rules: longest matching path wins, Allow wins a tie, '*' wildcard and '$' end anchor.
Read-only (GET requests only).
"""
import re, sys, urllib.request
from urllib.parse import urljoin, urlparse

BASE = (sys.argv[1] if len(sys.argv) > 1 else 'https://xjmu.online').rstrip('/')


def get(url):
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'})
    with urllib.request.urlopen(req, timeout=40) as r:
        return r.read().decode('utf-8', 'replace')


def parse(robots):
    groups, cur = {}, None
    for raw in robots.splitlines():
        line = raw.split('#', 1)[0].strip()
        if ':' not in line:
            continue
        k, v = [x.strip() for x in line.split(':', 1)]
        k = k.lower()
        if k == 'user-agent':
            if cur is None or cur['rules']:
                cur = {'agents': [], 'rules': []}
            cur['agents'].append(v.lower())
            groups.setdefault(v.lower(), cur)
        elif k in ('allow', 'disallow') and cur is not None:
            cur['rules'].append((k, v))
    return groups


def to_regex(pattern):
    out = ''
    for ch in pattern:
        out += '.*' if ch == '*' else re.escape(ch)
    if pattern.endswith('$'):
        out = out[:-2] + '$' if out.endswith('\\$') else out
    return re.compile('^' + out)


def allowed(groups, path):
    g = groups.get('googlebot') or groups.get('*')
    if not g:
        return True, None
    best = (-1, True, None)
    for kind, pat in g['rules']:
        if pat == '':
            continue
        if to_regex(pat).match(path):
            score = len(pat.replace('*', ''))
            is_allow = kind == 'allow'
            if score > best[0] or (score == best[0] and is_allow and not best[1]):
                best = (score, is_allow, f'{kind}: {pat}')
    return best[1], best[2]


def resources(html, page_url):
    found = set()
    for m in re.finditer(r'''(?:src|href)\s*=\s*["']([^"'#]+)["']''', html):
        u = m.group(1).strip()
        if u.startswith(('mailto:', 'tel:', 'javascript:', 'data:')):
            continue
        full = urljoin(page_url, u)
        if urlparse(full).netloc == urlparse(BASE).netloc and re.search(r'\.(css|js|png|jpe?g|gif|webp|svg|ico|woff2?|ttf|mp4)(\?|$)', full, re.I):
            found.add(full)
    return found


def main():
    groups = parse(get(BASE + '/robots.txt'))
    urls = re.findall(r'<loc>([^<]+)</loc>', get(BASE + '/sitemap.xml'))
    blocked, res_all = [], {}
    for u in urls:
        ok, rule = allowed(groups, urlparse(u).path + (('?' + urlparse(u).query) if urlparse(u).query else ''))
        if not ok:
            blocked.append((u, rule))
    print(f'{len(urls)} sitemap URLs checked: {len(blocked)} blocked')
    for u, r in blocked:
        print('  BLOCKED', u, '<-', r)
    pages = [u for u in urls if '/blog/' not in u or u == urls[0]][:25] + [u for u in urls if '/blog/' in u][:3]
    for u in pages:
        try:
            for r in resources(get(u), u):
                res_all.setdefault(r, u)
        except Exception as e:  # noqa
            print('  could not read', u, e)
    bad = []
    for r in sorted(res_all):
        p = urlparse(r)
        ok, rule = allowed(groups, p.path + (('?' + p.query) if p.query else ''))
        if not ok:
            bad.append((r, rule))
    print(f'{len(res_all)} CSS/JS/image/font files used by {len(pages)} pages: {len(bad)} blocked')
    for r, rule in bad:
        print('  BLOCKED', r, '<-', rule)
    must = ['/css/global.css', '/js/global.js', '/images/logo2.png', '/assets/images/affil-hospt.webp', '/favicon.ico', '/sitemap.xml']
    for m in must:
        print(('  ok      ' if allowed(groups, m)[0] else '  BLOCKED ') + m)


main()
