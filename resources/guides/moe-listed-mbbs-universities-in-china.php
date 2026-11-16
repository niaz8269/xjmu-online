<?php

$s = require __DIR__ . '/_shared.php';

return [
    'updated' => '2026-11-16',
    'title' => 'MOE Listed Universities in China 2027 | Official List of 43 (MBBS)',
    'description' => 'List of MOE listed universities in China for 2027 applicants: all 43 approved English taught MBBS universities with seats, the rules and how to verify one.',
    'keywords' => 'MOE listed university in China, List of MOE listed universities, MOE listed universities in China 2027, MOE listed MBBS, MOE listed MBBS universities in China, registered MBBS university in China, MOE approved medical universities China, 43 MOE listed universities, MBBS in China 2027',
    'eyebrow' => 'Official list for 2027 applicants',
    'h1' => 'List of MOE Listed Universities in China 2027',
    'lead' => "The 43 universities that China's Ministry of Education has approved to teach MBBS in English to international students, with seat quotas, the rules behind the list and how to check any university yourself.",
    'crumb' => 'List of MOE listed universities',
    'cta' => 'Apply to an MOE listed university',
    'sections' => [

        ['id' => 'what-is-moe-listed', 'eyebrow' => 'The basics', 'h' => 'What does MOE listed MBBS mean?', 'p' => [
            "MOE stands for the Ministry of Education of the People's Republic of China. An MOE listed MBBS university is a university that the ministry has named in its official notice as authorised to enrol international students in the undergraduate clinical medicine programme taught in English, the programme known worldwide as MBBS in China.",
            "The list is not a ranking and it is not an advertisement. It is a permission. A university that appears on it may teach MBBS in English and may issue an English-language MBBS degree certificate. A university that does not appear on it may not.",
            "For students applying for MBBS in China 2027, the list in force is the notice for the 2026–2027 academic year, issued on 1 June 2026. It names 43 universities and gives them a combined quota of 2,790 seats.",
        ], 'img' => ['src' => 'assets/images/campus-img.webp', 'alt' => 'Xinjiang Medical University campus, an MOE listed MBBS university in China', 'caption' => 'Xinjiang Medical University is number 8 on the MOE list, with 60 seats.'],
        'table' => ['caption' => 'The MOE list in numbers', 'head' => ['Fact', 'Figure'], 'rows' => [
            ['Universities approved', '43'],
            ['Total seats for international students', '2,790'],
            ['Academic year covered', '2026–2027'],
            ['Date of the notice', '1 June 2026'],
            ['Notice number', '教外司来〔2026〕94号'],
            ['Largest quota', 'Zhejiang University, 180 seats'],
            ['Xinjiang Medical University', '60 seats'],
        ]], 'note' => $s['moeSource']],

        ['id' => 'why-it-matters', 'eyebrow' => 'Why it matters', 'h' => 'Why the MOE list decides the value of your degree', 'p' => [
            "A medical degree is only useful if you can register as a doctor afterwards. Licensing bodies in your home country look at where and how you studied, and the MOE list is the official record of which Chinese programmes are authorised for international students.",
        ], 'sub' => [
            ['h' => 'It is the legal basis for an English taught MBBS', 'p' => [
                "The notice states that only listed universities may enrol international students into English taught MBBS. This is why the phrases MOE listed MBBS and English taught MBBS describe the same group of universities.",
            ]],
            ['h' => 'It protects the name of the degree', 'p' => [
                "Universities that are not on the list are prohibited from issuing any English-language degree certificate that carries the term MBBS. A student at an unlisted university therefore cannot graduate with the certificate that licensing bodies expect.",
            ]],
            ['h' => 'It limits numbers', 'p' => [
                "Each listed university receives a seat quota. The quota keeps class sizes in line with teaching and hospital capacity, and it is also why early application matters: when the seats are filled, the intake is closed.",
            ]],
            ['h' => 'It is enforced', 'p' => [
                "The notice says that universities which seriously violate it will face enrolment restrictions or full suspension. The list is reviewed every year, so a university must keep meeting the standard to stay on it.",
            ]],
        ]],

        ['id' => 'rules', 'eyebrow' => 'The rules', 'h' => 'The MOE rules every MBBS applicant should know', 'p' => [
            "The 2026 notice sets out five rules. They are short, and they answer most of the questions students ask about approved and unapproved programmes.",
        ], 'list' => [
            'Only universities on the approved list may enrol international students into English taught MBBS for 2026–2027.',
            'Universities not on the list are prohibited from issuing any English-language degree certificate bearing the term MBBS.',
            'Unlisted universities may only admit international students to Chinese-medium clinical medicine programmes, held to the same standard as domestic students.',
            'Using a bilingual teaching model as a workaround is strictly prohibited.',
            'Universities that seriously violate the notice face enrolment restrictions or full suspension.',
        ], 'note' => $s['moeSource']],

        ['id' => 'full-list', 'eyebrow' => 'Full list', 'h' => 'List of MOE listed universities in China: all 43 with seats', 'p' => [
            "This is the complete list from the official notice, in the order in which the ministry publishes it, with the seat quota for each university.",
        ], 'table' => ['caption' => 'MOE listed MBBS universities in China, 2026–2027 academic year', 'head' => ['#', 'University', 'Seats'], 'rows' => $s['moeRows'], 'highlight' => 'Xinjiang Medical University'],
        'note' => $s['moeSource'], 'link' => ['blogs', 'Read our news post on the MOE notice']],

        ['id' => 'reading-the-list', 'eyebrow' => 'Understanding it', 'h' => 'How to read the list', 'p' => [
            "The list is simple, but three points are often misunderstood.",
        ], 'sub' => [
            ['h' => 'The order is not a ranking', 'p' => [
                "The numbers beside each university are the order of publication, not a quality ranking. A university at number 30 is exactly as approved as a university at number 3.",
            ]],
            ['h' => 'Seats are a ceiling', 'p' => [
                "The seat figure is the maximum number of international students a university may enrol in the English taught MBBS for that academic year. Quotas range from 20 seats to 180. Larger quotas do not mean easier admission, because larger universities also receive more applications.",
            ]],
            ['h' => 'The list belongs to one academic year', 'p' => [
                "Each notice covers one academic year. Universities can be added or removed, and quotas can change. Once you are enrolled in an approved programme, you continue in that programme; the yearly list governs new enrolment.",
            ]],
        ]],

        ['id' => 'verify', 'eyebrow' => 'Check it yourself', 'h' => 'How to verify a registered MBBS university in China', 'p' => [
            "Students often ask how to confirm that a university is a registered MBBS university, or whether an agent's claim is true. You can check in four steps, and it takes a few minutes.",
        ], 'sub' => [
            ['h' => '1. Find the university on the MOE list', 'p' => ["Look for the exact university name in the table above. Similar names are common in China, so match the full name and the city."]],
            ['h' => '2. Confirm the programme and language', 'p' => ["Ask the university in writing whether your offer is for the English taught MBBS for international students. An offer for a bilingual or Chinese-medium clinical medicine programme is a different programme."]],
            ['h' => '3. Check the World Directory of Medical Schools', 'p' => ["Search for the university in the WHO World Directory of Medical Schools. Xinjiang Medical University is listed there."]],
            ['h' => '4. Check your home medical council', 'p' => ["Read your own council's current rules for foreign medical graduates. Recognition at home is decided by the council, not by the university or the agent."]],
        ], 'img' => ['src' => 'assets/images/counselling.webp', 'alt' => 'Students and parents checking information about MOE listed MBBS universities at an admissions counselling session', 'caption' => 'Check the list yourself before you pay any fee.']],

        ['id' => 'by-region', 'eyebrow' => 'By region', 'h' => 'MOE listed MBBS universities by region', 'p' => [
            "China is the size of a continent, and where you study shapes the climate you live in, the food you eat and how far you are from home. The 43 MOE listed universities are spread across seven regions. Eighteen are in the east, around Shanghai, Jiangsu and Zhejiang. Four are in the northwest, the region closest to Pakistan and Central Asia.",
        ], 'table' => ['caption' => 'The 43 MOE listed universities grouped by region', 'head' => ['Region', 'Universities', 'Count'], 'rows' => [
            ['Northeast', 'Jilin University, China Medical University, Dalian Medical University, Harbin Medical University, Beihua University, Jinzhou Medical University', '6'],
            ['North', 'Capital Medical University, Tianjin Medical University, Hebei Medical University', '3'],
            ['East', 'Shandong University, Qingdao University, Fudan University, Tongji University, Shanghai Jiao Tong University, Nanjing Medical University, Southeast University, Jiangsu University, Yangzhou University, Nantong University, Soochow University, Xuzhou Medical University, Zhejiang University, Wenzhou Medical University, Ningbo University, Fujian Medical University, Xiamen University, Anhui Medical University', '18'],
            ['Central', 'Wuhan University, Huazhong University of Science and Technology, Zhengzhou University', '3'],
            ['South', 'Southern Medical University, Jinan University, Guangzhou Medical University, Shantou University, Guangxi Medical University', '5'],
            ['Southwest', 'Sichuan University, Chongqing Medical University, Kunming Medical University, Southwest Medical University', '4'],
            ['Northwest', "Xinjiang Medical University, Shihezi University, Ningxia Medical University, Xi'an Jiaotong University", '4'],
        ], 'highlight' => 'Northwest'], 'sub' => [
            ['h' => 'Why region matters', 'p' => [
                "Winters in the northeast are long and very cold, while the south is hot and humid for much of the year. Coastal cities such as Shanghai and Guangzhou are the most expensive to live in. Cities in the west generally cost less, and for students from Pakistan, Afghanistan and Central Asia they are also the shortest journey home. Xinjiang Medical University is in Urumqi, the capital of Xinjiang, in the northwest.",
            ]],
        ]],

        ['id' => 'by-seats', 'eyebrow' => 'By size', 'h' => 'MOE listed universities by seat quota', 'p' => [
            "Seat quotas show how the 2,790 places are shared. Ten universities hold 100 seats or more and together account for well over a third of all places. Sixteen universities have 40 seats or fewer, which means their international classes are small and fill quickly.",
            "Xinjiang Medical University sits in the middle group with 60 seats: large enough for a full international class with students from many countries, small enough for teaching in manageable groups.",
        ], 'table' => ['caption' => 'How the 2,790 seats are distributed', 'head' => ['Seat quota', 'Number of universities', 'Examples'], 'rows' => [
            ['100 or more', '10', 'Zhejiang University, Dalian Medical University, Shihezi University'],
            ['60 to 80', '13', 'Xinjiang Medical University, Shandong University, Sichuan University'],
            ['50', '4', 'China Medical University, Wuhan University, Xiamen University'],
            ['40 or fewer', '16', 'Fudan University, Tongji University, Shantou University'],
        ], 'highlight' => '60 to 80']],

        ['id' => 'terms', 'eyebrow' => 'Clearing up the terms', 'h' => 'MOE listed, WHO listed and council recognised: the difference', 'p' => [
            "Students see several labels on university websites and assume they mean the same thing. They do not, and you need all three to be true.",
        ], 'sub' => [
            ['h' => 'MOE listed', 'p' => [
                "This is permission from China's Ministry of Education to teach MBBS in English to international students. It is about the programme you will study. Without it, there is no English taught MBBS to enrol in.",
            ]],
            ['h' => 'WHO listed, or WDOMS listed', 'p' => [
                "This means the medical school appears in the World Directory of Medical Schools. Many licensing bodies and international examinations require your school to be in the directory. It is a record that the school exists and is recognised by its own government, not an inspection of quality.",
            ]],
            ['h' => 'Recognised by your medical council', 'p' => [
                "This is the decision of the regulator in the country where you want to practise, such as PMDC in Pakistan or NMC in India. It decides whether you may sit the licensing examination and register as a doctor. Councils publish their own conditions, and those conditions can change.",
            ]],
            ['h' => 'How they fit together', 'p' => [
                "The safest choice is a university that is MOE listed for the English taught MBBS, appears in the World Directory of Medical Schools, and meets your own council's current conditions. Xinjiang Medical University is MOE listed and WDOMS listed; confirm your council's current rules before you apply.",
            ]],
        ]],

        ['id' => 'myths', 'eyebrow' => 'Myths', 'h' => 'Five myths about MOE listed MBBS universities', 'sub' => [
            ['h' => 'Myth 1: Any Chinese medical university can teach MBBS in English', 'p' => ["Only the universities on the MOE list can. For 2026–2027 that is 43 universities."]],
            ['h' => 'Myth 2: A bilingual programme is an approved alternative', 'p' => ["The notice prohibits bilingual teaching as a workaround. A bilingual offer is not an English taught MBBS."]],
            ['h' => 'Myth 3: A higher position on the list means a better university', 'p' => ["The list is in order of publication. It does not rank quality."]],
            ['h' => 'Myth 4: Once listed, always listed', 'p' => ["The list is issued every year, and universities that violate the notice can be restricted or suspended."]],
            ['h' => 'Myth 5: MOE listing guarantees recognition in my country', 'p' => ["MOE listing makes the programme legitimate in China. Recognition at home is decided by your own medical council, so check its rules as well."]],
        ], 'p' => [
            "Much of the confusion around MBBS in China comes from a handful of claims that are repeated in advertisements. Here is what the notice actually says.",
        ]],

        ['id' => 'ask-agent', 'eyebrow' => 'Before you pay', 'h' => 'Questions to ask before you pay any fee', 'p' => [
            "Whether you apply directly or through a consultant, ask for clear written answers to these questions. A genuine offer from an MOE listed university can answer all of them.",
        ], 'list' => [
            'Is the university on the current MOE list, and at which number?',
            'Is my offer for the English taught MBBS for international students, and does the admission letter say so?',
            'What is the total cost for the first year and for each later year, including hostel, insurance and the residence permit?',
            'Which hospitals will I train in, and where is the internship year?',
            'Will I receive a JW202 form for the X1 student visa?',
            'What are the refund rules if my visa is refused?',
        ]],

        ['id' => 'unlisted', 'eyebrow' => 'The risk', 'h' => 'What happens if you choose a university that is not MOE listed', 'p' => [
            "Unlisted universities are still allowed to admit international students, but only into Chinese-medium clinical medicine, studied to the same standard as Chinese students. That has real consequences for someone who expected an English taught MBBS.",
        ], 'list' => [
            'You study medicine in Chinese, which usually requires a high level of Chinese before clinical subjects begin.',
            'Your certificate cannot carry the term MBBS in English.',
            'Your degree may not be recognised by home-country licensing bodies such as PMDC, NMC India or equivalent authorities.',
            'If the university is penalised for breaking the notice, your enrolment can be affected.',
        ], 'note' => 'Verify your chosen university against the list before making any application or payment.'],

        ['id' => 'xjmu', 'eyebrow' => 'XJMU', 'h' => 'Xinjiang Medical University on the MOE list', 'p' => [
            "Xinjiang Medical University appears on the 2026–2027 list at number 8, with an intake quota of 60 seats. The university was founded in 1956 in Urumqi and is the largest provincial-ministerial joint medical university in Xinjiang.",
            "XJMU has taught international medical students for many years and has been part of the MOE's list of authorised English-medium MBBS programmes since the first list was published in 2007. Clinical training takes place across 8 directly affiliated hospitals, led by the First Affiliated Hospital, a Class A tertiary hospital.",
            "The university is also listed in the WHO World Directory of Medical Schools, and its graduates are eligible to sit the licensing examinations of their home countries.",
        ], 'img' => ['src' => 'assets/images/affil-hospt.webp', 'alt' => 'First Affiliated Hospital of Xinjiang Medical University, teaching hospital of an MOE listed MBBS university', 'caption' => 'The First Affiliated Hospital of Xinjiang Medical University.'],
        'link' => ['mbbs', 'About the MBBS programme at XJMU']],

        ['id' => 'choosing', 'eyebrow' => 'Choosing', 'h' => 'How to choose between MOE listed universities', 'p' => [
            "All 43 universities are approved, so the list alone does not tell you which one suits you. Compare them on the points that will shape your six years.",
        ], 'sub' => [
            ['h' => 'Total cost', 'p' => ["Compare the full yearly cost, not tuition alone. At XJMU the first-year package for the September 2026 intake is ¥49,950 and each later year totals about ¥35,950."]],
            ['h' => 'Clinical training', 'p' => ["Ask how many teaching hospitals the university has, how large they are and where international students do the internship year."]],
            ['h' => 'Location and daily life', 'p' => ["Climate, food and distance from home affect students more than they expect. Urumqi is close to Pakistan and Central Asia, and halal food is widely available."]],
            ['h' => 'Entry requirements', 'p' => ["Minimum marks differ. XJMU asks for at least 60% marks in each science subject and an age of 18 to 35."]],
            ['h' => 'Support for international students', 'p' => ["Look for a dedicated international student team, pre-arrival guidance and help with the visa and residence permit."]],
        ], 'table' => $s['feeYearly']],

        ['id' => 'seats', 'eyebrow' => 'Seats', 'h' => 'Seat quotas and why early application matters', 'p' => [
            "With 2,790 seats shared among 43 universities, the average quota is about 65 seats per university. Sixteen universities have 40 seats or fewer. Demand comes from students across South Asia, Central Asia, the Middle East and Africa, so popular universities fill their quota before the regular deadline.",
            "XJMU has 60 seats. The university's admissions calendar opens fall applications on 1 March, recommends applying by 30 June, and closes regular applications on 15 August. The MBBS also has a spring intake, with applications open from 1 September and a regular deadline of 15 January. Applying by the early deadline gives you the best chance of a seat and of scholarship consideration.",
        ], 'table' => $s['calendar']],

        ['id' => 'next-list', 'eyebrow' => 'Looking ahead', 'h' => 'When will the next MOE list be published?', 'p' => [
            "The ministry issues the list once a year. The notice for 2026–2027 was dated 1 June 2026. The notice for the following academic year has not been published, and the ministry does not announce the date in advance.",
            "If you are applying for 2027, plan with the current list and check again before you accept an offer. We will replace the table on this page as soon as the new notice is released, and we will keep this address the same so that your bookmark still works.",
        ], 'video' => $s['video']],

        ['id' => 'apply', 'eyebrow' => 'Next step', 'h' => 'How to apply to an MOE listed MBBS university', 'p' => [
            "The process is the same at most listed universities. At Xinjiang Medical University it works like this:",
        ], 'list' => [
            'Prepare your documents: passport, photograph, application form, physical examination form, police certificate, transcripts and a self-introduction video.',
            'Complete the online application and upload your documents.',
            'Pay the application fee of ¥1,000 to apply for the Pre-Admission Letter.',
            'Pay the ¥2,000 seat reservation deposit, refundable if your JW202 is rejected.',
            'Wait two to four weeks for the admissions committee to review your file.',
            'Receive your admission letter and JW202 form.',
            'Apply for the X1 or X2 student visa at the Chinese embassy.',
            'Arrive before the registration deadline and enrol.',
        ], 'img' => ['src' => 'images/class2.webp', 'alt' => 'English taught MBBS lecture for international students at Xinjiang Medical University', 'caption' => 'An English taught lecture for international MBBS students at XJMU.'],
        'link' => ['adm-HtApply', 'How to apply, step by step']],
    ],
    'faqs' => [
        ['What is a MOE listed university in China?', 'A MOE listed university is one that the Ministry of Education has named in its official notice as authorised to enrol international students in the English taught MBBS. For 2026–2027 the list has 43 universities, and Xinjiang Medical University is number 8 with 60 seats.'],
        ['How many MOE listed MBBS universities are there in China?', 'There are 43 for the 2026–2027 academic year, with a combined quota of 2,790 seats for international students.'],
        ['Is there an MOE list for 2027?', 'The list in force was issued on 1 June 2026 for the 2026–2027 academic year. The next notice has not been published yet. This page will be updated when it is.'],
        ['Is Xinjiang Medical University MOE listed?', 'Yes. It is number 8 on the 2026–2027 list with a quota of 60 seats.'],
        ['Is Xinjiang Medical University the same as Xinxiang Medical University?', 'No. They are two different universities with similar-sounding names. Xinjiang Medical University is in Urumqi, in the Xinjiang region, and is number 8 on the 2026–2027 MOE list. Xinxiang Medical University is in Xinxiang, in Henan province, and does not appear on the 43-university list in this guide.'],
        ['What is a registered MBBS university in China?', 'It is a university on the Ministry of Education list of institutions authorised to teach MBBS in English to international students.'],
        ['Can an unlisted university teach MBBS in English?', 'No. Unlisted universities may only admit international students to Chinese-medium clinical medicine and may not issue an English-language MBBS certificate.'],
        ['Is a bilingual MBBS programme allowed?', 'No. The notice strictly prohibits using a bilingual teaching model as a workaround.'],
        ['Which MOE listed university has the most seats?', 'Zhejiang University has the largest quota on the 2026–2027 list, with 180 seats.'],
        ['Does the order of the list show quality?', 'No. The order is the order of publication, not a ranking. Every university on the list is equally approved.'],
        ['Where can I find the official source?', 'The list comes from Ministry of Education notice 教外司来〔2026〕94号, issued by the Department of International Cooperation and Exchange on 1 June 2026.'],
        ['How do I check whether my university is approved?', 'Match the full university name against the list, confirm in writing that your offer is for the English taught MBBS, and check the World Directory of Medical Schools and your home medical council.'],
    ],
    'related' => ['mbbs-in-china-2027', 'english-taught-mbbs-in-china'],
];
