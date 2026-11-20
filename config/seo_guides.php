<?php

/*
| Keyword landing pages ("guides"). One file in resources/guides/{slug}.php = one page at /{slug},
| rendered by resources/views/pages/guide.blade.php. Files starting with "_" are shared data, not pages.
|
| RULE: every fact must already be published on xjmu.online (MOE notice of 1 June 2026, the fee structure, the
| admissions calendar, the admission requirements). Never state 2027 fees, seats or an MOE list for 2027-2028
| until they are officially published. Each guide must stay above 3,000 words (tests/Feature/SeoGuidesTest.php).
*/

$guides = [];
foreach (glob(__DIR__ . '/../resources/guides/[!_]*.php') ?: [] as $file) {
    $guides[basename($file, '.php')] = require $file;
}

// Quick answer + key facts for the top of each guide (resources/guides/_answers.php).
$answers = require __DIR__ . '/../resources/guides/_answers.php';
foreach ($guides as $slug => $g) {
    $guides[$slug] += $answers[$slug] ?? [];
}

return $guides;
