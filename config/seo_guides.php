<?php

/*
| Keyword landing pages ("guides"). One file in resources/guides/{slug}.php = one page at /{slug},
| rendered by resources/views/pages/guide.blade.php. Files starting with "_" are shared data, not pages.
|
| RULE: every fact must already be published on xjmu.online (MOE notice of 1 June 2026, the fee structure, the
| admissions calendar, the admission requirements). 2027 fees are confirmed unchanged from 2026 (owner, 22 Nov 2026). Never state seats or an MOE list for 2027-2028
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

// Fees in Pakistani and Indian rupees (22 Nov 2026): one shared table, added as the second section of the three
// guides where families ask for it. The rates and their date live in resources/guides/_shared.php ($fx).
$shared = require __DIR__ . '/../resources/guides/_shared.php';
$rupees = [
    'id' => 'fees-in-rupees', 'eyebrow' => 'In your currency', 'h' => 'MBBS in China fees in Pakistani and Indian rupees',
    'p' => [
        'The university charges its fees in Chinese yuan. The table shows the same fees in US dollars, Pakistani rupees and Indian rupees, converted at the mid-market rate on ' . $shared['fx']['date'] . ': ¥1 = PKR ' . $shared['fx']['pkr'] . ' and ¥1 = INR ' . $shared['fx']['inr'] . '.',
        'Treat the rupee amounts as a guide, not a quotation. Exchange rates move every day, and banks and transfer services add a few percent, so the amount you pay in rupees depends on the rate on the day you send the money. The yuan amount is the one that is fixed.',
    ],
    'table' => $shared['feeLocal'],
];
foreach (['mbbs-in-china-fees-2027', 'mbbs-in-china-for-pakistani-students', 'mbbs-in-china-for-indian-students'] as $slug) {
    if (isset($guides[$slug]['sections'])) {
        array_splice($guides[$slug]['sections'], 1, 0, [$rupees]);
    }
}

return $guides;
