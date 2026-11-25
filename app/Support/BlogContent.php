<?php

namespace App\Support;

/**
 * Cleans the admin-written HTML of a blog post when it is shown (the database row is never changed):
 * the page already has one title, one description and one canonical address, so a copy of those tags
 * pasted inside a post would give search engines two of each. (H1 headings inside a post are demoted by BlogService::demoteContentH1.)
 */
class BlogContent
{
    public static function clean(?string $html): string
    {
        $html = (string) $html;
        // Tags that belong in <head>, pasted into the post body.
        $html = preg_replace('~<title\b[^>]*>.*?</title>~is', '', $html) ?? $html;
        $html = preg_replace('~<meta\b[^>]*\bname\s*=\s*["\']?(?:description|robots|keywords)["\']?[^>]*>~i', '', $html) ?? $html;
        $html = preg_replace('~<link\b[^>]*\brel\s*=\s*["\']?canonical["\']?[^>]*>~i', '', $html) ?? $html;
        return $html;
    }
}
