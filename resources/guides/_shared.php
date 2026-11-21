<?php

// Facts shared by the guides. All of them are already published on xjmu.online.

$moeList = [
    ['Jilin University', 100], ['China Medical University', 50], ['Dalian Medical University', 120], ['Capital Medical University', 70],
    ['Tianjin Medical University', 100], ['Shandong University', 80], ['Fudan University', 40], ['Xinjiang Medical University', 60],
    ['Nanjing Medical University', 60], ['Jiangsu University', 70], ['Wenzhou Medical University (incl. overseas campus)', 120],
    ['Zhejiang University', 180], ['Wuhan University', 50], ['Huazhong University of Science and Technology', 80],
    ["Xi'an Jiaotong University", 120], ['Southern Medical University', 100], ['Jinan University', 30], ['Guangxi Medical University', 60],
    ['Sichuan University', 70], ['Chongqing Medical University', 100], ['Harbin Medical University', 60], ['Beihua University', 20],
    ['Jinzhou Medical University', 40], ['Qingdao University', 30], ['Hebei Medical University', 40], ['Ningxia Medical University', 100],
    ['Tongji University', 40], ['Shihezi University', 120], ['Southeast University', 30], ['Yangzhou University', 30],
    ['Nantong University', 30], ['Soochow University', 30], ['Ningbo University', 50], ['Fujian Medical University', 60],
    ['Anhui Medical University', 80], ['Xuzhou Medical University', 40], ['Zhengzhou University', 40], ['Guangzhou Medical University', 80],
    ['Shantou University', 20], ['Kunming Medical University', 70], ['Southwest Medical University', 40], ['Xiamen University', 50],
    ['Shanghai Jiao Tong University', 30],
];
$moeRows = [];
foreach ($moeList as $i => [$name, $seats]) {
    $moeRows[] = [(string) ($i + 1), $name, (string) $seats];
}
$moeRows[] = ['', 'Total', '2,790'];

// Approximate exchange rates for the rupee fee table. Mid-market rates looked up on the date below; update both
// numbers and the date together (banks and transfer services charge a few percent more).
$fx = ['date' => '22 November 2026', 'pkr' => 41.4, 'inr' => 13.9];
$money = fn (float $yuan, float $rate) => number_format(round($yuan * $rate, -3));
$feeLocalRows = [];
foreach ([['First-year package', 49950, '7,000'], ['Each later year', 35950, '5,050'], ['Six years in total', 229700, '32,000']] as [$label, $yuan, $usd]) {
    $feeLocalRows[] = [$label, '¥' . number_format($yuan), 'USD ' . $usd, 'PKR ' . $money($yuan, $fx['pkr']), 'INR ' . $money($yuan, $fx['inr'])];
}

return [
    'moeSource' => "Source: Ministry of Education of the People's Republic of China, notice 教外司来〔2026〕94号, issued 1 June 2026.",
    'moeRows' => $moeRows,
    'fx' => $fx,
    'feeLocal' => [
        'caption' => 'XJMU MBBS fees in yuan, US dollars, Pakistani rupees and Indian rupees (approximate, at ¥1 = PKR ' . $fx['pkr'] . ' and INR ' . $fx['inr'] . ' on ' . $fx['date'] . ')',
        'head' => ['Fee', 'Chinese yuan', 'US dollars', 'Pakistani rupees', 'Indian rupees'],
        'rows' => $feeLocalRows,
    ],
    'feeFirstYear' => [
        'caption' => 'First-year package, 2027 intakes (unchanged from 2026)',
        'head' => ['What the first-year package covers', 'Status'],
        'rows' => [
            ['Application processing, registration and admission charges', 'Included'],
            ['Tuition fee', 'Included'],
            ['Hostel accommodation', 'Included'],
            ['Student visa, health insurance and medical', 'Included'],
            ['JW202 visa letter and airport pickup', 'Included'],
            ['', 'Total: ¥49,950 (about USD 7,000)'],
        ],
    ],
    'feeYearly' => [
        'caption' => 'Second year onward, per year',
        'head' => ['Item', 'Amount (RMB ¥)'],
        'rows' => [
            ['Tuition fee', '¥30,000'],
            ['Accommodation', '¥4,500'],
            ['Insurance', '¥300'],
            ['Residence permit', '¥800'],
            ['Physical examination', '¥350'],
            ['', 'Total per year: ¥35,950 (about USD 5,050)'],
        ],
    ],
    'living' => [
        'caption' => 'Typical monthly living costs in Urumqi',
        'head' => ['Item', 'Typical monthly cost'],
        'rows' => [['Food', 'USD 150–250'], ['Local transport', 'USD 10–20'], ['Mobile SIM', 'USD 10–15']],
    ],
    'calendar' => [
        'caption' => 'Published admissions calendar (dates may vary by programme)',
        'head' => ['Step', 'September/Fall intake (September)', 'March/Spring intake (February/March)'],
        'rows' => [
            ['Applications open', '1 March', '1 September'],
            ['Early deadline (recommended for scholarships)', '30 June', '15 December'],
            ['Regular deadline', '15 August', '15 January'],
            ['Registration and orientation', '10 September', '20 February'],
        ],
    ],
    'requirements' => [
        'Senior high-school certificate (HSSC, A-Level or equivalent) with science subjects',
        'Minimum 60% marks in each of the science subjects',
        'Aged 18 to 35',
        'In good health and free from communicable diseases',
        'Valid passport',
    ],
    'documents' => [
        'Passport',
        'Passport-size photograph with a white background',
        'XJMU application form',
        'Chinese physical examination form and blood report',
        'Police non-criminal record certificate',
        'Senior high-school transcripts and certificates',
        'Self-introduction video, in which you say that you have chosen XJMU and will study there if accepted',
    ],
    'scholarships' => [
        'caption' => 'Scholarship stipends, yuan per person per year',
        'head' => ['Scholarship', 'Undergraduate', "Master's", 'Doctoral'],
        'rows' => [
            ['Chinese Government Scholarship (tuition, accommodation and insurance free)', '30,000', '36,000', '42,000'],
            ['Xinjiang Government Scholarship for students from neighbouring countries', '10,000', '12,200', '15,000'],
        ],
    ],
    'video' => ['src' => 'https://www.youtube.com/embed/8WrB3jnE5V8', 'title' => 'Xinjiang Medical University admissions video'],
];
