<?php

$s = require __DIR__ . '/_shared.php';

// Exchange rates for the dirham and riyal table. Looked up on 25 November 2026.
// ECB euro reference rates (published 24 November 2026): EUR 1 = USD 1.0963 = CNY 7.3063, so CNY per USD = 6.6645.
// Dirham and riyal are pegged to the US dollar: AED 3.6725 (Central Bank of the UAE tables) and SAR 3.75 (SAMA).
$gx = ['date' => '25 November 2026', 'ecbDate' => '24 November 2026', 'eurUsd' => 1.0963, 'eurCny' => 7.3063, 'aedUsd' => 3.6725, 'sarUsd' => 3.75];
$gx['cnyUsd'] = $gx['eurCny'] / $gx['eurUsd'];
$gx['aed'] = $gx['aedUsd'] / $gx['cnyUsd'];
$gx['sar'] = $gx['sarUsd'] / $gx['cnyUsd'];
$gulfMoney = fn (float $yuan, float $rate) => number_format(round($yuan * $rate, -2));
$gulfRows = [];
foreach ([['First-year package', 49950, '7,000'], ['Each later year', 35950, '5,050'], ['Six years in total', 229700, '32,000']] as [$label, $yuan, $usd]) {
    $gulfRows[] = [$label, '¥' . number_format($yuan), 'USD ' . $usd, 'AED ' . $gulfMoney($yuan, $gx['aed']), 'SAR ' . $gulfMoney($yuan, $gx['sar'])];
}
$gulfFeeTable = [
    'caption' => 'XJMU MBBS fees in yuan, US dollars, UAE dirhams and Saudi riyals (guide only, at ¥1 = AED ' . number_format($gx['aed'], 4) . ' and SAR ' . number_format($gx['sar'], 4) . ' on ' . $gx['date'] . ')',
    'head' => ['Fee', 'Chinese yuan', 'US dollars (university figure)', 'UAE dirhams', 'Saudi riyals'],
    'rows' => $gulfRows,
];

return [
    'updated' => '2026-11-25',
    'title' => 'MBBS in China for Gulf Students 2027 | UAE, Saudi, Arab',
    'description' => 'MBBS in China for Gulf students: MOE list, visa from the UAE or Saudi Arabia, fees in AED and SAR, and the licensing rules to check in each GCC country.',
    'keywords' => 'MBBS in China for Gulf students, MBBS in China for UAE students, MBBS in China for Saudi students, MBBS in China for Arab students, MBBS in China for Kuwaiti students, MBBS in China for Bahraini students, MBBS in China, study MBBS in China, MBBS in China fees in AED, MOE listed MBBS, Xinjiang Medical University',
    'eyebrow' => 'Guide for students and parents in the Gulf',
    'h1' => 'MBBS in China for Gulf Students: Admission, Visa, Fees and Licensing at Home',
    'lead' => 'A practical guide for students and parents in the UAE, Saudi Arabia, Kuwait, Oman, Qatar and Bahrain who are considering an MBBS in China: how the Ministry of Education list works, what the visa and fees involve, and which licensing rules to read in your own country before you apply. Every regulator rule is quoted with its source and the date we read it, and where we could not read a rule we say so.',
    'crumb' => 'MBBS in China for Gulf students',
    'cta' => 'Talk to admissions',
    'sections' => [

        ['id' => 'overview', 'eyebrow' => 'Overview', 'h' => 'MBBS in China for Gulf students: what to know first', 'p' => [
            "Families in the Gulf ask about an MBBS in China from different starting points. Some are nationals of the UAE, Saudi Arabia, Kuwait, Oman, Qatar or Bahrain. Many others are expatriate families who live in the Gulf and hold passports from South Asia, the Arab world or elsewhere. The questions are similar for everyone: is the programme real and recognised, what does it cost, how does the visa work, and what happens when the graduate comes back and wants to practise.",
            "This guide separates those questions into two halves. The first half is about China: the Ministry of Education (MOE) list of universities allowed to teach the English-taught MBBS to international students, admission requirements, fees, the visa and the six-year programme. The second half is about home: the licensing rules of the health regulators in each Gulf country. Those rules belong to the regulators, not to any university or consultancy, so we quote them from the regulators' own published documents and give the date we read them.",
            "Xinjiang Medical University (XJMU) in Urumqi is used as a worked example throughout, because its requirements and fees are published and we can state them exactly. It is not the only university on the MOE list, and this guide does not rank or compare universities. For any other university, use that university's own official page and the MOE list.",
            "Where a rule could not be read from an official page on 25 November 2026, this guide does not guess. It tells you to check the current rules with the regulator, and it names the regulator. A short wrong summary on a website can cost a student years, so saying less is the safer choice.",
        ], 'list' => [
            'Is the university on the MOE list for 2026-2027, and does the list say it may teach the MBBS in English to international students?',
            'Do you meet the university\'s admission requirements, including the marks in each science subject?',
            'Can the family fund six years, and how will you pay in yuan?',
            'Will your regulator accept the programme, and what must a foreign-trained graduate pass or complete to be licensed?',
            'Will the sponsor, employer or scholarship provider (if any) accept study in China? Ask them in writing.',
        ], 'img' => ['src' => 'assets/images/counselling.webp', 'alt' => 'An admissions counsellor talking with a family about MBBS study in China at a desk', 'caption' => 'Settle the questions about your home regulator and the Chinese requirements before you apply.']],

        ['id' => 'why', 'eyebrow' => 'Why China', 'h' => 'Why families in the Gulf consider China for medicine', 'p' => [
            "Families give different reasons for looking at China, and we do not publish claims about how many Gulf students go or how China compares in price or quality with other countries, because we cannot verify such figures from an official source. What we can describe are the features of the route itself, and these are the points families usually weigh.",
        ], 'list' => [
            'A defined list. China\'s Ministry of Education publishes the list of universities approved to teach the English-taught MBBS to international students. The list for 2026-2027 (notice of 1 June 2026) has 43 universities and 2,790 seats, so you can check a university against a government document instead of an advertisement.',
            'English-medium teaching. Lectures on the listed programmes are in English, with Chinese taught alongside so that students can work with patients in the clinical years.',
            'A published, fixed fee in yuan. At XJMU the six-year university fees come to about ¥229,700, which is about USD 32,000. The yuan amount is fixed by the university; only the conversion into dirhams, riyals or dinars moves with the market.',
            'A full hospital-based programme. XJMU lists 18 teaching hospitals and 95 internship hospitals, and the programme includes a one-year internship.',
            'A programme that follows the international pattern of six years. That matters later, because several Gulf regulators ask about the length of the degree and the internship.',
        ], 'sub' => [
            ['h' => 'The honest trade-offs', 'p' => [
                "The trade-offs are real. China is far from home, daily life is in Chinese, and the degree is only the middle of the journey: a graduate who wants to practise in the Gulf must meet the regulator's rules, which usually include verification of documents, an internship and an examination. Families who plan for those steps from the start tend to make calmer decisions than those who think about them after graduation.",
            ]],
        ], 'img' => ['src' => 'assets/images/lab-img.webp', 'alt' => 'A laboratory with long workbenches where people in white coats work with bottles and equipment', 'caption' => 'Basic medical sciences are taught in laboratories in the early years of the programme.']],

        ['id' => 'moe', 'eyebrow' => 'The list', 'h' => 'The Ministry of Education list of English-taught MBBS universities', 'p' => [
            "China's Ministry of Education (MOE) decides which universities may admit international students to the English-taught MBBS. The notice for 2026-2027, issued on 1 June 2026 (reference 教外司来〔2026〕94号), lists 43 universities with 2,790 seats in total. Xinjiang Medical University is number 8 on the list, with 60 seats.",
            "The list matters for a practical reason. Under the MOE notice, a university that is not on the list may admit international students only to a Chinese-medium clinical medicine programme and may not issue an English-language MBBS certificate. If an agent offers you a university that is not on the list, ask for the MOE notice and the university's own written statement of what it will award.",
            "The MOE list is one check, and it is not the same as recognition in your own country. A university can be on the MOE list and your regulator can still have its own conditions, as the sections on licensing below show. The two questions must be answered separately.",
        ], 'link' => ['moe-listed-mbbs-universities-in-china', 'See the full MOE list of 43 universities'], 'img' => ['src' => 'assets/images/campus-img.webp', 'alt' => 'Campus buildings of Xinjiang Medical University in Urumqi', 'caption' => 'XJMU is number 8 on the MOE list for 2026-2027, with 60 seats.']],

        ['id' => 'eligibility', 'eyebrow' => 'Admission', 'h' => 'Eligibility: what the university asks for', 'p' => [
            "Each university sets its own admission requirements within the MOE framework. These are the published requirements for the MBBS at XJMU. They are the university's requirements and are separate from any rule of your home regulator.",
        ], 'list' => $s['requirements'], 'sub' => [
            ['h' => 'Marks and subjects', 'p' => [
                "XJMU asks for at least 60% in each of the science subjects, which are physics, chemistry and biology. The wording is each subject, not the aggregate. Check your marks subject by subject before you apply.",
            ]],
            ['h' => 'Gulf and expatriate-school certificates', 'p' => [
                "XJMU's published wording is a senior high-school certificate, HSSC, A-Level or equivalent, with science subjects. Students in the Gulf finish school with many different certificates, from national secondary certificates to British, American, Indian, Pakistani and international curricula. The published wording does not say how each of these maps to the 60% requirement. If your certificate is not one of those named, ask admissions to confirm in writing whether it is accepted and how your marks will be read, and keep the reply.",
            ]],
            ['h' => 'Age, health and tests', 'p' => [
                "Applicants must be 18 to 35 years old, in good health and free from communicable diseases, and hold a valid passport. XJMU does not require the CSCA test. HSK level 4, the Chinese language test, is required by graduation, not at admission.",
            ]],
        ], 'link' => ['mbbs-in-china-eligibility-2027', 'Read the full MBBS in China eligibility guide']],

        ['id' => 'fees', 'eyebrow' => 'Cost', 'h' => 'Fees in yuan, dirhams and riyals', 'p' => [
            "The fees are published in Chinese yuan. For 2027 they are the same as for 2026: a first-year package of ¥49,950, about USD 7,000, then ¥35,950 a year, about USD 5,050, which gives about ¥229,700 over six years, about USD 32,000. The first-year package includes application processing, tuition, hostel accommodation, the student visa, health insurance and medical, the JW202 visa letter and airport pickup.",
            "The table converts those yuan amounts into the two largest Gulf currencies. The dirham and the riyal are both pegged to the US dollar, so the conversion depends mainly on the yuan-to-dollar rate. The rates are a guide, taken from the sources named below the table, and will have moved by the time you pay.",
        ], 'table' => $gulfFeeTable, 'sub' => [
            ['h' => 'Where the rates come from', 'p' => [
                "The yuan-to-dollar rate is worked out from the European Central Bank euro reference rates published on " . $gx['ecbDate'] . ", the latest available on " . $gx['date'] . ": EUR 1 = USD " . number_format($gx['eurUsd'], 4) . " and EUR 1 = CNY " . number_format($gx['eurCny'], 4) . ", which gives about CNY " . number_format($gx['cnyUsd'], 4) . " to the dollar. The dirham rate is AED " . number_format($gx['aedUsd'], 4) . " to the dollar, as listed in the Central Bank of the UAE exchange rate tables (July 2026 table read on " . $gx['date'] . "). The riyal rate is SAR " . number_format($gx['sarUsd'], 2) . " to the dollar, the official rate SAMA states it is committed to maintaining. For the Kuwaiti dinar, Qatari riyal, Omani rial and Bahraini dinar, take the yuan amount and use your bank's rate on the day.",
            ]],
            ['h' => 'Why the US dollar column differs a little', 'p' => [
                "The US dollar column shows the approximate figures the university quotes. At the rate above, the yuan amounts convert to slightly more than those round numbers, so use the yuan fee as the fixed figure and treat every conversion as an estimate.",
            ]],
            ['h' => 'What to add', 'p' => [
                "Banks and transfer services add a margin of a few percent to the mid-market rate, plus transfer charges. Living costs in Urumqi are separate from the fees, because hostel accommodation is already included. Send payments only through the channel the university confirms in writing, and keep every receipt.",
            ]],
        ], 'link' => ['mbbs-in-china-fees-2027', 'See the full MBBS in China fee guide']],

        ['id' => 'visa', 'eyebrow' => 'Visa', 'h' => 'Visa and travel from the Gulf', 'p' => [
            "An MBBS lasts six years, so an international student needs the long-term study visa, the X1 visa, which is for study of more than 180 days. The X2 visa is for study of 180 days or less. The university supplies two papers for the X1 application: the admission letter and the visa application form, the JW201 or JW202. At XJMU the JW202 is issued after the Pre-Admission Letter and the seat reservation deposit.",
            "You apply at the Chinese visa application service centre that serves your country, and each centre publishes its own checklist. We could read two of them on 25 November 2026, and these are what they say. For the other Gulf countries, open the visa centre page for your country and follow that list.",
        ], 'sub' => [
            ['h' => 'Applying in the UAE', 'p' => [
                "The Chinese Visa Application Service Center in Dubai lists, for the X1 visa, the original and a copy of the admission letter of the Chinese institution, and the original and a copy of the Visa Application Form for Foreign Students to China, Form JW201 or JW202. For the X2 visa it lists the admission letter only. Its general requirements include an original passport valid for more than six months, two white-background photos taken in the past six months, a copy of the Emirates ID and, for non-UAE nationals, a copy of the UAE residence visa. The page also asks for a no-objection letter from the UAE sponsor and an online appointment. The consular officer may ask for additional documents or an interview. Read the page itself before you book, because the centre updates it.",
            ]],
            ['h' => 'Applying in Saudi Arabia', 'p' => [
                "The Riyadh visa centre page, dated 24 August 2026, lists for the X1 visa the school's admission notice and Form JW201 or JW202. The list on that page also includes a bank statement for the last three months with a balance above 10,000 SAR. The page notes that holders of an X1 visa must apply for a residence permit after entering China. Check the current list at the visa centre before you apply, since requirements such as the bank statement can change.",
            ]],
            ['h' => 'After you arrive', 'p' => [
                "The X1 visa lets you enter China to study. It is not permission to stay for six years. The Riyadh page and the visa guide on this site say the holder must apply for a residence permit with the local police immigration office within 30 days of entry, and XJMU lists ¥800 a year for the residence permit and ¥350 for the physical examination from year two. No university or agent can promise that a visa will be issued, because the consular officer decides.",
            ]],
            ['h' => 'Flights', 'p' => [
                "Flight routes between the Gulf and Urumqi change from year to year and often connect through other cities, so we do not publish schedules. Ask a travel agent for current options and book early, because prices rise close to the intake dates.",
            ]],
        ], 'link' => ['china-student-visa-for-mbbs', 'Read the full China student visa guide'], 'img' => ['src' => 'assets/images/visa-process.webp', 'alt' => 'A five-step graphic of the student visa process, from finalising the university to receiving the visa and preparing to depart', 'caption' => 'The X1 visa application is built on the two papers the university supplies.']],

        ['id' => 'study', 'eyebrow' => 'The programme', 'h' => 'The six years and the language', 'p' => [
            "The XJMU MBBS lasts six years, with five years of study and a one-year internship. The early years cover the basic medical sciences. Teaching then moves to clinical subjects, and the clinical years take place in teaching hospitals. XJMU lists 18 teaching hospitals and 95 internship hospitals.",
            "Lectures are in English. Daily life and, in the clinical years, much of the contact with patients are in Chinese. Introductory Chinese is taught in years 1 and 2, and XJMU requires HSK level 4 by graduation. Students who postpone Chinese find the clinical years harder, so most advice from seniors is to begin in the first semester.",
        ], 'sub' => [
            ['h' => 'Why the internship matters for licensing', 'p' => [
                "Several Gulf regulators ask about an internship, and the way each one words it differs. The UAE rules quoted below say a graduate needs one year of internship after graduation, and that no internship after graduation is needed where the internship is part of the undergraduate programme. Bahrain's physician standards also ask for a year of internship. Qatar's guidelines count the internship towards a six-year degree. Whether the XJMU final year satisfies the rule of your regulator is a question to put to that regulator in writing, with the programme description from the university attached.",
            ]],
            ['h' => 'Food and daily life', 'p' => [
                "Hostel accommodation is included in the fees. Halal food is widely available in Urumqi, and many students cook in groups. Winters are cold and summers are hot and dry, so ask seniors what to bring.",
            ]],
        ], 'img' => ['src' => 'assets/images/affil-hospt.webp', 'alt' => 'An affiliated hospital building used for clinical teaching', 'caption' => 'The clinical years and the internship take place in hospitals.']],

        ['id' => 'licensing', 'eyebrow' => 'Back home', 'h' => 'Licensing at home: the pattern across the Gulf', 'p' => [
            "Every Gulf country has its own health regulator and its own rules, and the rules are written for all foreign-trained doctors, not only for graduates of Chinese universities. Reading them side by side shows the same five questions, and it is worth writing them down for the country you plan to work in.",
        ], 'list' => [
            'Is the medical school recognised or accredited in its own country, and does the regulator use a list or directory to check it?',
            'Has the graduate completed an internship, and does the regulator count the internship inside the degree?',
            'Is the degree verified directly with the university, through an approved verification service or the regulator?',
            'Must the graduate pass a licensing examination, and are some people exempt?',
            'What experience or other registration is needed before the licence is issued?',
        ], 'sub' => [
            ['h' => 'What we do and do not say', 'p' => [
                "Below, each country's rules are quoted as published by its regulator on 25 November 2026, with the document we read. Some documents are several years old, and the regulators revise them, so treat our summary as a map to the regulator's page and not as a replacement for it. We never say that a regulator recognises or approves XJMU or any other Chinese university, because we have not found a regulator page that says so. Ask the regulator, and ask for the reply in writing.",
            ]],
            ['h' => 'If you are a national or an expatriate', 'p' => [
                "Some rules differ for nationals and for non-nationals, for example in exam exemptions or experience. Read the part that applies to your passport and your residency, and ask the regulator which applies to you.",
            ]],
        ]],

        ['id' => 'uae', 'eyebrow' => 'UAE', 'h' => 'United Arab Emirates: the Unified Healthcare Professional Qualification Requirements', 'p' => [
            "Health licensing in the UAE is run by the Ministry of Health and Prevention (MOHAP), the Dubai Health Authority (DHA) and the Department of Health Abu Dhabi (DOH), working to a unified document called the Professional Qualification Requirements (PQR). The version we read, the third version of 2022, is published on the DHA website, and the DHA's Manual for Licensing Healthcare Professionals (version 1.2, effective 8 May 2025) says it must be read together with the PQR and its updates.",
            "As published by the DHA on 25 November 2026, the PQR sets these conditions for physicians. Qualifications must be issued by institutions recognised by the authorities that recognise or accredit educational programmes in the country concerned. Physicians must be graduates of formally recognised allopathic medical schools, accredited by the issuing authority of that country. All physicians must complete a one-year internship after graduation, and for programmes where the internship is part of the undergraduate training no internship after graduation is required. Applicants must hold a valid licence or registration in their home country or the country of last employment, where applicable.",
        ], 'sub' => [
            ['h' => 'Verification and examination', 'p' => [
                "The PQR says the documents required for licensing are verified directly from the original or primary source. It says healthcare professionals must pass the authority's examination to obtain a licence in the UAE, with UAE nationals and children of Emirati women exempted from the authority examinations, and that applicants have three attempts across the authorities. Certain international examinations or registrations can exempt a person from the licensing examination under an exam-equivalency chapter of the PQR, but not from the other requirements such as verification and experience. Experience requirements for each title are set out in the PQR, so read the table for the title you want.",
            ]],
            ['h' => 'The DHA licensing steps', 'p' => [
                "The DHA manual describes the steps as creating a Sheryan account and a self-assessment, fulfilling the PQR requirements, obtaining a positive primary source verification result, passing the assessment, which is either a computer-based test or an oral assessment depending on the post, and receiving the registration certificate. It says documents must be submitted as originals with a certified translation if they are not in English or Arabic, and that for certain qualifications the DHA may require an equivalency certificate from the UAE Ministry of Education.",
            ]],
            ['h' => 'What to ask', 'p' => [
                "Ask the authority you expect to apply to whether the XJMU programme, including its internship year, meets the PQR internship rule, and whether an equivalency certificate from the Ministry of Education is required for a degree earned in China. Both questions are about how the rules apply to your degree, so the answer should come from the authority and be in writing. We do not state that any UAE body has approved or recognised XJMU.",
            ]],
        ]],

        ['id' => 'saudi', 'eyebrow' => 'Saudi Arabia', 'h' => 'Saudi Arabia: the Saudi Commission for Health Specialties', 'p' => [
            "In Saudi Arabia the Saudi Commission for Health Specialties (SCFHS) classifies and registers health practitioners. As published by the SCFHS on 25 November 2026, its professional classification requirements say that foreign-issued documents need a verification result from a verification company approved by the SCFHS, and that if the application rests on a bachelor's degree, that certificate must be authenticated. Academic records must show the programme details, the duration and any practical or internship component.",
            "The same page says non-Saudis need at least one year of experience, with graduates of Saudi universities excepted, and that the registration certificate must come from the official authority of the country where the applicant practised before arriving in the Kingdom. For Saudis who hold a foreign academic qualification, the page asks for an equalization certificate from the Ministry of Education.",
        ], 'sub' => [
            ['h' => 'The Saudi Medical Licensure Examination', 'p' => [
                "The SCFHS applicant guide for the Saudi Medical Licensure Examination (SMLE), the 2022 edition that we read on 25 November 2026, says graduates must take the SMLE if they want to practise in Saudi Arabia or enter an SCFHS postgraduate training programme. To apply, a candidate needs a recognised primary degree, MBBS or equivalent, from an accredited health science programme, or must be in the internship year or one year from graduation. Eligible candidates may sit the SMLE up to four times a year. The SCFHS has published newer SMLE material since, so check its current guide.",
            ]],
            ['h' => 'Scholarships and sponsors', 'p' => [
                "If a government body, an employer or a scholarship programme is funding your study, ask that body in writing whether study at a Chinese university is acceptable and under what conditions. We have not found an official page we can quote on this, so we state no rule.",
            ]],
        ]],

        ['id' => 'bahrain-qatar', 'eyebrow' => 'Bahrain and Qatar', 'h' => 'Bahrain and Qatar: directories, internship and examination', 'p' => [
            "Bahrain and Qatar both name an international directory of medical schools in their physician rules. That directory is now the World Directory of Medical Schools, which merged the older WHO Avicenna directory and the FAIMER IMED directory. Our guide to the WHO and WDOMS listing explains how to search it. Both documents below are physician guidelines that the regulators published some time ago, so confirm them against the regulators' current notices.",
        ], 'sub' => [
            ['h' => 'Bahrain: National Health Regulatory Authority (NHRA)', 'p' => [
                "The NHRA's Healthcare Professional Licensing Standards for Physicians (version 1, 17 March 2017), which we read on 25 November 2026, say physicians must be graduates of an allopathic medical school listed in the WHO Avicenna Directory or the FAIMER IMED directory and recognised by the Bahrain Ministry of Education or Higher Education Council. They must complete a one-year internship after graduation, and non-Bahraini applicants must hold a valid licence from their home country or country of last employment, where applicable.",
                "The standards say some physicians must pass the NHRA's Bahrain Licensing Examination, and list exemptions under a 2015 decision, including applicants with a master's degree or higher specialisation, Bahraini or GCC nationals who hold a valid GCC licence, and applicants with ten years of clinical experience after the internship. Graduates of non-Bahraini universities must meet the experience requirement for the title they apply for.",
            ]],
            ['h' => 'Qatar: Department of Healthcare Professions (DHP)', 'p' => [
                "The DHP Guidelines for Physicians, read on 25 November 2026, list for the General Practitioner scope a bachelor's degree in medicine from a university listed in the IMED FAIMER Directory, and a qualifying examination unless the applicant is exempt, with five attempts allowed. They say Qataris and children of Qatari women are exempt from that examination, and that overseas general practitioners must pass it after at least five years of experience. They also set a minimum of a six-year undergraduate degree, and say an internship can count towards the required years where the college programme is shorter.",
                "On documents, the guidelines say the verification process carried out by the approved verification companies replaces attestation of certificates by competent authorities, so certificates do not have to be attested. The DHP names three approved providers: DataFlow, Quadrabay and Approved Verification Services. The DHP says practitioners must complete verification before submitting the online evaluation application.",
            ]],
        ]],

        ['id' => 'kuwait-oman', 'eyebrow' => 'Kuwait and Oman', 'h' => 'Kuwait and Oman: what we could and could not read', 'p' => [
            "Kuwait. On 25 November 2026 we could not open the Kuwaiti Ministry of Health's licensing pages or the government e-services pages, because the sites refused an automated read. We therefore state no Kuwaiti rule. Ask the Ministry of Health's Medical Licensing Department what it requires from a foreign-trained graduate, how a degree from China is verified, and whether any licensing examination applies to you, and ask for the reply in writing.",
            "Oman. The Ministry of Health's Directorate General of Private Health Establishments published a procedure for licensing doctors, effective December 2021 with a review date of December 2025. It describes a Ministry viva examination, which can be taken in person or online, and says the documents include the degree, the internship certificate, work experience, a letter from the employer and a primary source verification report. That document covers doctors appointed by private health establishments and was due for review, so it does not tell a new graduate what applies today. Ask the Ministry of Health, and the Oman Medical Specialty Board if you plan postgraduate training, what applies to you.",
        ], 'sub' => [
            ['h' => 'Not a gap in your plan, only a gap in our page', 'p' => [
                "Not being able to quote a rule does not mean there is no rule. It means you should ask the regulator before you pay any fee. A short email with your degree, the university, the programme length and the internship, sent to the regulator's published licensing address, is a reasonable first step.",
            ]],
        ]],

        ['id' => 'apply', 'eyebrow' => 'How to apply', 'h' => 'How to apply to XJMU from the Gulf: a worked example', 'p' => [
            "The application is the same for all international applicants, wherever they live. This is the sequence at XJMU. For any other university, follow its own published process.",
        ], 'list' => [
            'Check that you meet the XJMU requirements, and check your home regulator\'s conditions.',
            'Choose your intake, and plan to apply by the early deadline.',
            'Prepare your documents, record your self-introduction video and complete the medical report.',
            'Submit the application and pay the ¥1,000 application fee as instructed.',
            'After the Pre-Admission Letter, pay the ¥2,000 seat reservation deposit, which is refunded if the JW202 visa letter is rejected.',
            'Apply for your X1 student visa with the JW202 letter and the admission letter, and book your flight.',
        ], 'table' => $s['calendar'], 'sub' => [
            ['h' => 'Two intakes', 'p' => [
                "XJMU has a March/Spring intake and a September/Fall intake. For the September/Fall intake, applications open on 1 March with an early deadline of 30 June. For the March/Spring intake, applications open on 1 September with an early deadline of 15 December. Dates may vary by programme, so confirm them when you apply.",
            ]],
        ], 'link' => ['mbbs-in-china-admission-calendar-2027', 'See the full admission calendar']],

        ['id' => 'documents', 'eyebrow' => 'Documents', 'h' => 'Documents, and the question of attestation', 'p' => [
            "These are the documents XJMU asks for at the application stage. Prepare them early, and ask admissions for the current list before you start, because the Chinese requirements for certificates from outside China are set by the university and the Chinese authorities.",
        ], 'list' => $s['documents'], 'sub' => [
            ['h' => 'Attestation of certificates', 'p' => [
                "Gulf families often ask whether certificates must be attested by the education ministry, the foreign ministry or a Chinese embassy before applying. We could not find an official Chinese page that states a single rule for applicants from the Gulf, so we state none here. Ask XJMU admissions what it needs for your certificates and in what form, and keep the answer in writing. The licensing rules at home are a separate matter, as the sections above show: Qatar's DHP, for example, says its verification companies replace attestation, and the DHA expects originals with certified translations.",
            ]],
            ['h' => 'A police certificate and your residence', 'p' => [
                "The police non-criminal record certificate is on the list. An expatriate family may need the certificate from the country of residence, the country of nationality or both, so ask admissions which one is wanted before you spend time on it.",
            ]],
        ]],

        ['id' => 'mistakes', 'eyebrow' => 'Avoid', 'h' => 'Mistakes families in the Gulf make when choosing China', 'p' => [
            "These are patterns that cause trouble, and each one is avoidable.",
        ], 'sub' => [
            ['h' => 'Assuming MOE listing means recognition at home', 'p' => [
                "The MOE list is China's list. Your own regulator has its own conditions, and they should be read and asked about before the first payment.",
            ]],
            ['h' => 'Relying on one source', 'p' => [
                "A single agent, a video or a forum post is not enough. Check the MOE list, the regulator's own pages and the university's written replies. A rule quoted second-hand may have changed.",
            ]],
            ['h' => 'Comparing only the fee', 'p' => [
                "The cheapest quote is not the best one. Compare what each fee includes, whether the university is on the MOE list, and what the programme and hospitals offer.",
            ]],
            ['h' => 'Leaving the language until later', 'p' => [
                "Chinese is needed in the clinical years. Begin in the first semester, and plan for HSK level 4.",
            ]],
            ['h' => 'Ignoring the return', 'p' => [
                "The six years in China are only part of the path. Plan for the verification, the internship rule, the examination and the experience requirements in the country where you intend to work.",
            ]],
        ]],

        ['id' => 'scams', 'eyebrow' => 'Safety', 'h' => 'Money and scams: how to protect your family', 'p' => [
            "Because the fees are in yuan and the family is far away, payment is where most problems start. A few habits protect you.",
        ], 'list' => [
            'Check that the university is on the MOE list and that the offer letter comes from the university.',
            'Pay only to the university or the payment channel it confirms in writing, never to a personal account.',
            'Keep every receipt, and ask for the fee breakdown in writing.',
            'Do not pay for a guaranteed scholarship or a guaranteed visa, because no one can guarantee either.',
            'Ask the regulator, not the agent, whether the programme meets the licensing rules, and keep the reply.',
        ], 'sub' => [
            ['h' => 'Speak to a family who has done it', 'p' => [
                "Ask admissions whether it can put you in touch with a family whose child already studies at XJMU. Ten minutes with another parent often answers more than a day of reading, and it costs nothing.",
            ]],
        ], 'link' => ['non-moe-universities-in-china-mbbs-risks', 'Read about the risks of universities that are not on the MOE list'], 'video' => $s['video']],
    ],
    'faqs' => [
        ['Can Gulf students study MBBS in China?', 'Yes. International students, including those living in the Gulf, can apply to universities on the Ministry of Education list that teach the English-taught MBBS. Each university sets its own requirements, and your own health regulator sets the rules for licensing at home.'],
        ['Is MBBS in China valid in the UAE?', 'The UAE licensing authorities publish Professional Qualification Requirements that cover the school, internship, verification and examination. Read them on the Ministry of Health and Prevention, Dubai Health Authority or Department of Health Abu Dhabi site and ask the authority in writing how they apply to your degree.'],
        ['Is MBBS in China accepted in Saudi Arabia?', 'The Saudi Commission for Health Specialties sets the classification, registration and Saudi Medical Licensure Examination rules. Its applicant guide says graduates must take the SMLE to practise in the Kingdom. Check the current rules with the commission before you apply.'],
        ['What about Kuwaiti, Omani, Qatari and Bahraini students?', 'Each country has its own regulator. We quote the published Bahrain and Qatar physician rules above, and for Kuwait and Oman we could not read a current rule, so please check with the Ministry of Health of your country.'],
        ['Is MBBS in China for Arab students taught in English?', 'On the Ministry of Education list, the MBBS for international students is taught in English. At XJMU introductory Chinese is taught in years 1 and 2, and HSK level 4 is required by graduation.'],
        ['What are the MBBS in China fees in dirhams and riyals?', 'The XJMU first-year package is ¥49,950 and each later year ¥35,950, about ¥229,700 over six years. At the rates on 25 November 2026 that is about AED 27,500 for the first year and AED 126,600 over six years, and about SAR 28,100 and SAR 129,200. Banks add a few percent.'],
        ['What marks do I need at XJMU?', 'At least 60% in each of physics, chemistry and biology, shown on a senior high-school certificate such as HSSC, A-Level or equivalent, with an age between 18 and 35. Ask admissions how your Gulf or expatriate-school certificate will be read.'],
        ['Which visa do Gulf students need for MBBS in China?', 'The X1 visa, which is for study of more than 180 days. The Dubai and Riyadh Chinese visa centres list the admission letter and the JW201 or JW202 form among the documents. Check your own centre\'s current list.'],
        ['Is XJMU on the Ministry of Education list?', 'Yes. Xinjiang Medical University is number 8 on the 2026-2027 MOE list, with 60 seats. The list has 43 universities and 2,790 seats and was issued on 1 June 2026.'],
        ['Does the MBBS include an internship?', 'Yes. The XJMU programme lasts six years, which includes a one-year internship. Several Gulf regulators ask about an internship, so ask yours whether it accepts the internship inside the degree.'],
        ['When are the intakes?', 'There is a March/Spring intake and a September/Fall intake. Applications for the September/Fall intake open on 1 March, with an early deadline of 30 June. For the March/Spring intake they open on 1 September, with an early deadline of 15 December.'],
        ['How do I avoid scams?', 'Check the university on the MOE list, pay only to the university or its confirmed channel, keep receipts and never pay for a guaranteed scholarship or visa.'],
    ],
    'related' => ['mbbs-in-china-2027', 'mbbs-in-china-fees-2027', 'mbbs-in-china-eligibility-2027', 'china-student-visa-for-mbbs', 'moe-listed-mbbs-universities-in-china', 'who-listed-medical-universities-in-china', 'non-moe-universities-in-china-mbbs-risks'],
];
