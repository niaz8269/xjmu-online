<?php

/*
| Old / mistyped addresses that Google (Search Console "Not found (404)") or the blog posts' own links still use.
| Each becomes a permanent 301 to the closest real page, so visitors and link signals are not lost.
*/
return [
    // path => destination
    'paths' => [
        '/apply' => '/login',
        // The five programme cards on the home page link here (the link is stored in the database); it never existed.
        '/migrations/programs' => '/programs',
        '/mbbs-in-china' => '/mbbs',
        '/mbbs-xinjiang-medical-university-pakistani-students' => '/blog/mbbs-at-xinjiang-medical-university-complete-guide-for-pakistani-students-2026-xjmu-online',
        '/index.html' => '/',
        '/introduction.html' => '/about',
        '/contact.html' => '/contact',
    ],

    // any file path below /downloads/ that does not exist (old brochure / form links) goes to the downloads page
    'downloads_fallback' => '/downloads',

    // old /blog-post?slug=<old> addresses of renamed or removed posts => destination
    'blog_slugs' => [
        'complete-list-of-45-moe-listed-universities-in-china-for-mbbs-2026-2027-xjmu-online' => '/moe-listed-mbbs-universities-in-china',
        'expat-eyes-a-ghanaian-student-s-endearing-memories-of-xinjiang' => '/blogs',
    ],
];
