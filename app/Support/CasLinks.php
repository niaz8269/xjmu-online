<?php

namespace App\Support;

/**
 * Links the first mention on a page of each other MOE listed university, "Study in China" and "MBBS in China"
 * to the CAS website. Each term is linked once per page; headings, existing links and scripts are left alone.
 */
class CasLinks
{
    public const URL = 'https://cas.moe/';

    /** @var array<string, true> terms already linked on the current page */
    private static array $used = [];

    /** @var array<int, array{0: string, 1: bool}>|null [term, case-insensitive] */
    private static ?array $terms = null;

    /** Call once at the start of each page. */
    public static function reset(): void
    {
        self::$used = [];
    }

    /** Plain text in, safe HTML out. */
    public static function text(?string $plain): string
    {
        return self::html(e((string) $plain));
    }

    /** HTML in, HTML out: only text outside links, headings, buttons, scripts and styles is touched. */
    public static function html(?string $html): string
    {
        $html = (string) $html;
        foreach (self::terms() as [$term, $insensitive]) {
            if (isset(self::$used[$term])) {
                continue;
            }
            foreach (array_unique([$term, e($term)]) as $needle) {
                if (stripos($html, $needle) === false) {
                    continue;
                }
                $pattern = '/(?<![\w>])' . preg_quote($needle, '/') . '(?!\w)/u' . ($insensitive ? 'i' : '');
                $done = false;
                $skip = 0;
                $parts = preg_split('/(<[^>]+>)/', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
                foreach ($parts as $i => $part) {
                    if ($part !== '' && $part[0] === '<') {
                        if (preg_match('/^<(\/?)(a|h[1-6]|script|style|button|summary|figcaption)\b/i', $part, $m)) {
                            $skip += $m[1] === '/' ? -1 : 1;
                        }
                        continue;
                    }
                    if ($skip > 0 || $part === '') {
                        continue;
                    }
                    $new = preg_replace($pattern, '<a href="' . self::URL . '" target="_blank" rel="noopener">$0</a>', $part, 1, $count);
                    if ($count) {
                        $parts[$i] = $new;
                        $done = true;
                        break;
                    }
                }
                if ($done) {
                    $html = implode('', $parts);
                    self::$used[$term] = true;
                    break;
                }
            }
        }

        return $html;
    }

    /** @return array<int, array{0: string, 1: bool}> longest first, so a longer name wins over a shorter one inside it */
    private static function terms(): array
    {
        if (self::$terms === null) {
            $terms = [['Study in China', true], ['MBBS in China', true]];
            foreach (config('cas_links.universities', []) as $name) {
                $terms[] = [$name, false];
            }
            usort($terms, fn ($a, $b) => mb_strlen($b[0]) <=> mb_strlen($a[0]));
            self::$terms = $terms;
        }

        return self::$terms;
    }
}
