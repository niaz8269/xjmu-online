<?php

$s = require __DIR__ . '/_shared.php';

// Exchange rates: Bank of Canada daily rates for 24 November 2026 (the latest published when this page was written, 25 November 2026).
// 1 CNY = 0.2145 CAD. The US dollar column keeps the university's own rounded USD figures, the same on every page of this site. Update the rate and the date together.
$ca = ['date' => '24 November 2026', 'cad' => 0.2145];
$round = fn (float $v) => number_format(round($v, -2));
$feeNorthAmerica = [];
foreach ([['First-year package', 49950, '7,000'], ['Each later year', 35950, '5,050'], ['Six years in total', 229700, '32,000']] as [$label, $yuan, $usd]) {
    $feeNorthAmerica[] = [$label, '¥' . number_format($yuan), 'USD ' . $usd, 'CAD ' . $round($yuan * $ca['cad'])];
}

return [
    'updated' => '2026-11-25',
    'title' => 'MBBS in China for US & Canadian Students: USMLE, ECFMG',
    'description' => 'MBBS in China for US and Canadian students: can you practise at home? ECFMG, USMLE, residency, MCC and CaRMS rules read from official pages, plus fees in USD and CAD.',
    'keywords' => 'MBBS in China for US students, MBBS in China for American students, MBBS in China for Canadian students, MBBS in China USMLE, ECFMG China MBBS, MBBS in China, can I practise in the US after MBBS in China, MBBS in China CaRMS, MBBS in China fees in USD, Xinjiang Medical University',
    'eyebrow' => 'Guide for students and parents in the US and Canada',
    'h1' => 'MBBS in China for US and Canadian Students: USMLE, ECFMG, Residency and Fees',
    'lead' => 'The question most American and Canadian families really ask is not whether you can study medicine in China, but whether you can become a licensed doctor at home afterwards. This guide takes the Chinese side first (the Ministry of Education list, entry requirements, visa and cost) and then the North American side, rule by rule, with each rule taken from the official page of the body that sets it and the date we read it.',
    'crumb' => 'MBBS in China for US and Canadian students',
    'cta' => 'Talk to admissions',
    'sections' => [

        ['id' => 'overview', 'eyebrow' => 'Overview', 'h' => 'MBBS in China for US and Canadian students: the honest picture', 'p' => [
            "Students in the United States and Canada, including citizens whose families come from Asia, Africa or the Caribbean, look at an MBBS in China for practical reasons: a six-year course taught in English, a published fee in yuan that can be planned for, and a degree that leads to the same kind of licensing route that any graduate of a medical school outside the United States or Canada follows.",
            "Be clear about one thing before you read further. A medical degree from China does not by itself allow you to practise in the United States or Canada. In the United States, a graduate of a school outside the country goes through ECFMG Certification, the United States Medical Licensing Examination (USMLE), a residency programme and a state licence. In Canada, the route runs through the Medical Council of Canada examinations, the CaRMS residency match and a provincial or territorial licence. Every one of those steps is set by a North American body, not by the Chinese university and not by us.",
            "That is why this page is organised the way it is. We state the Chinese facts we can stand behind, and for each North American rule we name the official body, say what its page says, and give the date we read it. Where we could not open an official page, we do not state the rule. We write \"check the current rules with\" that body instead. Rules change, so treat every statement here as a starting point for your own check, not as advice that replaces the official page.",
            "Nothing in this guide is a promise. No university, agent or website can guarantee that you will pass an examination, match to a residency or be granted a licence. What a good decision needs is accurate information about each step, and that is what we set out below.",
        ], 'img' => ['src' => 'assets/images/counselling.webp', 'alt' => 'A group of people gathered around a table reading admission leaflets and forms', 'caption' => 'Read the rules of the bodies that license doctors at home before you apply abroad.']],

        ['id' => 'short-answer', 'eyebrow' => 'The question', 'h' => 'Can I practise in the US or Canada after an MBBS from China?', 'p' => [
            "The short answer is that it depends on your school, your graduation year and on examinations you must pass yourself. It is not a yes or no decided on the day you enrol. The table sets out the route in the order you meet it, and the sections that follow give the detail and the source for each step.",
        ], 'table' => ['caption' => 'The route home after an MBBS from China, step by step', 'head' => ['Step', 'United States', 'Canada'], 'rows' => [
            ['1. Your medical school', 'Must be listed in the World Directory of Medical Schools with an ECFMG Sponsor Note that includes your graduation year', 'Must be a school listed in the World Directory that CaRMS describes as recognised in Canada; ask the college that licenses doctors in your province what else it requires'],
            ['2. Examinations', 'USMLE Step 1 and Step 2 CK, plus a clinical skills pathway, for ECFMG Certification; Step 3 later', 'MCCQE and the NAC Examination for the CaRMS R-1 Match'],
            ['3. Residency', 'Apply through ERAS and the NRMP Match; ACGME requires ECFMG Certification for IMGs entering ACGME-accredited programmes', 'Apply through CaRMS; eligibility is limited to Canadian citizens and permanent residents, with some exceptions'],
            ['4. Licence', 'Granted by a state; ECFMG says state authorities require ECFMG Certification, among other requirements, for an unrestricted licence', 'Granted by the provincial or territorial medical regulatory authority, not by the MCC'],
        ]], 'note' => 'As published by ECFMG (Intealth), the USMLE programme, the NRMP, the Medical Council of Canada and CaRMS, read on 25 November 2026. Each is explained, with its source, in the sections below.', 'sub' => [
            ['h' => 'Who counts as an international medical graduate', 'p' => [
                "ECFMG says that what makes someone an international medical graduate (IMG) is where the medical degree was earned, not citizenship. In its words, U.S. citizens who graduated from medical schools outside the United States are considered IMGs. So an American who graduates in China follows the IMG route, with the same requirements as any other IMG.",
            ]],
        ]],

        ['id' => 'moe-list', 'eyebrow' => 'The Chinese side', 'h' => 'The Ministry of Education list of English-taught MBBS universities', 'p' => [
            "In China the Ministry of Education (MOE) decides which universities may enrol international students on an English-taught MBBS. The list in force for 2026–2027 comes from MOE notice 教外司来〔2026〕94号, issued on 1 June 2026. It names 43 universities with 2,790 seats between them. Xinjiang Medical University (XJMU) is number 8 on that list, with 60 seats.",
            "Why does a student in Texas or Ontario need to know about a Chinese ministry list? Because it tells you whether the programme itself is one the Chinese government approves for international students, and because your North American steps depend on a real, documented programme. The MOE list does not replace the North American checks that follow, and the North American checks do not replace the MOE list. You need both.",
            "The full list, with seats for every university, is on our page about MOE listed universities. We do not compare or rank universities on this site, and we do not quote another university's fees or seats from memory. If you are comparing schools, take each figure from the MOE notice or from that university's own official page, and ask for the answers in writing.",
        ], 'table' => ['caption' => 'MOE list for 2026–2027: the facts this guide relies on', 'head' => ['Item', 'Detail'], 'rows' => [
            ['Issuing body', "Ministry of Education of the People's Republic of China"],
            ['Notice', '教外司来〔2026〕94号, issued 1 June 2026'],
            ['Universities on the list', '43'],
            ['Total seats', '2,790'],
            ['Xinjiang Medical University', 'Number 8, 60 seats'],
        ]], 'img' => ['src' => 'assets/images/campus-img.webp', 'alt' => 'Xinjiang Medical University campus in Urumqi, number 8 on the MOE list for English-taught MBBS', 'caption' => 'XJMU campus in Urumqi, used in this guide as a worked example.'], 'link' => ['moe-listed-mbbs-universities-in-china', 'See all 43 MOE listed universities']],

        ['id' => 'eligibility', 'eyebrow' => 'Admission', 'h' => 'Entry requirements: what the university asks of you', 'p' => [
            "Each university publishes its own entry requirements, so there is no single list that applies to all of China. As a worked example, here are the published requirements of Xinjiang Medical University for the MBBS. They are the university's requirements and are separate from anything a North American licensing body asks of you.",
        ], 'list' => $s['requirements'], 'sub' => [
            ['h' => 'Marks and subjects', 'p' => [
                "XJMU requires a senior high-school certificate (HSSC, A-Level or equivalent) with science subjects, and at least 60% in each of physics, chemistry and biology. The requirement is per subject, so a good overall average does not cover a weak subject.",
                "US high schools and Canadian provinces grade and name their courses in different ways. We do not translate a transcript into a percentage on this page. Send admissions your transcript and ask them to confirm in writing how your diploma and science courses are assessed against the 60% requirement before you pay anything.",
            ]],
            ['h' => 'Age', 'p' => [
                "Applicants must be between 18 and 35 years old.",
            ]],
            ['h' => 'Entrance tests', 'p' => [
                "XJMU does not require the CSCA test for admission to its MBBS. We say nothing here about the entrance rules of other universities. Whether your own licensing route asks for any test before or after the degree is a separate question, answered in the North American sections below.",
            ]],
        ], 'link' => ['mbbs-in-china-eligibility-2027', 'Read the full MBBS in China eligibility guide']],

        ['id' => 'fees', 'eyebrow' => 'Cost', 'h' => 'MBBS in China fees in US and Canadian dollars', 'p' => [
            "Fees in China are published in yuan, and that figure is the one that is fixed. XJMU's fees for 2027 are the same as for 2026. The first-year package is ¥49,950 and each later year is ¥35,950, which is about ¥229,700 over six years. The table converts those amounts into US and Canadian dollars so you can compare them with tuition at home.",
        ], 'table' => [
            'caption' => 'XJMU MBBS fees in yuan, US dollars and Canadian dollars (US dollars as published by the university; Canadian dollars at ¥1 = CAD ' . number_format($ca['cad'], 4) . ', Bank of Canada daily rate for ' . $ca['date'] . ', read on 25 November 2026; rounded to the nearest 100)',
            'head' => ['Fee', 'Chinese yuan', 'US dollars', 'Canadian dollars'],
            'rows' => $feeNorthAmerica,
        ], 'sub' => [
            ['h' => 'How to read the table', 'p' => [
                "The conversion is a guide, not a quote. The exchange rate moves every day, and banks and transfer services charge a margin above the mid-market rate, so budget a few percent more than the table shows. The US dollar figures are the university's own rounded amounts, the same as on every other page of this site, and the Canadian dollar figures are calculated from the Bank of Canada rate stated in the caption. Because the university's dollar figures are rounded, and the exchange rate moves, your bank may show a different dollar total on the day you pay. The yuan amount is the fixed fee.",
            ]],
            ['h' => 'What the first-year package covers', 'p' => [
                "The first-year package of ¥49,950 covers application processing, registration and admission charges, the tuition fee, hostel accommodation, the student visa, health insurance and medical, the JW202 visa letter and airport pickup. From the second year the ¥35,950 is made up of tuition (¥30,000), accommodation (¥4,500), insurance (¥300), residence permit (¥800) and physical examination (¥350).",
            ]],
            ['h' => 'What the table leaves out', 'p' => [
                "Flights between North America and Urumqi, food and daily living costs, and the HSK examination fee are extra. We do not publish flight prices because they change too quickly to be reliable. Add them, plus the cost of the examinations you will take on the way home, which are set by the examining bodies and are published on their own sites. We have not copied those fees here.",
            ]],
        ], 'link' => ['mbbs-in-china-fees-2027', 'See the full fee guide']],

        ['id' => 'us-ecfmg', 'eyebrow' => 'United States', 'h' => 'The US route: ECFMG Certification', 'p' => [
            "ECFMG, the Educational Commission for Foreign Medical Graduates, now part of Intealth, is the body that evaluates the qualifications of international medical graduates who enter the US health care system. Its Certification overview page, which we read on 25 November 2026, gives three reasons it matters. The ACGME requires IMGs who enter ACGME-accredited residency or fellowship programmes to be certified by ECFMG. ECFMG Certification is one of the eligibility requirements for IMGs to take Step 3 of the USMLE. And medical licensing authorities in the United States require IMGs to be certified by ECFMG, among other requirements, to obtain an unrestricted licence.",
            "In practice, ECFMG Certification is the doorway to residency and to Step 3, as the three reasons above show. ECFMG sets out what it requires in four parts, and we summarise each below as published on that page.",
        ], 'table' => ['caption' => 'ECFMG Certification requirements, as published by ECFMG (Intealth), read on 25 November 2026', 'head' => ['Requirement', 'What ECFMG says'], 'rows' => [
            ['Medical school', 'The school must meet ECFMG requirements and be listed in the World Directory of Medical Schools with an ECFMG Sponsor Note, which also states the graduation years covered. With no Sponsor Note, students and graduates are not eligible'],
            ['Credit years', 'At least four credit years awarded by a school listed with an ECFMG Sponsor Note; there are restrictions on transferred credits'],
            ['Medical science examinations', 'Pass USMLE Step 1 and Step 2 Clinical Knowledge (CK); IMGs apply for the USMLE through the Federation of State Medical Boards (FSMB)'],
            ['Clinical and communication skills', 'Satisfy an ECFMG Pathway, which includes a satisfactory Occupational English Test (OET) Medicine score, or hold a passing performance on the former Step 2 CS'],
            ['Diploma and transcript', 'ECFMG verifies the medical diploma with the issuing school and requests the final transcript'],
        ]], 'sub' => [
            ['h' => 'Your graduation year matters', 'p' => [
                "ECFMG says that the individual's graduation year must be included in the ECFMG Sponsor Note in the school's World Directory listing. This is the single most important check for a student who is about to enrol. The Sponsor Note is not a permanent label on the school. It names the graduation years for which the school meets ECFMG's requirements. Look at the years it lists and compare them with the year you would graduate.",
            ]],
            ['h' => 'Pathways expire', 'p' => [
                "ECFMG says the clinical skills Pathways are offered on a seasonal application cycle and an accepted Pathway is subject to expiration. An accepted, unexpired Pathway is required to become ECFMG Certified, and also to take part in the NRMP Match. Read ECFMG's own pages on the Pathways before you plan the timing of your final year.",
            ]],
            ['h' => 'Time limits and other rules', 'p' => [
                "ECFMG says it has set time limits and other rules for completing the examination requirements. We do not copy them here. They are in the ECFMG Information Booklet, and they are the kind of rule that is updated from time to time.",
            ]],
        ]],

        ['id' => 'us-accreditation', 'eyebrow' => 'United States', 'h' => 'ECFMG and medical school accreditation: what the policy says', 'p' => [
            "Many websites say that students must graduate from an accredited medical school to be eligible for ECFMG Certification, and some give years such as 2023 or 2024. The rule has changed shape over time, so go to ECFMG's own words. On its Recognized Accreditation Policy page (read on 25 November 2026), Intealth states that the policy does not affect an individual's eligibility for ECFMG Certification, and that IMGs can continue to apply for and pursue ECFMG Certification even if their medical school currently does not meet the requirements of the policy, as long as the school meets ECFMG's current requirements.",
            "The same page explains what the policy does do. A school that is accredited by an agency recognised by an organisation that Intealth has approved meets the policy, and Intealth has so far approved two such organisations, the World Federation for Medical Education (WFME) and the National Committee on Foreign Medical Education and Accreditation (NCFMEA). If a school currently meets the policy, a notation saying so is included on the Sponsor Notes tab of its World Directory listing. Intealth describes that notation as an additional data point for U.S. programme directors on the educational background of IMGs applying to U.S. graduate medical education.",
            "Notice what the page does not say. It does not say that a school with the notation is automatically eligible, because it also says that a school must hold an ECFMG Sponsor Note for its students and graduates to be eligible to apply. And it says that the policy may continue to evolve at Intealth's sole discretion. So the accurate advice is short. Open the World Directory, find the school, read the Sponsor Notes tab, and write down exactly what it says about the ECFMG Sponsor Note, the graduation years and the policy notation. Do this again before you enrol and again in your final year, and do not rely on anyone's summary, including ours.",
        ], 'link' => ['who-listed-medical-universities-in-china', 'How the World Directory (WDOMS) works']],

        ['id' => 'us-usmle', 'eyebrow' => 'United States', 'h' => 'USMLE: Step 1, Step 2 CK and Step 3 for IMGs', 'p' => [
            "The USMLE is the examination sequence for US medical licensure. For an international medical graduate the rules on the USMLE website (read on 25 November 2026, page undated, footer 1996–2026) are these.",
        ], 'list' => [
            'Step 1 and Step 2 CK: you must be enrolled in, or a graduate of, a school outside the United States that is listed in the World Directory of Medical Schools as meeting ECFMG eligibility requirements, and meet other ECFMG criteria. You must be eligible when you apply and on the day of the examination.',
            'Students can sit these steps: officially enrolled students at eligible schools may take Step 1 and Step 2 CK. If you are dismissed or withdraw from medical school you are not eligible, even while an appeal is pending.',
            'Step 3: you need passing scores on Step 1 and Step 2 CK, the equivalent of an MD from a school listed in the World Directory, and a valid, unexpired ECFMG Certificate.',
            'Attempt limits: if you have attempted a Step four or more times, including incomplete attempts, without passing, you cannot apply for any Step. You may not take the same Step more than three times in 12 months.',
            'Time limits: many US state medical boards require all Steps to be completed within a set period; the USMLE page directs you to the FSMB website for state requirements.',
        ], 'sub' => [
            ['h' => 'Recommended training before Step 3', 'p' => [
                "The USMLE programme recommends that examinees have completed, or be near completion of, at least one postgraduate training year in an accredited US graduate medical education programme before Step 3. That is a recommendation, not an eligibility rule, as the page states it.",
            ]],
            ['h' => 'What we do not state', 'p' => [
                "We do not publish pass rates, preparation advice or a timetable for the Steps. Examination content, fees and application windows change, and the USMLE and ECFMG sites are the only reliable place to read them.",
            ]],
        ]],

        ['id' => 'us-residency', 'eyebrow' => 'United States', 'h' => 'Residency: ERAS, the NRMP Match and visas', 'p' => [
            "A residency is the paid training programme a new doctor must complete in order to be licensed to practise independently. The NRMP, which runs the Main Residency Match, publishes the rules for IMGs. According to its eligibility page for applicants (read on 25 November 2026, no date shown), IMGs must have completed the medical science examination, clinical skills and communication skills requirements. NRMP sends weekly lists of registered IMG applicants to ECFMG, and ECFMG, not the NRMP, determines whether the applicant is verified.",
            "The page adds two points that matter for planning. Verification must be completed by the Rank Order List Certification Deadline, and IMGs who have not met the requirements by then cannot take part in the Match. And to begin residency training, an IMG needs full ECFMG Certification, not only verification for the Match. NRMP also notes that graduates of Canadian medical schools from 1 July 2025 are treated as IMGs for this purpose, which shows how the IMG definition follows where the degree was earned.",
        ], 'sub' => [
            ['h' => 'Visas', 'p' => [
                "Visa questions arise for physicians who are foreign nationals. ECFMG's Exchange Visitor Sponsorship Program page (last updated 18 August 2025, read on 25 November 2026) says that the J-1 is one visa commonly used by foreign national physicians entering US graduate medical education, and that ECFMG is the sole sponsor of J-1 physicians in clinical training programmes. If you hold citizenship or status in another country as well, ask each residency programme which visa types it sponsors. We do not state any other visa rule here because we did not confirm one from an official page.",
            ]],
            ['h' => 'What programmes look for', 'p' => [
                "We have not found an official statement of what residency programme directors prefer in applicants from particular countries or schools, so we say nothing about it, and we do not quote match rates. What the official page does say is that the World Directory notation on the Recognized Accreditation Policy is an additional data point for U.S. programme directors. Look at the programmes you hope to join, and read what each one publishes about the IMGs it considers.",
            ]],
        ]],

        ['id' => 'us-loans', 'eyebrow' => 'United States', 'h' => 'Paying for it: US federal student loans for a school in China', 'p' => [
            "American families often ask whether federal student loans can be used for a medical school abroad. The answer depends on the school, not on the student. Intealth's Recognized Accreditation Policy page explains one part of the rule: a determination of comparability by NCFMEA, which the U.S. Department of Education authorises to evaluate a country's accreditation standards for its medical schools, is required for a country's medical schools to take part in the William D. Ford Federal Direct Student Loan Program.",
            "We tried to read the Federal Student Aid list of foreign schools that take part in federal loan programmes, but its server refused our requests, so we cannot tell you whether any school in China is listed, and we do not guess. Ask the school's admissions office whether it takes part in the Direct Loan Program, and check the school's name on the Federal Student Aid foreign schools list on studentaid.gov yourself. If it is not on the list, plan on private funds or other sources, and ask your financial aid office about anything else you intend to use.",
            "Canadian students should ask the student aid office of their own province or territory about funding for study abroad. We do not state any provincial rule here because we did not read one.",
        ]],

        ['id' => 'canada', 'eyebrow' => 'Canada', 'h' => 'The Canadian route: MCC examinations, CaRMS and provincial licensing', 'p' => [
            "In Canada, three bodies matter. The Medical Council of Canada (MCC) runs the national examinations and issues the Licentiate of the Medical Council of Canada. CaRMS, the Canadian Resident Matching Service, runs the residency match. And the provincial and territorial medical regulatory authorities grant the licence to practise. The MCC's pathways to licensure page (read on 25 November 2026; the page is marked © 2026) says that candidates apply to the regulatory authority for registration and should check the requirements of each jurisdiction where they want to practise.",
        ], 'table' => ['caption' => 'CaRMS R-1 Main Residency Match, international medical graduates: key points as published', 'head' => ['Point', 'What the official page says'], 'rows' => [
            ['Who can apply', 'CaRMS lists Canadian citizens and permanent residents, and says some exceptions may apply (CaRMS eligibility criteria, read 25 November 2026)'],
            ['Medical degree', 'Held or being obtained by 1 July of the match year, from an international medical school listed in the World Directory of Medical Schools that is recognised in Canada (CaRMS)'],
            ['Examinations', 'NAC Examination and MCCQE; some provinces allow exceptions to either exam (CaRMS); the MCC confirms that IMGs must provide MCCQE and NAC results for the R-1 Match (MCC)'],
            ['First iteration', 'No previous RCPSC, CFPC or ACGME accredited postgraduate training, with exceptions (CaRMS)'],
            ['Provincial steps', 'British Columbia, Alberta and Quebec require a provincial assessment or recognition before you apply; Ontario introduced new IMG requirements for the 2026 Match (CaRMS, MCC)'],
        ]], 'sub' => [
            ['h' => 'Citizens and permanent residents', 'p' => [
                "For many Canadian families the most important line on the CaRMS page is that eligibility is limited to Canadian citizens and permanent residents, with some exceptions. If you are a Canadian citizen studying in China, you are within that group. Read the page for the current year, since the eligibility criteria are revised for each match.",
            ]],
            ['h' => 'Check the province you want to work in', 'p' => [
                "The MCC and CaRMS pages say that some provinces add their own assessment programmes for international medical graduates. The MCC names the University of British Columbia's Clinical Assessment Program for British Columbia, the Alberta International Medical Graduate Program for Alberta, and the Collège des médecins du Québec's recognition of equivalence of the MD degree for Quebec. If you already know where you hope to practise, read that province's regulatory authority page before you enrol.",
            ]],
            ['h' => 'LMCC changes', 'p' => [
                "The MCC's page also mentions a notice titled Important update to LMCC eligibility and issuance, starting 26 January 2026. We have not summarised it here. Read it on the MCC site, because it changes how the licentiate is issued.",
            ]],
            ['h' => 'What we could not verify', 'p' => [
                "We did not read each provincial authority's own licensing page, so we do not state what any province requires beyond the points above. Check the current rules with the College of Physicians and Surgeons, or equivalent regulatory body, of the province where you intend to practise.",
            ]],
        ]],

        ['id' => 'wdoms', 'eyebrow' => 'Checking a school', 'h' => 'Checking any school: the World Directory, Sponsor Notes and the MOE list', 'p' => [
            "Whichever school you consider, in China or elsewhere, four checks come first. They take about an hour and they are all free.",
        ], 'list' => [
            'Is the programme on the Ministry of Education list for the intake you want? Use the MOE notice, not an advertisement.',
            'Does the school appear in the World Directory of Medical Schools (wdoms.org), operated by the World Federation for Medical Education in partnership with FAIMER, a division of Intealth?',
            'Does the Sponsor Notes tab show an ECFMG Sponsor Note, and does it include the graduation year you would have?',
            'What does the Sponsor Notes tab say about the Recognized Accreditation Policy, and how does your target residency programme or Canadian province treat that information?',
        ], 'sub' => [
            ['h' => 'XJMU as an example', 'p' => [
                "XJMU is ECFMG eligible as of the date of this guide. We state only this, and we ask you to confirm it yourself on the ECFMG and World Directory pages, because the rules and the lists change and the graduation years in a Sponsor Note matter. The same advice applies to every other university in China.",
            ]],
            ['h' => 'Be careful with the word recognised', 'p' => [
                "We do not write that a North American body recognises or approves a Chinese university unless that body's own page says so about that university. When an agent tells you that a school is recognised in the United States or Canada, ask for the official page that says it, and read the page yourself.",
            ]],
        ], 'link' => ['non-moe-universities-in-china-mbbs-risks', 'Why universities not on the MOE list carry risks']],

        ['id' => 'programme', 'eyebrow' => 'Programme', 'h' => 'The six years: structure and clinical training', 'p' => [
            "At XJMU the MBBS is six years: five years of study followed by a one-year internship. It is taught in English, with introductory Chinese in years 1 and 2. The first years cover the basic medical sciences. Teaching then moves towards clinical subjects, with hospital rotations and a final-year internship. The university has 18 teaching hospitals and 95 internship hospitals, so clinical teaching does not depend on one site.",
            "Look at the programme through the eyes of the North American steps. ECFMG requires at least four credit years from a listed school and verifies your diploma and final transcript with the school. The USMLE Step 1 and Step 2 CK examinations are sat while you are enrolled or after graduation, so ask the university how it supports students who plan to sit them, and how it handles transcript and diploma verification requests from ECFMG.",
        ], 'img' => ['src' => 'assets/images/lab-img.webp', 'alt' => 'People in white coats working at benches in a medical laboratory', 'caption' => 'The early years cover the basic medical sciences before clinical rotations begin.'], 'sub' => [
            ['h' => 'Clinical years', 'p' => [
                "In the clinical years you learn in hospitals where patients speak Chinese. That is why the language requirement exists, and it is also why students who plan to work in North America should think about how they will practise English-language clinical communication. The ECFMG requirement for communication skills, which includes the OET Medicine test, is a reminder that clinical English is assessed too.",
            ]],
            ['h' => 'Ask the university in writing', 'p' => [
                "Before you pay, ask for written answers to these questions: how many weeks of the clinical programme are completed in each hospital, how the internship is assessed, how requests from ECFMG or from a Canadian regulatory authority for a diploma, transcript or confirmation of study are handled, and how long they take. A school that answers precisely is easier to plan around.",
            ]],
        ], 'link' => ['english-taught-mbbs-in-china', 'How the English-taught MBBS works']],

        ['id' => 'clinic-image', 'eyebrow' => 'Clinical training', 'h' => 'What hospital training looks like in practice', 'p' => [
            "A medical degree is built on contact with patients. In the later years you spend your time in teaching hospitals, learning to take histories, examine patients and work in a team under supervision. At XJMU the First Affiliated Hospital is a Class A tertiary hospital, and the clinical years are shared across the affiliated, teaching and internship hospitals named above.",
            "Do not take a promise about hospital placements from a brochure. Ask which hospitals your year group will use, in which language each ward works and how rotations are timetabled. Then ask to speak to a current international student.",
        ], 'img' => ['src' => 'assets/images/clinic.webp', 'alt' => 'A clinician in a white coat examining a young patient in a hospital rehabilitation room', 'caption' => 'Clinical training takes place in hospital settings under supervision.']],

        ['id' => 'visa', 'eyebrow' => 'Visa', 'h' => 'The Chinese student visa from the US or Canada', 'p' => [
            "An MBBS student needs the X1 visa, which the Chinese Embassy in the United States says is issued to those who intend to study in China for more than 180 days. The embassy's page, Requirements and Procedures for Chinese Visa Application (page dated 22 September 2025, read on 25 November 2026), lists for higher-education students the Confirmation Form for Study in China (Form JW201 or Form JW202) and a photocopy of the admission letter issued by the Chinese school. XJMU's admission process names the JW202, which the university issues together with the admission letter.",
            "The same page says applicants fill in forms and upload material through the New COVA online application system, which launched at the embassy on 30 September 2025, and that regular processing takes four business days on average, with an express service of three business days. Treat those as averages, not promises, and do not book flights before the visa is in your passport. The embassy's consular district covers a list of states set out on the page. If you live elsewhere in the United States, check which Chinese consulate covers your address.",
        ], 'img' => ['src' => 'assets/images/visa-process.webp', 'alt' => 'Graphic showing the steps of the China student visa process for MBBS students', 'caption' => 'The X1 visa application is built on two papers from the university.'], 'sub' => [
            ['h' => 'If you apply from Canada', 'p' => [
                "We did not read a Chinese embassy or consulate page for Canada in this session, so we state no Canadian visa rule here. Find the Chinese embassy or consulate, or the visa application service centre, that covers your province, and read its X1 checklist and its dated instructions.",
            ]],
            ['h' => 'Dual citizens and non-citizens', 'p' => [
                "The US embassy page lists proof of legal status for foreign nationals who apply in the United States. If you are a permanent resident, a visa holder or a dual citizen, read the page for what it asks in your case. This is another reason to read the embassy page and not a summary.",
            ]],
            ['h' => 'After arrival', 'p' => [
                "The X1 visa is converted into a residence permit after you arrive, and the application must be made within 30 days of entry, as set out in our China student visa guide. XJMU lists ¥800 for the residence permit and ¥350 for the physical examination from the second year.",
            ]],
        ], 'link' => ['china-student-visa-for-mbbs', 'Read the full China student visa guide']],

        ['id' => 'language', 'eyebrow' => 'Language', 'h' => 'Chinese and English: what to expect', 'p' => [
            "At XJMU the lectures and examinations in the medical subjects are in English. Introductory Chinese is taught in years 1 and 2, and HSK level 4 is required by graduation. In the clinical years patients speak Chinese, so you will use it at the bedside, and daily life in Urumqi is in Chinese.",
            "For students from North America, the practical point is to start early. Learn the basics before you go, keep up the lessons in the first two years, and take the HSK examination in good time so that nothing delays your graduation. An HSK fee is an extra cost paid by students.",
        ], 'link' => ['hsk-requirement-for-mbbs-in-china', 'See the HSK guide for MBBS students']],

        ['id' => 'apply', 'eyebrow' => 'How to apply', 'h' => 'How to apply to XJMU: a worked example', 'p' => [
            "The application steps are the same for every international applicant. Follow them in order, and keep a copy of every receipt and message.",
        ], 'list' => [
            'Check that you meet the XJMU requirements, and settle the North American questions in this guide.',
            'Choose your intake: the September/Fall intake or the March/Spring intake. Plan to apply by the early deadline.',
            'Prepare your documents, record your self-introduction video and complete the medical report.',
            'Submit the application and pay the ¥1,000 application fee as instructed.',
            'After the Pre-Admission Letter, pay the ¥2,000 seat reservation deposit, which is refunded if the JW202 visa letter is rejected.',
            'Apply for your student visa with the admission letter and the JW202 form, and then book your flight.',
        ], 'table' => $s['calendar'], 'sub' => [
            ['h' => 'Documents', 'p' => [
                "Prepare these early. Several are used again at the embassy and after arrival.",
            ], 'list' => $s['documents']],
            ['h' => 'Two intakes', 'p' => [
                "The March/Spring intake and the September/Fall intake both start from the same application. The dates above are the published calendar, and they may vary by programme, so confirm them with admissions.",
            ]],
        ], 'link' => ['mbbs-in-china-admission-calendar-2027', 'See the full admission calendar']],

        ['id' => 'sources', 'eyebrow' => 'Sources', 'h' => 'Rules in this guide and the official page behind each', 'p' => [
            "So that you can check every rule yourself, the table lists each North American and Chinese rule used above, the body that publishes it and the date we read it. All pages were read on 25 November 2026. Where a page shows its own date, we give it.",
        ], 'table' => ['caption' => 'Official sources used for the rules on this page', 'head' => ['Rule', 'Published by', 'Page'], 'rows' => [
            ['ECFMG Certification requirements, IMG definition, Sponsor Notes, Pathways', 'ECFMG (Intealth)', 'ecfmg.org/certification'],
            ['Recognized Accreditation Policy; no effect on eligibility for ECFMG Certification or the USMLE', 'Intealth', 'intealth.org/accreditation'],
            ['J-1 sponsorship of physicians in clinical training (page updated 18 August 2025)', 'ECFMG Exchange Visitor Sponsorship Program', 'ecfmg.org/evsp'],
            ['USMLE eligibility, attempt limits, Step 3', 'USMLE programme', 'usmle.org, Bulletin of Information, eligibility'],
            ['IMG verification and certification for the Main Residency Match', 'NRMP', 'nrmp.org, Are You Eligible?'],
            ['MCCQE and NAC for the R-1 Match; provincial licensing', 'Medical Council of Canada', 'mcc.ca, pathways to licensure'],
            ['R-1 eligibility for international medical graduates', 'CaRMS', 'carms.ca, R-1 eligibility criteria'],
            ['X1 visa documents and processing (page dated 22 September 2025)', 'Chinese Embassy in the United States', 'us.china-embassy.gov.cn'],
            ['Daily exchange rates for the fee table', 'Bank of Canada', 'bankofcanada.ca, Valet API, FXCNYCAD and FXUSDCAD'],
            ['English-taught MBBS list for 2026–2027', "Ministry of Education of the People's Republic of China", 'Notice 教外司来〔2026〕94号, 1 June 2026'],
        ]], 'sub' => [
            ['h' => 'Not verified', 'p' => [
                "We could not confirm from an official page: whether any school in China takes part in US federal student loans, any H-1B rule for physicians, any state licensing board's rule, any province's licensing rule, any Canadian visa rule, and any statement of what residency programme directors prefer. For each we say nothing beyond \"check the current rules with\" the body named above.",
            ]],
        ]],

        ['id' => 'mistakes', 'eyebrow' => 'Avoid', 'h' => 'Mistakes to avoid and how to spot scams', 'p' => [
            "These patterns cause trouble, and each is avoidable.",
        ], 'sub' => [
            ['h' => 'Believing a summary of the rules', 'p' => [
                "An agent, a video or a forum post may be out of date even when the writer means well. Read the official page, note its date and keep a copy. ECFMG says its Recognized Accreditation Policy will continue to evolve, which is a reminder that what was true last year may not be today.",
            ]],
            ['h' => 'Skipping the graduation-year check', 'p' => [
                "The ECFMG Sponsor Note lists the graduation years it covers. Check that your expected graduation year is included, and check again in your final year.",
            ]],
            ['h' => 'Treating a listing as a guarantee', 'p' => [
                "A school's presence on the MOE list or in the World Directory is a useful fact. It is not a promise about your examinations, your residency or your licence.",
            ]],
            ['h' => 'Paying before you have answers in writing', 'p' => [
                "Pay only to the university or to a payment channel it has confirmed in writing, keep every receipt and never pay for a guaranteed scholarship, a guaranteed visa or a guaranteed residency place. Nobody can guarantee those.",
            ]],
            ['h' => 'Forgetting the return', 'p' => [
                "The degree is the middle of the journey. Plan for ECFMG Certification or the MCC examinations, for the residency match and for the licence at the end, and plan the cost and time of each step.",
            ]],
        ], 'link' => ['after-mbbs-in-china', 'Read what happens after MBBS in China']],
    ],
    'faqs' => [
        ['Can I practise in the US after an MBBS from China?', 'Not on the degree alone. A US citizen who graduates abroad is an international medical graduate and must meet ECFMG Certification requirements, pass the USMLE, enter a residency programme and obtain a state licence. Check each step on the official pages before you apply.'],
        ['Does ECFMG accept graduates of Chinese medical schools?', 'ECFMG decides by school and graduation year. A school must be listed in the World Directory of Medical Schools with an ECFMG Sponsor Note that includes your graduation year. XJMU is ECFMG eligible; confirm the current status on the ECFMG and World Directory pages yourself.'],
        ['Is XJMU on the ECFMG list?', 'XJMU is ECFMG eligible, and we ask readers to confirm this on the ECFMG and World Directory pages themselves because rules and lists change. Read the Sponsor Notes tab, including the graduation years it names.'],
        ['What USMLE steps must an international medical graduate pass?', 'ECFMG says IMGs must pass Step 1 and Step 2 CK and satisfy a clinical skills pathway for certification. Step 3 requires passing scores on Steps 1 and 2 CK and a valid ECFMG Certificate, as published by the USMLE programme.'],
        ['Does the ECFMG accreditation requirement apply to my school?', 'Intealth\'s Recognized Accreditation Policy says it does not affect eligibility for ECFMG Certification or the USMLE, and that a school meeting it receives a notation in the World Directory. Read your school\'s Sponsor Notes tab and graduation years.'],
        ['Can I match to a US residency after MBBS in China?', 'The NRMP says IMGs must be verified by ECFMG before the Rank Order List Certification Deadline, and need full ECFMG Certification to begin training. We state no match rates because we found no official figure for Chinese graduates.'],
        ['Can Canadians work as doctors after MBBS in China?', 'Through the Canadian route: MCCQE and the NAC Examination, the CaRMS match for Canadian citizens and permanent residents, and a licence from the provincial or territorial authority. Check each province\'s requirements with its regulatory body.'],
        ['Can I use US federal student loans for MBBS in China?', 'Only if the school takes part in the Direct Loan Program. We could not read the federal list of participating foreign schools, so we state nothing about China. Ask the school and check studentaid.gov.'],
        ['How much is MBBS in China in USD and CAD?', 'At the Bank of Canada rates of 24 November 2026, the XJMU first-year package of ¥49,950 is about USD 7,000 (rounded, as published by the university) or CAD 10,700, and each later year of ¥35,950 is about USD 5,050 or CAD 7,700. The yuan amount is the fixed fee.'],
        ['What are the entry requirements at XJMU?', 'A senior high-school certificate with science subjects, at least 60% in each of physics, chemistry and biology, and an age between 18 and 35. XJMU does not require the CSCA test. Ask admissions how your US or Canadian diploma is assessed.'],
        ['Which visa do I need and where do I apply?', 'The X1 visa, for study of more than 180 days, applied for at the Chinese embassy, consulate or visa centre that covers your address, using the admission letter and the JW202 form. Read the page for your own country.'],
        ['When are the intakes?', 'There are two: the September/Fall intake and the March/Spring intake. Applications for the September/Fall intake open on 1 March, with an early deadline of 30 June. For the March/Spring intake they open on 1 September, with an early deadline of 15 December.'],
    ],
    'related' => ['mbbs-in-china-2027', 'mbbs-in-china-fees-2027', 'mbbs-in-china-eligibility-2027', 'china-student-visa-for-mbbs', 'moe-listed-mbbs-universities-in-china', 'who-listed-medical-universities-in-china', 'after-mbbs-in-china'],
];
