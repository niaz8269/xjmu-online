<?php

// SEO overrides for individual blog posts, applied in code so the blog rows in the database stay untouched.
// 'title' / 'description' replace the <title> and meta description only (the visible H1 and article are unchanged).
// 'guides' => true adds a short "Planning for 2027?" box under the article, linking to the keyword guides (config/seo_guides.php).
return [
    'posts' => [
        'advantages-of-studying-mbbs-in-china-for-international-students-complete-2026-guide' => [
            'title' => 'Advantages of MBBS in China for International Students 2026–27',
            'description' => 'Why study MBBS in China? MOE-approved universities, English-medium teaching, affordable fees and a 6-year programme. See the 2027 guide and the MOE list.',
            'guides' => true,
        ],
    ],
];
