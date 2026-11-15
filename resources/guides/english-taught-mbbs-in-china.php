<?php

$s = require __DIR__ . '/_shared.php';

return [
    'updated' => '2026-11-16',
    'title' => 'English Taught MBBS in China 2027 | MOE Listed English Medium',
    'description' => 'English taught MBBS in China 2027: how English medium MBBS works at MOE listed universities, yearly curriculum, Chinese language rules, requirements and fees.',
    'keywords' => 'English taught MBBS, English taught MBBS in China, English medium MBBS in China 2027, MBBS in English in China, MOE listed MBBS, MBBS in China 2027, registered MBBS university in China, MBBS in China',
    'eyebrow' => 'English medium programme guide',
    'h1' => 'English Taught MBBS in China 2027',
    'lead' => 'How an English taught MBBS works at an MOE listed university: who is allowed to teach it, what you study each year, how much Chinese you need, and what it costs at Xinjiang Medical University.',
    'crumb' => 'English taught MBBS in China',
    'cta' => 'Apply for English taught MBBS',
    'sections' => [

        ['id' => 'what-it-is', 'eyebrow' => 'Overview', 'h' => 'What is an English taught MBBS in China?', 'p' => [
            "An English taught MBBS is the undergraduate clinical medicine degree for international students in China, delivered in English from the first lecture to the final examination. It lasts six years, including a one-year clinical internship, and leads to the MBBS degree.",
            "It is sometimes called English medium MBBS, MBBS in English or the international MBBS. All of these names describe one programme, and that programme can only be offered by universities on the Ministry of Education (MOE) list. For students planning MBBS in China 2027, there are 43 such universities on the list in force.",
            "This guide explains how the English taught MBBS works in practice, using the programme at Xinjiang Medical University (XJMU) as the example.",
        ], 'img' => ['src' => 'images/class2.webp', 'alt' => 'Lecturer teaching an English taught MBBS class on human parasitology to international students at Xinjiang Medical University', 'caption' => 'A lecture in English for international MBBS students at Xinjiang Medical University.'],
        'table' => ['caption' => 'English taught MBBS at XJMU: key facts', 'head' => ['Question', 'Answer'], 'rows' => [
            ['Language of teaching', 'English'],
            ['Duration', '6 years, including a 1-year internship'],
            ['Approved by', "China's Ministry of Education"],
            ['Seats for international students', '60 (2026–2027 list)'],
            ['Chinese language', 'Introductory Chinese in years 1–2; HSK-4 by graduation'],
            ['Clinical training', '8 directly affiliated hospitals'],
            ['Intakes', 'Spring (February/March) and fall (September)'],
        ]]],

        ['id' => 'who-can-teach', 'eyebrow' => 'Approval', 'h' => 'Which universities can teach MBBS in English?', 'p' => [
            "Only MOE listed MBBS universities. Each year the Ministry of Education publishes the universities authorised to enrol international students in the English taught programme, with a seat quota for each. The notice of 1 June 2026 approves 43 universities and 2,790 seats for the 2026–2027 academic year.",
            "The notice is strict about language. Universities outside the list may only admit international students to Chinese-medium clinical medicine, and they may not issue an English-language certificate bearing the term MBBS. Teaching partly in English and partly in Chinese, described as bilingual teaching, is prohibited as a way around the list.",
            "So when you look for a registered MBBS university in China that teaches in English, you are looking for a university on this list. Xinjiang Medical University is on it, with 60 seats.",
        ], 'note' => $s['moeSource'], 'link' => ['moe-listed-mbbs-universities-in-china', 'See all 43 MOE listed MBBS universities']],

        ['id' => 'three-types', 'eyebrow' => 'Compare', 'h' => 'English taught, bilingual and Chinese-medium: the difference', 'p' => [
            "Three kinds of medical programme are advertised to international students. Only one of them is the English taught MBBS.",
        ], 'table' => ['caption' => 'Programme types compared', 'head' => ['', 'English taught MBBS', 'Bilingual programme', 'Chinese-medium clinical medicine'], 'rows' => [
            ['Who may offer it', 'MOE listed universities only', 'Not permitted as a workaround', 'Any qualified medical university'],
            ['Language of lectures and exams', 'English', 'Mixed', 'Chinese'],
            ['English-language MBBS certificate', 'Yes', 'No', 'No'],
            ['Chinese needed before you start', 'Not required for teaching', 'Usually', 'High level required'],
            ['Standard applied', 'MOE standard for international MBBS', 'Not authorised', 'Same as domestic students'],
        ]], 'sub' => [
            ['h' => 'Why the difference matters', 'p' => [
                "The certificate you graduate with is what your home licensing body will examine. An English taught MBBS from a listed university is the programme the MOE has authorised for international students. A degree from an unlisted or bilingual programme may not be recognised at home, so confirm the programme type in writing before you accept any offer.",
            ]],
        ]],

        ['id' => 'year-by-year', 'eyebrow' => 'Curriculum', 'h' => 'What you study each year in the English taught MBBS', 'p' => [
            "The six years move from science to patients. This is the published structure of the MBBS at Xinjiang Medical University.",
        ], 'sub' => [
            ['h' => 'Years 1–2: basic medical sciences', 'p' => [
                "Anatomy, physiology, biochemistry, histology, medical chemistry and the foundational sciences, taught in English through lectures and laboratory sessions. Introductory Chinese language runs alongside, so that you can manage daily life from the first months.",
            ]],
            ['h' => 'Year 3: pre-clinical sciences', 'p' => [
                "Pathology, pharmacology, microbiology, immunology and forensic medicine. This is the year that connects the science of the body to the diagnosis and treatment of disease.",
            ]],
            ['h' => 'Years 4–5: clinical medicine', 'p' => [
                "Internal medicine, surgery, paediatrics, obstetrics and gynaecology, and specialty rotations, with bedside teaching in the affiliated hospitals.",
            ]],
            ['h' => 'Year 6: clinical internship', 'p' => [
                "A full year of supervised clinical rotations across the major departments, consolidating diagnostic and practical skills before graduation.",
            ]],
        ], 'img' => ['src' => 'assets/images/lab-img.webp', 'alt' => 'International MBBS students working in a medical sciences laboratory during the English taught MBBS in China', 'caption' => 'Laboratory work is part of the basic medical sciences in years 1–2.'],
        'link' => ['mbbs', 'See the full MBBS programme at XJMU']],

        ['id' => 'chinese', 'eyebrow' => 'Language', 'h' => 'Do you need Chinese for an English taught MBBS?', 'p' => [
            "You do not need Chinese to be admitted, and you do not need it to follow lectures or sit examinations, because the programme is taught entirely in English.",
            "You will still learn Chinese during the degree, for two good reasons. The first is daily life: shopping, transport and making friends are all easier with basic Chinese. The second is clinical work. Patients in the teaching hospitals speak Chinese, so to take a history and examine a patient you need to communicate with them directly.",
            "At XJMU, introductory Chinese is built into years 1 and 2, and HSK-4 Chinese proficiency is required by graduation to support clinical rotations. HSK is the standard Chinese proficiency test, and level 4 means you can discuss a fairly wide range of everyday topics. Because the classes are part of the timetable, students reach that level step by step over several years.",
        ], 'sub' => [
            ['h' => 'Is this the same as a bilingual programme?', 'p' => [
                "No. In the English taught MBBS, the medical subjects are taught and examined in English, and Chinese is taught as a language course. In a bilingual programme, the medical subjects themselves are taught partly in Chinese, which the MOE does not permit as a substitute for the English taught MBBS.",
            ]],
        ]],

        ['id' => 'english-requirement', 'eyebrow' => 'English', 'h' => 'English language requirements', 'p' => [
            "Because every lecture, textbook and examination is in English, the university needs to know that you can study in it. You can show this in one of two ways.",
        ], 'list' => [
            'An English test result such as IELTS or TOEFL',
            'Proof that your previous education was taught in English',
        ], 'sub' => [
            ['h' => 'Exemptions', 'p' => [
                "Some exemptions may apply for students from English-speaking countries. If your school taught in English, ask it for a letter confirming the medium of instruction; it is a simple document that saves the cost of a test.",
            ]],
            ['h' => 'How good does my English need to be?', 'p' => [
                "Good enough to read a science textbook and follow a lecture without translating in your head. Medical English has a large vocabulary of its own, and everyone learns it during the first two years. If you studied Physics, Chemistry and Biology in English at school, you already have the base you need.",
            ]],
        ]],

        ['id' => 'requirements', 'eyebrow' => 'Eligibility', 'h' => 'Admission requirements for the English taught MBBS', 'p' => [
            "These are the published requirements for the MBBS at Xinjiang Medical University.",
        ], 'list' => $s['requirements'], 'sub' => [
            ['h' => 'Documents', 'p' => [
                "You will need your passport, a white-background photograph, the XJMU application form, the Chinese physical examination form and blood report, a police non-criminal record certificate, your senior high-school transcripts and certificates, and a short self-introduction video.",
            ]],
            ['h' => 'Home-country conditions', 'p' => [
                "Some countries set extra conditions for students who study medicine abroad. Check your own medical council's current rules before you apply.",
            ]],
        ], 'link' => ['adm-HtApply', 'How to apply, step by step']],

        ['id' => 'clinical', 'eyebrow' => 'Clinical training', 'h' => 'Clinical training and the internship year', 'p' => [
            "The value of a medical degree depends on how much real medicine you see. Xinjiang Medical University has 8 directly affiliated hospitals, 18 teaching hospitals and 95 internship hospitals, so clinical teaching does not depend on a single site.",
            "The First Affiliated Hospital is the largest and most comprehensive of them. It is a Class A tertiary hospital, the highest grade in China's hospital system, and serves as the main centre for complex cases, critical care and emergencies in the region.",
            "From year 4, teaching moves to the bedside. In year 6 you rotate through the major departments under supervision for a full year. This is where the Chinese you have learned is put to use, alongside the medicine.",
        ], 'img' => ['src' => 'assets/images/clinic.webp', 'alt' => 'Medical student examining a patient during clinical training in the English taught MBBS programme in China', 'caption' => 'Hands-on clinical training in an affiliated hospital.']],

        ['id' => 'fees', 'eyebrow' => 'Fees', 'h' => 'English taught MBBS fees', 'p' => [
            "The English taught MBBS at XJMU is one of the more affordable MOE listed options. Fees for 2027 have not been announced; these are the published figures for the September 2026 intake.",
        ], 'table' => $s['feeYearly'], 'sub' => [
            ['h' => 'The first year', 'p' => [
                "The first year is charged as a single all-inclusive package of ¥49,950, about USD 7,000. It covers application processing, tuition, hostel, the student visa, health insurance and medical, registration, admission and management charges, the JW202 visa letter and airport pickup.",
            ]],
            ['h' => 'Living costs', 'p' => [
                "Budget USD 150–250 a month for food, USD 10–20 for local transport and USD 10–15 for a mobile SIM.",
            ]],
            ['h' => 'Scholarships', 'p' => [
                "Eligible students can apply for the Chinese Government Scholarship and other awards listed by the university. Apply by the early deadline to be considered.",
            ]],
        ], 'note' => 'Figures are indicative for the 2026 intake; USD conversions are approximate.', 'link' => ['fees', 'Full fee breakdown']],

        ['id' => 'recognition', 'eyebrow' => 'Recognition', 'h' => 'Is an English taught MBBS from China recognised?', 'p' => [
            "Recognition has three layers. In China, the programme must be on the MOE list. Internationally, the school should appear in the WHO World Directory of Medical Schools. At home, your medical council decides whether you can sit its licensing examination.",
            "Xinjiang Medical University is MOE listed and is in the World Directory of Medical Schools. Its graduates are eligible to appear for the licensing examinations of their home countries, and for international examinations such as the USMLE. The university's Clinical Medicine discipline is ESI-ranked, placing in the top 2.07‰ worldwide.",
            "Councils update their rules, so read the current requirements for your own country before you apply.",
        ]],

        ['id' => 'who-is-it-for', 'eyebrow' => 'Is it right for you', 'h' => 'Who is the English taught MBBS for?', 'p' => [
            "The programme suits students who want to become doctors, studied science in school, and are ready to live abroad for six years.",
        ], 'list' => [
            'You completed senior high school with at least 60% in each science subject.',
            'You can study in English.',
            'You want a government-approved programme with published fees.',
            'You are willing to learn Chinese for daily life and clinical work.',
            'You plan to sit a licensing examination after graduation.',
        ], 'sub' => [
            ['h' => 'What students find hardest', 'p' => [
                "The first semester. A new country, a new language outside the classroom and a heavy science timetable arrive together. Students who settle fastest are the ones who attend the Chinese classes from the first week and form study groups early.",
            ]],
        ], 'img' => ['src' => 'images/activity1.webp', 'alt' => 'International MBBS students sharing a cultural activity together at Xinjiang Medical University', 'caption' => 'International students at a campus cultural activity.']],

        ['id' => 'study-tips', 'eyebrow' => 'Study', 'h' => 'How to succeed in an English medium MBBS', 'sub' => [
            ['h' => 'Build medical vocabulary early', 'p' => ["Anatomy and physiology introduce thousands of new terms. Keep a vocabulary notebook from week one and revise it daily."]],
            ['h' => 'Treat Chinese as a core subject', 'p' => ["Your clinical years depend on it. Ten minutes of practice a day in years 1 and 2 makes the hospital years far easier."]],
            ['h' => 'Use the laboratories and the library', 'p' => ["Practical sessions make the theory stick. The university library gives you a quiet place to work between classes."]],
            ['h' => 'Prepare for licensing from year 3', 'p' => ["Licensing examinations test the basic sciences as well as clinical medicine. Students who revise for them alongside their courses avoid a long gap after graduation."]],
            ['h' => 'Ask for help', 'p' => ["The international student office supports students with academic, visa and settling-in questions. Use it."]],
        ], 'p' => [
            "Students who do well in the English taught MBBS tend to share a few habits.",
        ], 'img' => ['src' => 'assets/images/library.webp', 'alt' => 'Library building of Xinjiang Medical University used by English taught MBBS students', 'caption' => 'The Xinjiang Medical University library.']],

        ['id' => 'how-taught', 'eyebrow' => 'In the classroom', 'h' => 'How the English taught MBBS is delivered', 'p' => [
            "English taught does not only mean that the lecturer speaks English. It covers every part of the course that is assessed.",
        ], 'sub' => [
            ['h' => 'Lectures and teaching materials', 'p' => [
                "Lectures for the international class are given in English, with slides and teaching materials in English.",
            ]],
            ['h' => 'Laboratories and practical sessions', 'p' => [
                "The basic sciences are learned by doing as well as by listening. Laboratory sessions in subjects such as anatomy, histology and biochemistry put the week's lectures into practice.",
            ]],
            ['h' => 'Examinations', 'p' => [
                "Because the medical subjects are taught in English, they are examined in English. Chinese language is assessed separately, as a language course, and through the HSK test.",
            ]],
            ['h' => 'Bedside teaching', 'p' => [
                "In the clinical years, teaching happens on the wards of the affiliated hospitals. Teaching for international students remains in English, while conversations with patients take place in Chinese. This is the point at which your Chinese classes start to pay off.",
            ]],
        ], 'img' => ['src' => 'images/class1.webp', 'alt' => 'International students in a classroom session of the English medium MBBS at Xinjiang Medical University', 'caption' => 'International MBBS students in class at XJMU.']],

        ['id' => 'location', 'eyebrow' => 'Where you will live', 'h' => 'Studying an English taught MBBS in Urumqi', 'p' => [
            "Xinjiang Medical University is in Urumqi, the capital of Xinjiang in northwest China. For students from Pakistan it is among the closest places in China to home: a direct flight from Islamabad takes about 2 hours 50 minutes.",
            "The city is culturally familiar to many international students. Halal food is widely available, and the bazaars, parks and nearby mountains give students plenty to do outside the timetable. Accommodation is in university hostels and is part of the published fee structure.",
            "The university has a dedicated international student office that helps with the visa, accommodation and settling in, and the first-year package includes airport pickup.",
        ], 'img' => ['src' => 'assets/images/localbazar.webp', 'alt' => 'Local bazaar street in Urumqi, the city where Xinjiang Medical University teaches its English taught MBBS', 'caption' => 'A bazaar street in Urumqi.']],

        ['id' => 'parents', 'eyebrow' => 'For parents', 'h' => 'What parents usually ask about an English medium MBBS', 'sub' => [
            ['h' => 'Is the programme really approved?', 'p' => [
                "Yes, if the university is on the Ministry of Education list. Xinjiang Medical University is number 8 on the list for 2026–2027. You can check the list yourself on our MOE listed universities page.",
            ]],
            ['h' => 'Will my child be able to practise at home?', 'p' => [
                "Graduates are eligible to sit the licensing examination of their home country. Passing that examination, and meeting the council's other conditions, is what leads to registration. Read your council's current rules before applying.",
            ]],
            ['h' => 'What will six years cost in total?', 'p' => [
                "Using the 2026 figures, the first year is ¥49,950 and each of the following five years is about ¥35,950, plus living costs of roughly USD 150–250 a month for food. Fees for 2027 have not been announced.",
            ]],
            ['h' => 'Who looks after students when they arrive?', 'p' => [
                "The admissions team provides pre-arrival guidance, students are met at the airport, and the international student office supports them through registration, the health check and the residence permit.",
            ]],
            ['h' => 'How do we stay in touch with the university?', 'p' => [
                "Admissions counsellors can be reached by phone, email and WhatsApp, and the online portal shows the status of an application in real time.",
            ]],
        ], 'p' => [
            "Parents pay for the degree and worry about it more than students do. These are the questions they ask most often.",
        ]],

        ['id' => 'mistakes', 'eyebrow' => 'Avoid these', 'h' => 'Mistakes to avoid when choosing an English taught MBBS', 'list' => [
            'Accepting an offer described as bilingual, or as English with some Chinese. That is not the English taught MBBS.',
            'Trusting a brochure instead of the MOE list. Check the university name against the list yourself.',
            'Ignoring the Chinese classes in years 1 and 2. You will need the language in the hospital.',
            'Comparing tuition only. Compare the full yearly total, including hostel, insurance and the residence permit.',
            'Waiting for the regular deadline. Seats are limited to the MOE quota, so apply by the early deadline.',
        ], 'p' => [
            "Most problems students run into could have been avoided before they applied.",
        ]],

        ['id' => 'glossary', 'eyebrow' => 'Glossary', 'h' => 'Terms you will meet when applying', 'p' => [
            "Applications for MBBS in China use a small set of terms and abbreviations. Knowing them makes every form easier to fill in.",
        ], 'table' => ['caption' => 'English taught MBBS glossary', 'head' => ['Term', 'What it means'], 'rows' => [
            ['MOE', "China's Ministry of Education, which approves the universities that may teach MBBS in English"],
            ['MOE listed', 'On the yearly MOE list of universities authorised for the English taught MBBS'],
            ['WDOMS', 'The World Directory of Medical Schools, where licensing bodies check that a medical school is recognised'],
            ['HSK', 'The standard Chinese proficiency test; level 4 is required by graduation at XJMU'],
            ['JW202', 'The visa application form for study in China, issued with your admission letter'],
            ['X1 visa', 'The student visa for study longer than six months, converted to a residence permit after arrival'],
            ['Residence permit', 'The permit that lets you live in China during your studies, renewed each year'],
            ['Internship year', 'The sixth year of the MBBS, spent in supervised hospital rotations'],
        ]]],

        ['id' => 'apply', 'eyebrow' => 'Next step', 'h' => 'How to apply for the English taught MBBS in 2027', 'p' => [
            "The English taught MBBS has two intakes. For spring 2027, applications opened on 1 September, with an early deadline of 15 December, a regular deadline of 15 January and registration around 20 February. For fall 2027, applications open on 1 March, with an early deadline of 30 June, a regular deadline of 15 August and registration around 10 September. Dates may vary, so confirm them with the admissions team.",
        ], 'table' => $s['calendar'], 'list' => [
            'Prepare and notarise your documents.',
            'Apply online and upload the documents.',
            'Pay the ¥1,000 application fee, then the ¥2,000 seat reservation deposit after your Pre-Admission Letter.',
            'Receive your admission letter and JW202 form after review, which takes two to four weeks.',
            'Apply for the X1 student visa and travel before registration.',
        ], 'video' => $s['video']],
    ],
    'faqs' => [
        ['Is MBBS in China taught in English?', 'Yes, at the 43 universities on the Ministry of Education list for 2026–2027. Other universities may only teach clinical medicine to international students in Chinese.'],
        ['Is the English taught MBBS at Xinjiang Medical University fully in English?', 'Yes. It is taught entirely in English. HSK-4 Chinese proficiency is required by graduation to support clinical rotations.'],
        ['Do I need IELTS for an English taught MBBS in China?', 'You need to show you can study in English. That can be an IELTS or TOEFL result, or proof that your previous education was in English. Exemptions may apply for students from English-speaking countries.'],
        ['Do I need to know Chinese before I start?', 'No. Introductory Chinese is taught during years 1 and 2 of the programme.'],
        ['What is the difference between English taught and bilingual MBBS?', 'In the English taught MBBS, medical subjects are taught and examined in English. Bilingual teaching as a substitute is prohibited by the Ministry of Education.'],
        ['How long is the English taught MBBS?', 'Six years, including a one-year clinical internship.'],
        ['How much does the English taught MBBS cost?', 'At Xinjiang Medical University the first-year package for the September 2026 intake is ¥49,950, and each later year totals about ¥35,950. Fees for 2027 have not been announced.'],
        ['How many seats are there?', 'The MOE quota for Xinjiang Medical University is 60 seats for the 2026–2027 academic year.'],
        ['Will I treat patients in English?', 'Patients in the teaching hospitals speak Chinese. That is why Chinese is taught during the degree and HSK-4 is required by graduation.'],
        ['Can I take the USMLE after an English taught MBBS in China?', 'Graduates of Xinjiang Medical University are eligible to sit licensing examinations including the USMLE, subject to the conditions of each examining body.'],
    ],
    'related' => ['mbbs-in-china-2027', 'moe-listed-mbbs-universities-in-china'],
];
