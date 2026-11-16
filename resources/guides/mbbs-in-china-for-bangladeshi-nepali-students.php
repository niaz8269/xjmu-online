<?php

$s = require __DIR__ . '/_shared.php';

return [
    'updated' => '2026-11-17',
    'title' => 'MBBS in China for Bangladeshi & Nepali Students 2027 | XJMU',
    'description' => 'MBBS in China for Bangladeshi and Nepali students 2027: XJMU requirements, fees, home-country checks, documents, scholarships, how to apply to an MOE listed university',
    'keywords' => 'MBBS in China for Bangladeshi students, MBBS in China for Nepali students, MBBS in China for Sri Lankan students, MBBS in China requirements, MBBS in China 2027, MOE listed MBBS, English taught MBBS, Xinjiang Medical University',
    'eyebrow' => 'Guide for students from South Asia',
    'h1' => 'MBBS in China for Bangladeshi and Nepali Students 2027',
    'lead' => 'A practical guide for students and parents from Bangladesh, Nepal and Sri Lanka: what Xinjiang Medical University, an MOE listed university, requires, what it costs, which questions to settle with your own medical council, the documents you need and how to apply for the September or spring intake.',
    'crumb' => 'MBBS in China for Bangladeshi and Nepali students',
    'cta' => 'Talk to admissions',
    'sections' => [

        ['id' => 'overview', 'eyebrow' => 'Overview', 'h' => 'Studying MBBS in China from Bangladesh, Nepal or Sri Lanka', 'p' => [
            "Students from Bangladesh, Nepal and Sri Lanka are among those who ask us about an MBBS in China. They share one important feature: for each country, the rules that decide whether a foreign medical degree can lead to registration at home are set by that country's own medical council or equivalent body. This guide explains what the Chinese university requires and what it costs, and it tells you exactly what to check at home.",
            "Xinjiang Medical University (XJMU) in Urumqi is number 8 on the Ministry of Education (MOE) list of universities approved to teach the English taught MBBS to international students for 2026–2027, with 60 seats. The programme lasts six years, including a one-year internship, and the published fees are in the tables below.",
            "We do not publish the home-country rules for each nationality, because they change and because a wrong summary can cost a student years. Instead we give you the questions to ask, and we recommend that you read the current notices of your own council before you pay any fee.",
        ], 'img' => ['src' => 'assets/images/counselling.webp', 'alt' => 'Students and parents from South Asia discussing MBBS in China with a counsellor', 'caption' => 'Settle your home-country rules before you apply.']],

        ['id' => 'checks', 'eyebrow' => 'Home country', 'h' => 'What to check with your own medical council', 'p' => [
            "Whichever country you come from, the questions are the same. Write the answers down and keep a copy of the page you found them on.",
        ], 'list' => [
            'Does your council recognise a medical degree from a university on the Chinese Ministry of Education list?',
            'Does the council require anything of students before they study abroad, such as a qualifying test, minimum marks or a registration with the council?',
            'Does the programme meet the council\'s conditions on length, language of teaching and internship?',
            'What must a foreign medical graduate pass to be registered at home, and how often is the examination held?',
            'Is there a limit on the number of years or attempts for that examination?',
            'Are there conditions on where the internship may be done?',
        ], 'sub' => [
            ['h' => 'Where to find the answers', 'p' => [
                "Read the council's own website and published notices, or write to the council and keep the reply. Do not rely on a forum post, a video or an agent's summary. Ask XJMU admissions to confirm in writing that the programme meets the conditions you have found.",
            ]],
        ]],

        ['id' => 'xjmu', 'eyebrow' => 'The university', 'h' => 'Xinjiang Medical University at a glance', 'p' => [
            "XJMU is in Urumqi, the capital of the Xinjiang region in western China. It is easy to confuse with Xinxiang Medical University, which is in Henan province and is not on the 2026–2027 MOE list of 43 universities.",
        ], 'table' => ['caption' => 'XJMU MBBS at a glance', 'head' => ['Item', 'Detail'], 'rows' => [
            ['City', 'Urumqi, Xinjiang'],
            ['MOE list 2026–2027', 'Number 8, 60 seats'],
            ['Language of teaching', 'English, with introductory Chinese in years 1 and 2 and HSK-4 required by graduation'],
            ['Duration', '6 years including a 1-year internship'],
            ['Intakes', 'September and February/March'],
            ['First-year package', '¥49,950, about USD 7,000'],
            ['From the second year', '¥35,950 a year, about USD 5,050'],
        ]], 'img' => ['src' => 'assets/images/campus-img.webp', 'alt' => 'Xinjiang Medical University campus in Urumqi', 'caption' => 'The campus in Urumqi.']],

        ['id' => 'requirements', 'eyebrow' => 'Admission', 'h' => 'Admission requirements for international students', 'p' => [
            "These are the published requirements for the MBBS at XJMU. They apply to every international applicant, including students from Bangladesh, Nepal and Sri Lanka.",
        ], 'list' => $s['requirements'], 'sub' => [
            ['h' => 'Which certificate counts', 'p' => [
                "The requirement is a senior high-school certificate with science subjects, or an equivalent qualification. Ask admissions in writing whether your own certificate, for example the final school examination of your country, counts as equivalent, and how your marks are read.",
            ]],
            ['h' => 'Marks in each subject', 'p' => [
                "The 60% requirement applies to each of the science subjects, not only to the total. Check Physics, Chemistry and Biology separately.",
            ]],
            ['h' => 'Age and health', 'p' => [
                "Applicants must be between 18 and 35, in good health and free from communicable diseases.",
            ]],
        ]],

        ['id' => 'fees', 'eyebrow' => 'Cost', 'h' => 'Fees: the first year and after', 'p' => [
            "The fees are the same published fees for every international student. They are in Chinese yuan with approximate US dollar amounts. We do not print amounts in taka, rupees or other currencies because exchange rates move.",
        ], 'table' => $s['feeFirstYear'], 'sub' => [
            ['h' => 'From the second year', 'p' => [
                "The yearly fee is about ¥35,950, about USD 5,050.",
            ]],
        ]],

        ['id' => 'yearly', 'eyebrow' => 'Cost', 'h' => 'Yearly fees and the six-year total', 'p' => [
            "Over six years the university fees total about ¥229,700, about USD 32,000. That is the first-year package of ¥49,950 plus five years at ¥35,950.",
        ], 'table' => $s['feeYearly'], 'sub' => [
            ['h' => 'Converting to your currency', 'p' => [
                "Take the yuan amount, multiply by the current rate your bank offers, and add a margin of a few percent for transfer charges and rate changes. Do the sum again on the day you pay.",
            ]],
        ], 'link' => ['mbbs-in-china-fees-2027', 'See the full MBBS in China fee guide']],

        ['id' => 'living', 'eyebrow' => 'Living costs', 'h' => 'Cost of living in Urumqi', 'p' => [
            "Hostel accommodation is part of the fees, so you pay no rent. Everyday costs are modest.",
        ], 'table' => $s['living'], 'link' => ['life-in-urumqi-for-international-students', 'Read what life in Urumqi is like'], 'img' => ['src' => 'assets/images/localbazar.webp', 'alt' => 'Urumqi bazaar where South Asian students buy halal food and daily supplies', 'caption' => 'Halal food and daily supplies are available at local bazaars.']],

        ['id' => 'daily', 'eyebrow' => 'Daily life', 'h' => 'Daily life: food, language, climate and community', 'p' => [
            "Students from South Asia usually settle in faster than they expect, because they find other students from the region and because halal food is widely available in Urumqi. Many cook in groups, which keeps costs low and brings a taste of home.",
            "Lectures are in English, but daily life and patient contact are in Chinese. Introductory Chinese is taught in years 1 and 2 and HSK-4 is required by graduation, so begin early.",
            "The climate is dry and continental, with hot summers and cold snowy winters. A student from a warm, humid country will feel the first winter strongly. Buy a proper coat, boots with a good grip, gloves and thermal clothing, and ask seniors where to find them.",
        ], 'sub' => [
            ['h' => 'Staying connected with home', 'p' => [
                "Video calls work well, and many students talk to their family every day. A local SIM costs about USD 10 to 15 a month.",
            ]],
        ]],

        ['id' => 'documents', 'eyebrow' => 'Documents', 'h' => 'Documents you need', 'p' => [
            "Prepare these early. The medical report and the police certificate take the longest.",
        ], 'list' => $s['documents'], 'sub' => [
            ['h' => 'Certificates from your own country', 'p' => [
                "Ask admissions whether your school certificates need to be translated into English or certified, and in what form. Do this before you pay for any translation.",
            ]],
        ]],

        ['id' => 'apply', 'eyebrow' => 'How to apply', 'h' => 'How to apply, step by step', 'p' => [
            "The application is the same for all international applicants.",
        ], 'list' => [
            'Check that you meet the XJMU requirements and the conditions of your own medical council.',
            'Choose your intake, and plan to apply by the early deadline.',
            'Prepare your documents, record your self-introduction video and complete the medical report.',
            'Submit the application and pay the ¥1,000 application fee as instructed.',
            'After the Pre-Admission Letter, pay the ¥2,000 seat reservation deposit, which is refunded if the JW202 visa letter is rejected.',
            'Apply for your student visa with the JW202 letter and book your flight.',
        ], 'table' => $s['calendar']],

        ['id' => 'scholarship', 'eyebrow' => 'Scholarships', 'h' => 'Scholarships', 'p' => [
            "Two scholarships are listed in the published fee structure. Places are limited and competitive, so apply by the early deadline and ask admissions whether you are eligible.",
        ], 'table' => $s['scholarships'], 'link' => ['mbbs-in-china-scholarship-2027', 'Read the MBBS in China scholarship guide']],

        ['id' => 'nepal', 'eyebrow' => 'Nepal', 'h' => 'For Nepali students: points to think about', 'p' => [
            "Nepal is a neighbour of China, and many Nepali families find the idea of study in China natural. A few practical points are worth thinking about.",
        ], 'sub' => [
            ['h' => 'Distance and travel', 'p' => [
                "Routes between Nepal and Urumqi change over time. Ask your travel agent for current options and plan to book early, because fares rise close to the intake dates.",
            ]],
            ['h' => 'Recognition and registration', 'p' => [
                "Check the current rules of the Nepal Medical Council, or the body that regulates medical registration in Nepal, about foreign medical degrees and any examination for graduates. Keep a copy of what you find.",
            ]],
            ['h' => 'Language', 'p' => [
                "Some Nepali students have an advantage because they have heard Chinese before, but daily clinical Chinese is a new skill for most. Start in the first semester.",
            ]],
        ]],

        ['id' => 'bangladesh', 'eyebrow' => 'Bangladesh', 'h' => 'For Bangladeshi students: points to think about', 'p' => [
            "Bangladeshi families usually compare study in China with a seat at home and with other countries abroad. The comparison is fair only if you use the same checklist for each option.",
        ], 'sub' => [
            ['h' => 'Recognition and registration', 'p' => [
                "Check the current rules of the Bangladesh Medical and Dental Council, or the body that regulates registration in Bangladesh, about foreign medical degrees, any examination for graduates and any condition on studying abroad. Ask XJMU to confirm in writing that the programme meets the conditions you find.",
            ]],
            ['h' => 'Cost planning', 'p' => [
                "Plan the six-year total in yuan and convert it at the rate your bank offers on the day you pay. Keep a reserve for exchange-rate changes.",
            ]],
            ['h' => 'Climate', 'p' => [
                "Bangladesh is warm and humid, while Urumqi has cold dry winters. Plan your winter clothing before you arrive.",
            ]],
        ]],

        ['id' => 'srilanka', 'eyebrow' => 'Sri Lanka', 'h' => 'For Sri Lankan students: points to think about', 'p' => [
            "Students from Sri Lanka should check the conditions set by the Sri Lanka Medical Council, or the body that regulates registration, for foreign medical graduates, including any examination and any condition on the course. As for every other nationality, read the current notices and ask XJMU to confirm in writing that its programme meets them.",
            "The first winter is the biggest adjustment for students from the tropics. Seniors can tell you how they managed it.",
        ], 'img' => ['src' => 'assets/images/mountains.webp', 'alt' => 'Snow-capped Tianshan mountains near Urumqi in winter', 'caption' => 'Urumqi winters are cold, which surprises students from warm countries.']],

        ['id' => 'programme', 'eyebrow' => 'Programme', 'h' => 'What the six years look like', 'p' => [
            "The MBBS at XJMU follows the usual pattern of medical education: basic sciences first, then clinical subjects, then hospital rotations and a final internship. What is different is the setting, because you study in English in a Chinese university and meet Chinese-speaking patients in the later years.",
        ], 'sub' => [
            ['h' => 'Years 1 and 2', 'p' => [
                "You study the basic medical sciences and begin introductory Chinese. These are the years to build good study habits and to learn enough Chinese to manage daily life.",
            ]],
            ['h' => 'Years 3 and 4', 'p' => [
                "Teaching moves towards clinical subjects. Your Chinese becomes more useful as you meet patients and hospital staff.",
            ]],
            ['h' => 'Year 5', 'p' => [
                "You spend more time in the affiliated hospitals, learning from real cases.",
            ]],
            ['h' => 'Year 6', 'p' => [
                "The final year is a full clinical internship. Check that it matches any internship condition your own council sets, and ask the university to confirm in writing.",
            ]],
        ], 'img' => ['src' => 'assets/images/affil-hospt.webp', 'alt' => 'Affiliated hospital of Xinjiang Medical University where students complete clinical rotations', 'caption' => 'Clinical rotations take place in affiliated hospitals.']],

        ['id' => 'exam', 'eyebrow' => 'After graduation', 'h' => 'Preparing for the home licensing examination from the first year', 'p' => [
            "Many students treat the licensing examination at home as something to deal with after graduation. It is better to think about it from the first year, because the content builds on the whole programme.",
            "Find out from your own council what the examination covers, how it is structured and how often it is held. Ask seniors from your country how they prepare, which resources they use and when they start. Keep your notes organised by subject, and revise in every semester.",
            "Students who study steadily through the programme find the examination far less frightening than those who leave everything to the last year. Work backwards from the date you expect to sit it, and plan your final two years around it.",
        ]],

        ['id' => 'mistakes', 'eyebrow' => 'Avoid', 'h' => 'Mistakes families make when choosing China', 'p' => [
            "These patterns cause trouble, and each one is avoidable.",
        ], 'sub' => [
            ['h' => 'Relying on a single source', 'p' => [
                "A single agent, a single video or a single post is not enough. Check the Ministry of Education list, your council's notices and the university's own written replies.",
            ]],
            ['h' => 'Paying before checking', 'p' => [
                "Do not pay a deposit until you have read your council's conditions and received the university's answers in writing.",
            ]],
            ['h' => 'Ignoring the language', 'p' => [
                "Students who put off learning Chinese struggle in the clinical years. Begin in the first semester.",
            ]],
            ['h' => 'Comparing only the fee', 'p' => [
                "The cheapest quote is not always the best. Compare what each fee includes and the quality of the programme and the hospitals.",
            ]],
        ], 'img' => ['src' => 'assets/images/library.webp', 'alt' => 'Library at Xinjiang Medical University for international MBBS students', 'caption' => 'Students study in a well-equipped campus.']],

        ['id' => 'parents', 'eyebrow' => 'For parents', 'h' => 'What parents usually ask', 'p' => [
            "Parents ask about safety, food, communication and money. International students live together in the university hostel, are supported by the international office and can talk to seniors who have been through the first months. Halal food is easy to find in Urumqi. Video calls work well.",
            "On money, plan for the full six-year total, keep a reserve and send payments only through channels that the university has confirmed in writing. Keep every receipt.",
            "The best way to settle doubts is to speak to a family whose child already studies at XJMU. Ask admissions to arrange it. A short conversation with another parent often answers more than a day of reading.",
        ], 'link' => ['mbbs-in-china-for-indian-students', 'Read the guide for Indian students']],

        ['id' => 'timeline', 'eyebrow' => 'Planning', 'h' => 'A simple timeline from decision to departure', 'p' => [
            "Start earlier than you think you need to. The documents take longer than the form.",
        ], 'sub' => [
            ['h' => 'Four months before the early deadline', 'p' => [
                "Read your council's notices, check your marks against the 60% requirement in each science subject and request transcripts and certificates. Begin the medical examination and the police certificate.",
            ]],
            ['h' => 'Two months before', 'p' => [
                "Record your video, collect photographs and fill in the application form. Ask a teacher to check it for mistakes.",
            ]],
            ['h' => 'One month before', 'p' => [
                "Submit everything. Do not wait for the last week.",
            ]],
            ['h' => 'After the Pre-Admission Letter', 'p' => [
                "Pay the seat reservation deposit, apply for the visa with the JW202 letter, book your flight and buy winter clothing for your arrival.",
            ]],
        ], 'link' => ['mbbs-in-china-spring-intake-2027', 'See spring intake dates and deadlines']],

        ['id' => 'compare', 'eyebrow' => 'Comparing', 'h' => 'How to compare China with a seat at home or another country', 'p' => [
            "Students from South Asia often compare China with a seat at home, with other countries abroad, or with both. We do not publish claims about other countries, because we cannot verify them for you. A fair comparison uses the same checklist for every option.",
        ], 'list' => [
            'Is the university recognised by the education ministry of its own country?',
            'Does the programme meet the conditions that your council sets on length, language and internship?',
            'What is the full six-year cost, including hostel, visa and insurance?',
            'What language will you live in, and how will you manage it?',
            'How far is it from home, and how often can you travel?',
            'What is the climate, and how will you cope with it?',
            'What must you pass after graduation, and how long does that take?',
        ], 'sub' => [
            ['h' => 'Fill in a table', 'p' => [
                "Make one column for each option and one row for each question. Fill it with facts from official sources and written replies, not from advertisements. After an hour, the best choice for your situation is usually clear.",
            ]],
            ['h' => 'Ask someone who has done it', 'p' => [
                "Talk to a student from your own country who is studying in Urumqi now. Their account of the first months, the winter and the language is worth more than any brochure.",
            ]],
        ], 'link' => ['mbbs-in-china-vs-pakistan', 'Read the full comparison guide']],

        ['id' => 'risks', 'eyebrow' => 'Honest view', 'h' => 'The risks to weigh', 'p' => [
            "The main downsides are distance from home, the need to learn Chinese for clinical work, six years of study and the licensing examination after you return. Another risk is that rules change while you study, so keep reading your council's notices throughout the programme.",
            "The biggest avoidable risk is choosing without checking. Families who verify the university, the council's conditions and the costs rarely regret it.",
        ], 'video' => $s['video']],
    ],
    'faqs' => [
        ['Can Bangladeshi students study MBBS in China?', 'Yes. International students can apply for the MBBS at XJMU if they meet the published requirements. Check the current rules of your own medical council about registration after you graduate.'],
        ['Can Nepali students study MBBS in China?', 'Yes. Nepali students can apply under the same requirements. Check the current rules of the council that regulates medical registration in Nepal.'],
        ['What are the requirements for MBBS in China?', 'A senior high-school certificate with science subjects, at least 60% in each science subject, an age between 18 and 35, good health and a valid passport.'],
        ['How much does MBBS in China cost?', 'The first-year package is ¥49,950, about USD 7,000, and later years are about ¥35,950, about USD 5,050. The six-year university fees total about ¥229,700.'],
        ['Is XJMU recognised?', 'XJMU is number 8 on the 2026–2027 Ministry of Education list. Registration at home depends on your own council\'s rules, so read them and ask the university to confirm in writing.'],
        ['Is the MBBS taught in English?', 'Yes, with introductory Chinese in years 1 and 2 and HSK-4 required by graduation.'],
        ['How long is the MBBS?', 'Six years including a one-year internship.'],
        ['Is halal food available in Urumqi?', 'Yes. Xinjiang has large Muslim communities and halal restaurants and bazaars are widely available.'],
        ['When can I apply?', 'Fall applications open on 1 March with an early deadline of 30 June. Spring applications open on 1 September with an early deadline of 15 December.'],
        ['Are there scholarships?', 'Two scholarships are listed: the Chinese Government Scholarship and the Xinjiang Government Scholarship. Places are limited, so apply by the early deadline.'],
        ['Do I need to learn Chinese?', 'Yes. Lectures are in English, but daily life and patient contact are in Chinese.'],
        ['How do I avoid scams?', 'Check the university on the MOE list, pay only to the university or its confirmed channel, keep receipts and never pay for a guaranteed scholarship.'],
    ],
    'related' => ['mbbs-in-china-fees-2027', 'mbbs-in-china-for-indian-students', 'mbbs-in-china-2027'],
];
