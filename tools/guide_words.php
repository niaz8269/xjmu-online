<?php
// Usage: php tools/guide_words.php — word count of each guide's own copy (headings, text, lists, tables, FAQs).
$guides = require __DIR__ . '/../config/seo_guides.php';
foreach ($guides as $slug => $g) {
    $text = [$g['h1'], $g['lead']];
    array_walk_recursive($g['sections'], function ($v, $k) use (&$text) { if (is_string($v) && ! in_array($k, ['src', 'id'], true)) { $text[] = $v; } });
    array_walk_recursive($g['faqs'], function ($v) use (&$text) { $text[] = $v; });
    printf("%-42s %5d words, %d images, %d sections\n", $slug, str_word_count(implode(' ', $text)), count(array_filter($g['sections'], fn ($s) => ! empty($s['img']))), count($g['sections']));
}
