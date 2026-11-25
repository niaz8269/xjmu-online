<?php

/*
| Code-side FAQs for the main pages and selected blog posts (added 21 Nov 2026).
| They are shown on the page and marked up as FAQPage, so search engines and AI answers can quote them.
| No database rows are changed: admin-managed FAQs still come first, these are added after them
| (a question that the admin already has is not repeated).
|
| RULE: every answer uses facts already published on the site (fee structure, MOE notice of 1 June 2026,
| admissions calendar, requirements) or confirmed by the owner (ECFMG eligible, HSK-4 by graduation, PMDC NRE).
*/

$q = [
    'moe' => ['Is Xinjiang Medical University MOE listed for MBBS?', 'Yes. Xinjiang Medical University is number 8 of the 43 universities on the Ministry of Education list issued on 1 June 2026, with 60 seats for the English taught MBBS in 2026–2027.'],
    'moe_count' => ['How many MOE listed MBBS universities are there in China?', 'The Ministry of Education notice of 1 June 2026 lists 43 universities allowed to teach MBBS in English to international students, with 2,790 seats in total. Only apply to a university on this list.'],
    'moe_check' => ['How can I check that a Chinese university is MOE listed?', 'Find the university by name in the Ministry of Education notice for the current academic year. The notice of 1 June 2026 lists 43 universities with their seat quotas. If a university is not on the list, it cannot admit international students to an English taught MBBS.'],
    'wdoms' => ['Is Xinjiang Medical University WHO recognised?', 'The WHO no longer keeps its own list. Medical schools are recorded in the World Directory of Medical Schools (WDOMS), and Xinjiang Medical University is listed there.'],
    'ecfmg' => ['Can XJMU graduates take the USMLE?', 'Yes. XJMU is ECFMG eligible, so its students and graduates can apply for ECFMG certification and sit the USMLE. ECFMG rules can change, so check its current requirements when you plan this route.'],
    'councils' => ['Is the XJMU MBBS degree recognised by PMDC and NMC?', 'XJMU states that it is recognised by PMDC, NMC and other medical councils. Each council sets its own conditions for foreign graduates, so confirm the current position with your council. Each council sets its own conditions and updates them, so read your council\'s current rules before you apply.'],
    'english' => ['Is MBBS at Xinjiang Medical University taught in English?', 'Yes. The MBBS is taught in English. Introductory Chinese is taught in years 1 and 2, and HSK-4 Chinese is required by graduation so that you can talk to patients during clinical training.'],
    'hsk' => ['Do I need to know Chinese before I start MBBS in China?', 'No. You do not need Chinese to apply. Introductory Chinese is taught in years 1 and 2, and HSK-4 is required by graduation for clinical work with patients.'],
    'duration' => ['How long is MBBS in China?', 'MBBS in China takes six years: five years of study followed by a one-year clinical internship.'],
    'clinical' => ['Is MBBS in China the same as Clinical Medicine?', 'Yes. In China the MBBS programme is officially called Clinical Medicine. The English taught Clinical Medicine degree at an MOE listed university leads to the MBBS.'],
    'fee_total' => ['What is the total cost of MBBS at Xinjiang Medical University?', 'The first-year package is ¥49,950 (about USD 7,000) and each later year is ¥35,950 (about USD 5,050). University fees for all six years total about ¥229,700, or about USD 32,000, before food and personal costs. Fees for the 2027 intakes are confirmed and unchanged from 2026.'],
    'fee_first' => ['What does the first-year package of ¥49,950 include?', 'It covers application processing, registration and admission charges, tuition, hostel accommodation, the student visa, health insurance and medical checks, the JW202 visa letter and airport pickup.'],
    'fee_yearly' => ['What do I pay from the second year?', '¥35,950 a year: tuition ¥30,000, accommodation ¥4,500, insurance ¥300, residence permit ¥800 and physical examination ¥350.'],
    'fee_payments' => ['Which payments are made before arrival?', 'A ¥1,000 application fee, which is non-refundable once the Pre-Admission Letter is issued and is part of the package, and a ¥2,000 seat reservation deposit after the Pre-Admission Letter, which is refunded if the JW202 visa letter is rejected.'],
    'living' => ['How much does it cost to live in Urumqi as a student?', 'Food usually costs about USD 150–250 a month, local transport about USD 10–20 and a mobile SIM about USD 10–15. Hostel accommodation is included in the first-year package.'],
    'halal' => ['Is halal food available in Urumqi?', 'Yes. Urumqi has a large Muslim population, so halal food is easy to find around the campus and across the city.'],
    'cheap' => ['Is MBBS in China cheaper than private MBBS at home?', 'Often, yes. At XJMU the six-year university fees total about ¥229,700 (about USD 32,000). Compare this with the full cost of private medical colleges at home, and always check that the Chinese university is MOE listed.'],
    'eligibility' => ['What are the eligibility requirements for MBBS in China?', 'A senior high-school certificate (HSSC, A-Level or equivalent) with science subjects, at least 60% in each science subject, age 18 to 35, good health and a valid passport.'],
    'percent' => ['What percentage is required for MBBS in China?', 'Xinjiang Medical University requires at least 60% in each science subject (physics, chemistry and biology) at higher secondary level.'],
    'age' => ['What is the age limit for MBBS in China?', 'Applicants to Xinjiang Medical University should be between 18 and 35 years old.'],
    'documents' => ['Which documents do I need to apply?', 'Your passport, a photograph with a white background, the XJMU application form, the Chinese physical examination form and blood report, a police non-criminal record certificate, senior high-school transcripts and certificates, and a short self-introduction video.'],
    'intakes' => ['When are the MBBS intakes at XJMU?', 'There are two intakes: the March/Spring intake, starting in February or March, and the September/Fall intake, starting in September.'],
    'spring_dates' => ['What are the March/Spring 2027 deadlines?', 'Applications opened on 1 September. The early deadline is 15 December, the regular deadline is 15 January and registration is around 20 February.'],
    'fall_dates' => ['What are the September/Fall 2027 deadlines?', 'Applications open on 1 March. The early deadline is 30 June, the regular deadline is 15 August and registration is around 10 September.'],
    'apply' => ['How do I apply for MBBS at Xinjiang Medical University?', 'Fill in the online application form on this site, upload your documents and pay the ¥1,000 application fee. After review you receive a Pre-Admission Letter, pay the ¥2,000 seat deposit, and then receive the JW202 letter and admission letter for your student visa.'],
    'early' => ['Should I apply before the early deadline?', 'Yes. Seats are limited (XJMU has 60 on the 2026–2027 MOE list), and scholarship applicants should apply by the early deadline: 15 December for March/Spring and 30 June for September/Fall.'],
    'neet' => ['Do Indian students need NEET for MBBS in China?', 'Yes. Under the NMC rules for foreign medical graduates, Indian students generally need to qualify NEET before studying medicine abroad. Read the NMC\'s current regulations before you apply.'],
    'pak_nre' => ['What do Pakistani graduates need to practise in Pakistan?', 'Graduates sit the PMDC National Registration Examination (NRE), complete the house job and register with the PMDC. Check the PMDC\'s current notices for the exam format and dates.'],
    'after' => ['What can I do after MBBS in China?', 'You can return home and pass your national licensing exam, such as the PMDC NRE or the NMC exam, prepare for the USMLE (XJMU is ECFMG eligible) or PLAB, study for a master\'s or PhD, or choose a non-clinical career.'],
    'safe' => ['Is Urumqi safe for international students?', 'Urumqi is a large, modern city with a long-established international student community. The university supports students from arrival, including airport pickup, and you should follow the university\'s guidance and local rules as anywhere else.'],
    'distance' => ['Why do students from Pakistan and Central Asia choose Urumqi?', 'Urumqi is in north-west China, one of the closest Chinese cities to Pakistan and Central Asia, which makes travel home shorter, and halal food and a familiar culture are easy to find.'],
    'csc' => ['Can international MBBS students get a scholarship at XJMU?', 'Yes. The Chinese Government Scholarship (CSC) makes tuition, accommodation and insurance free and pays undergraduates ¥30,000 a year. The Xinjiang Government Scholarship for students from neighbouring countries pays undergraduates ¥10,000 a year.'],
    'csc_amounts' => ['How much is the scholarship stipend?', 'Per year: CSC ¥30,000 (undergraduate), ¥36,000 (master\'s) and ¥42,000 (doctoral); Xinjiang Government Scholarship ¥10,000, ¥12,200 and ¥15,000.'],
    'csc_who' => ['Who can apply for the Xinjiang Government Scholarship?', 'It is for international students from countries neighbouring Xinjiang. Ask the admissions office whether your country qualifies before you apply.'],
    'csc_when' => ['When should I apply for a scholarship?', 'Apply by the early deadline of your intake (15 December for March/Spring, 30 June for September/Fall), with a complete application, so you can be considered.'],
    'csc_full' => ['Is there a fully funded scholarship for MBBS in China?', 'The Chinese Government Scholarship (CSC) is the closest to fully funded: tuition, accommodation and insurance are free and undergraduates receive a ¥30,000 yearly stipend. Places are limited and competitive.'],
    'csc_without' => ['Can I study MBBS at XJMU without a scholarship?', 'Yes. Most students pay the published fees: ¥49,950 for the first-year package and ¥35,950 a year after that.'],
    'seats' => ['How many MBBS seats does XJMU have?', 'Xinjiang Medical University has 60 seats for international MBBS students on the Ministry of Education list for 2026–2027.'],
    'worth' => ['Is MBBS in China worth it?', 'It can be, if you choose an MOE listed university, study in English, and prepare for your home licensing exam from year one. The main benefits are lower fees and modern hospitals; the main challenges are learning Chinese for patients and passing the exam back home.'],
];

$pick = fn (string ...$keys) => array_map(fn ($k) => $q[$k], $keys);

// The five fee questions that have been on /fees since launch (moved here on 22 Nov 2026 so the whole list is editable).
$feesFirst = [
    ['How much does MBBS cost in China at XJMU?', 'The complete first-year package is approximately ¥49,950 (about US$7,000). From the second year onward, the total is about ¥35,950 per year, which includes tuition, accommodation, insurance, residence permit and physical examination.'],
    ['What is included in the first-year MBBS fee package?', 'The first-year package covers the application processing fee, tuition, hostel, visa, health insurance and medical, registration fee, management and admission charges, JW202 visa letter and airport pickup.'],
    ['Are there any hidden charges?', 'No. XJMU publishes a transparent, all-inclusive fee structure. Living expenses (food and personal costs) are separate and typically range from US$150–250 per month.'],
    ['Can I get a scholarship to reduce MBBS fees?', 'Yes. Eligible international students can apply for CSC government scholarships and merit awards. Visit our scholarships page or speak to a counsellor for guidance.'],
    ['How and when do I pay the fees?', 'Fees are paid annually after you receive your admission letter. Our admissions team guides you through the payment process for the September 2027 intake.'],
];

return [
    'home' => $pick('moe', 'fee_total', 'eligibility', 'intakes', 'english', 'councils', 'ecfmg', 'wdoms', 'apply', 'csc'),
    'mbbs' => $pick('duration', 'clinical', 'moe', 'english', 'eligibility', 'documents', 'intakes', 'councils', 'ecfmg', 'seats'),
    'fees' => array_merge($feesFirst, $pick('fee_first', 'fee_yearly', 'fee_total', 'fee_payments', 'living', 'csc', 'cheap')),
    'admissions' => $pick('eligibility', 'percent', 'age', 'documents', 'intakes', 'spring_dates', 'fall_dates', 'apply', 'early', 'fee_payments', 'neet', 'hsk'),
    'scholarships' => $pick('csc', 'csc_amounts', 'csc_full', 'csc_who', 'csc_when', 'csc_without', 'fee_total', 'early'),

    // Blog posts by slug (posts whose own content already has an FAQ block are not listed).
    'blogs' => [
        'advantages-of-studying-mbbs-in-china-for-international-students-complete-2026-guide' => $pick('worth', 'moe_count', 'english', 'cheap', 'duration', 'after'),
        'advantages-of-studying-mbbs-in-china-for-international-students-complete-2027-guide' => $pick('worth', 'moe_count', 'english', 'fee_total', 'intakes', 'ecfmg'),
        'china-moe-releases-official-mbbs-english-taught-university-list-for-2026-2027' => $pick('moe_count', 'moe', 'moe_check', 'clinical', 'english'),
        'complete-list-of-43-moe-listed-universities-in-china-for-mbbs-2026-2027-xjmu-online' => $pick('moe_count', 'moe_check', 'moe', 'wdoms', 'seats'),
        'from-mbbs-student-to-global-doctor-international-career-pathways-after-graduating-from-xjmu' => $pick('after', 'ecfmg', 'pak_nre', 'neet', 'councils', 'hsk'),
        'mbbs-at-xinjiang-medical-university-2026-moe-listed-english-taught-program' => $pick('moe', 'english', 'duration', 'fee_total', 'eligibility', 'intakes'),
        'mbbs-in-china-2026-admissions-guide-xinjiang-medical-university' => $pick('eligibility', 'documents', 'apply', 'spring_dates', 'fall_dates', 'fee_payments'),
        'mbbs-in-china-for-pakistani-students-study-abroad-while-still-close-to-home-xjmu-2026' => $pick('pak_nre', 'councils', 'distance', 'halal', 'fee_total', 'percent'),
        'xinjiang-medical-university-mbbs-fee-structure-Sept-2026' => $pick('fee_first', 'fee_yearly', 'fee_total', 'fee_payments', 'living', 'csc'),
        'xjmu-september-2026-intake-60-seats-20-countries-is-your-seat-still-available' => $pick('seats', 'intakes', 'spring_dates', 'fall_dates', 'early', 'apply'),
        'study-in-china-mbbs-in-china-top-chinese-medical-university-xinjiang-medical-university' => $pick('moe', 'english', 'fee_total', 'safe', 'halal', 'apply'),
        'why-xinjiang-medical-university-is-the-ultimate-destination-for-your-mbbs-in-2026' => $pick('moe', 'distance', 'fee_total', 'english', 'councils', 'intakes'),
    ],
];
