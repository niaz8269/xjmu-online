<?php

$s = require __DIR__ . '/_shared.php';

// Exchange rates for the pound and euro table: European Central Bank euro foreign exchange reference rates of
// 24 November 2026 (the latest published when read on 25 November 2026): EUR 1 = CNY 7.3063 = GBP 0.83700.
// Per yuan: GBP 0.1146, EUR 0.1369. Update the numbers, the caption and docs/sources/mbbs-in-china-for-european-students.md together.
$feeEurope = [
    'caption' => 'XJMU MBBS fees in yuan, US dollars, British pounds and euros (approximate, at ¥1 = £0.1146 and €0.1369, from the European Central Bank reference rates of 24 November 2026, the latest published on 25 November 2026)',
    'head' => ['Fee', 'Chinese yuan', 'US dollars', 'British pounds', 'Euros'],
    'rows' => [
        ['First-year package', '¥49,950', 'USD 7,000', '£5,700', '€6,800'],
        ['Each later year', '¥35,950', 'USD 5,050', '£4,100', '€4,900'],
        ['Six years in total', '¥229,700', 'USD 32,000', '£26,300', '€31,400'],
    ],
];

return [
    'updated' => '2026-11-25',
    'title' => 'MBBS in China for UK & European Students 2027 | GMC Route',
    'description' => 'MBBS in China for UK and European students: the MOE list, visa, fees in pounds and euros, and the real route back to practise in the UK, Ireland and the EU.',
    'keywords' => 'MBBS in China for UK students, MBBS in China for European students, study medicine in China from the UK, MBBS in China GMC, MBBS in China, study MBBS in China, MBBS in China PLAB, MOE listed MBBS, Xinjiang Medical University',
    'eyebrow' => 'Guide for students and parents in the UK and Europe',
    'h1' => 'MBBS in China for UK and European Students: Admission, Costs and the Route Back to Practise',
    'lead' => 'An honest guide for students, parents and graduates in the United Kingdom and Europe who are considering an MBBS in China: how the Ministry of Education list works, what the Chinese visa needs, what it costs in pounds and euros, and what the UK, Irish and EU regulators say you must do before you can practise at home. Xinjiang Medical University (XJMU) is used as one worked example.',
    'crumb' => 'MBBS in China for UK and European students',
    'cta' => 'Talk to admissions',
    'sections' => [

        ['id' => 'overview', 'eyebrow' => 'Overview', 'h' => 'MBBS in China for UK and European students: the honest picture', 'p' => [
            "If you live in the United Kingdom or elsewhere in Europe and you are thinking about studying medicine in China, you are really asking three separate questions. Can I be admitted to a Chinese university that is allowed to teach MBBS in English? Can I manage the visa, the money and six years far from home? And, most important for your future, what will the regulator in the country where I want to practise require of me when I return? A guide that answers only the first two is a brochure. This one tries to answer all three, and it says clearly which answers we can state and which you must check yourself.",
            "Many readers of this guide are residents or citizens of European countries with family roots in Asia or Africa. Others are Europeans who did not get a place at a medical school at home and are weighing up their options. The facts below apply to both groups, because every one of the rules that matter is set by the place where you studied and the regulator where you want to work, not by your passport alone.",
            "On the Chinese side, the facts are firm. The Ministry of Education (MOE) publishes a list of the universities that may enrol international students in the English taught MBBS, and the list for 2026–2027 has 43 universities. On the home side, the facts are strict and they change. The General Medical Council (GMC) in the United Kingdom, the Medical Council of Ireland and the authorities in Germany and the Netherlands each set their own conditions for doctors who qualified outside their territory. We read their own pages on 25 November 2026, we say what they say, and where a page was conditional we say so. Where we could not read an official page, we tell you to check with the regulator, and we do not guess.",
            "One more point belongs at the start. Cost is one reason families in Europe look at China, and it is a fair reason. But a degree from China does not by itself give anyone the right to work as a doctor in the United Kingdom or any EU country. A graduate still has to meet the home regulator's requirements, and nobody, including this website, can promise a job or a training post at the end of it.",
        ], 'img' => ['src' => 'assets/images/campus-img.webp', 'alt' => 'Campus buildings of Xinjiang Medical University in Urumqi, used in this guide as a worked example of an MOE listed MBBS university', 'caption' => 'Campus buildings at XJMU, the worked example in this guide.']],

        ['id' => 'checklist', 'eyebrow' => 'Checklist', 'h' => 'Six questions to settle before you pay any fee', 'p' => [
            "Whatever country you live in, these six questions decide whether the plan works. Answer them in order and keep a copy of the page that gives you each answer.",
        ], 'list' => [
            'Is the university on the Ministry of Education list of universities that may teach the English taught MBBS to international students this year?',
            'Is the medical school listed in the World Directory of Medical Schools, and is it clear of any warning on your home regulator\'s pages?',
            'Does your home regulator accept the qualification? For the GMC, this is checked before you may book the PLAB test.',
            'What must a graduate pass or hold to register at home, and are you prepared for it, including the language test?',
            'Can your family fund six years, with a margin for exchange rates and flights?',
            'Can you cope with six years in a Chinese city, where daily life is in Chinese even though lectures are in English?',
        ], 'sub' => [
            ['h' => 'Why the order matters', 'p' => [
                "The first two questions are about the university, the next two are about the regulators, and the last two are about you and your family. If any answer is no, the fee and the flight do not matter yet. Settle the regulator questions before you pay a deposit, not after.",
            ]],
        ]],

        ['id' => 'why-europe', 'eyebrow' => 'Why people look at China', 'h' => 'Why students in the UK and Europe look at China', 'p' => [
            "We do not publish figures on how many students leave Europe to study medicine, because we have no verified source for them. What we can describe are the reasons that readers tell us about, and you can test each one against your own situation.",
        ], 'list' => [
            'Cost. XJMU publishes its fees in yuan, and the university fees for all six years add up to about ¥229,700, which is about USD 32,000. The table further down shows the same sums in pounds and euros.',
            'A programme taught in English. The MOE list names the universities allowed to teach the MBBS to international students in English, so you do not need Chinese to be admitted.',
            'A fixed route. The MBBS in China is a six-year programme that includes a one-year internship, and the admission requirements and fees are published in advance.',
            'No Chinese entrance test in the XJMU requirements. XJMU does not require the CSCA test, and its published requirements are marks and age. Whether your own country asks you to sit a test is a separate matter for you and your regulator.',
        ], 'sub' => [
            ['h' => 'What these reasons do not tell you', 'p' => [
                "None of the four reasons says anything about what happens when you return. That is decided by the regulator in the country where you want to practise, and the later sections of this guide set out what those regulators say. Please read them before you read the fees.",
            ]],
        ]],

        ['id' => 'moe', 'eyebrow' => 'The Chinese list', 'h' => 'The Ministry of Education list of English taught MBBS universities', 'p' => [
            "The Ministry of Education of the People's Republic of China issues a yearly notice naming the universities that may enrol international students in the English taught MBBS. The notice for 2026–2027 was issued on 1 June 2026 and names 43 universities with a combined quota of 2,790 seats. The order of the list is the order of publication, not a ranking, and the seat figure is a ceiling on new international enrolment for that year.",
            "The notice sets five rules, and each one matters to a family in Europe because home regulators look at where and how a student studied.",
        ], 'list' => [
            'Only universities on the approved list may enrol international students into the English taught MBBS for 2026–2027.',
            'Universities not on the list are prohibited from issuing any English-language degree certificate bearing the term MBBS.',
            'Unlisted universities may only admit international students to Chinese-medium clinical medicine programmes, held to the same standard as domestic students.',
            'Using a bilingual teaching model as a workaround is strictly prohibited.',
            'Universities that seriously violate the notice face enrolment restrictions or full suspension.',
        ], 'table' => [
            'caption' => 'The MOE list for 2026–2027 at a glance, and XJMU\'s place on it',
            'head' => ['Item', 'Detail'],
            'rows' => [
                ['Notice', "Ministry of Education notice 教外司来〔2026〕94号, issued 1 June 2026"],
                ['Universities on the list', '43'],
                ['Combined seat quota', '2,790'],
                ['XJMU', 'Number 8 on the list, 60 seats'],
                ['What the list means', 'Permission to teach the English taught MBBS to international students for that academic year; not a ranking and not a promise of recognition abroad'],
            ],
        ], 'sub' => [
            ['h' => 'MOE listing is not recognition by your regulator', 'p' => [
                "Being on the MOE list tells you that China permits the programme. It does not tell you that the GMC, the Medical Council of Ireland or any other regulator accepts the qualification. That is a separate decision, made by each regulator under its own rules, and the GMC section below shows why the two lists are different. Read the full list of 43 universities in our guide to the MOE list, and compare any other name you are offered with it, letter for letter and city for city.",
            ]],
        ], 'link' => ['moe-listed-mbbs-universities-in-china', 'See the full MOE list of 43 universities with seats']],

        ['id' => 'eligibility', 'eyebrow' => 'Admission', 'h' => 'Eligibility: what XJMU asks of an applicant', 'p' => [
            "Every university sets its own requirements within the MOE framework, so the figures below are XJMU's published requirements and not a rule for all of China. We use them as a worked example. If you are looking at another university, ask it for its own requirements in writing.",
        ], 'list' => $s['requirements'], 'sub' => [
            ['h' => 'Marks in physics, chemistry and biology', 'p' => [
                "XJMU asks for at least 60% in each of the science subjects, physics, chemistry and biology, at the level of an A-level or an equivalent senior high-school certificate. The requirement applies to each subject separately, not to an average of the three.",
            ]],
            ['h' => 'UK A-levels, the International Baccalaureate and European school-leaving certificates', 'p' => [
                "A-levels and many European certificates report results as letters, points or grades on a national scale, not as percentages. XJMU's published wording does not explain how such results are converted into a percentage, and we will not invent a conversion for you. If you hold A-levels, the International Baccalaureate, an Abitur, a baccalauréat or another European certificate, write to admissions with a copy of your results and ask in writing whether you meet the 60% requirement in each science subject. Keep the reply.",
            ]],
            ['h' => 'Age', 'p' => [
                "Applicants must be aged 18 to 35. This leaves room for graduates and for older applicants who are changing career, but you should still check the dates against your own birth date before you apply.",
            ]],
            ['h' => 'Entrance tests', 'p' => [
                "XJMU does not require the CSCA test. Its published requirements list marks and age, together with good health and a valid passport. Some countries ask their own citizens to meet conditions before they study medicine abroad, so check with your own regulator and your own funding body.",
            ]],
        ]],

        ['id' => 'fees', 'eyebrow' => 'Cost', 'h' => 'What an MBBS in China costs: fees in yuan, pounds and euros', 'p' => [
            "XJMU charges its fees in Chinese yuan. The fees for the 2027 intakes are the same as for 2026: a first-year package of ¥49,950, which is about USD 7,000, then ¥35,950 for each later year, which is about USD 5,050. Over six years the university fees come to about ¥229,700, or about USD 32,000. The first-year package covers application processing, tuition, hostel accommodation, the student visa, health insurance, the medical check, the JW202 visa letter and airport pickup.",
            "The table converts the same fees into British pounds and euros. The conversion uses the euro foreign exchange reference rates published by the European Central Bank for 24 November 2026, the latest set available when we checked on 25 November 2026: one euro bought 7.3063 yuan and 0.837 pounds. The US dollar column keeps the rounded figures already published elsewhere on this site and was not recalculated.",
        ], 'table' => $feeEurope, 'sub' => [
            ['h' => 'Read the table as a guide, not a quotation', 'p' => [
                "Exchange rates move every day, and banks and transfer services add a few percent on top of the mid-market rate. The yuan amount is the one that is fixed. For the sum you will really pay, take the yuan figure, multiply it by the rate your bank gives on the day you send the money, and add a margin for charges.",
            ]],
            ['h' => 'What the fees do not include', 'p' => [
                "Food, local transport and a mobile SIM are paid by you. In Urumqi food is typically USD 150–250 a month, local transport USD 10–20 and a SIM USD 10–15, according to the published figures. Flights between Europe and China, visa fees at home, the medical examination at home and any document attestation are also yours to budget for. Ask admissions in writing which costs the package covers.",
            ]],
            ['h' => 'Cost is a reason, not a guarantee', 'p' => [
                "Lower fees are one reason people look at China, and it is an honest one. But a graduate must still pass the requirements of the home regulator, which can include examinations, language tests and fees of their own, and this guide cannot promise a job or a training post. Please add those costs and that uncertainty to your family budget before you decide.",
            ]],
        ], 'link' => ['mbbs-in-china-fees-2027', 'See the full fee breakdown for 2027']],

        ['id' => 'gmc', 'eyebrow' => 'Practising in the UK', 'h' => 'The route back to practise in the UK: what the GMC says', 'p' => [
            "In the United Kingdom, the General Medical Council (GMC) decides who can be registered with a licence to practise. Everything in this section is taken from the GMC's own web pages, as published by the GMC and read by us on 25 November 2026. The GMC changes its rules from time to time, so read the pages themselves before you act, and treat this section as a map and not as advice.",
            "The GMC is not an agency that works for any university, and no university can negotiate with it on your behalf. It says of itself that its job is to make sure international medical graduates have the necessary knowledge and skills to be granted full registration and a licence to practise medicine in the UK.",
        ], 'sub' => [
            ['h' => 'Step 1: your primary medical qualification must be acceptable to the GMC', 'p' => [
                "As published by the GMC, if your primary medical qualification is from outside the UK, the GMC needs to make sure it is an acceptable overseas qualification before you can take the PLAB test or apply for registration. You must check this first. The GMC says that if you take the PLAB test and later find that the GMC does not accept your qualification, your PLAB fees will not be refunded. The GMC also says that it is at its discretion to decide which overseas qualifications it accepts and that you cannot appeal against its decision.",
            ]],
            ['h' => 'Step 2: the PLAB test, or another way the GMC accepts', 'p' => [
                "As published by the GMC, the majority of international medical graduates show that they meet its requirements by taking the Professional and Linguistic Assessments Board (PLAB) test. The test has two parts and candidates must pass both parts within a maximum of four attempts at each. Part 1 is a written applied knowledge test, held at various locations around the world. Part 2 is a clinical and professional skills assessment, in the style of an OSCE, held at the GMC's clinical assessment centre. The GMC states that the PLAB test is compliant with the Medical Licensing Assessment requirements. The GMC's pages also mention other ways to show that you meet its requirements, such as an acceptable overseas registration clinical exam, and the page that lists them is the place to read about them.",
            ]],
            ['h' => 'Step 3: knowledge of English', 'p' => [
                "As published by the GMC, international doctors must show it that they have the necessary knowledge of English before they are allowed to take the PLAB test, and most do this by reaching the GMC's minimum scores in the IELTS test or in the OET, the medicine version. The GMC adds that the English evidence it accepts is subject to change. Read the current scores on its page, because we do not repeat numbers that can change.",
            ]],
            ['h' => 'Step 4: provisional or full registration with a licence to practise', 'p' => [
                "As published by the GMC, a graduate of a medical school outside the UK or Switzerland is eligible for provisional registration with a licence to practise if they have passed both parts of the PLAB test within the past two years, or passed an acceptable overseas registration clinical exam within the past two years, and have not completed an internship. A graduate who has passed both parts of PLAB within the past two years and has completed an internship applies instead for full registration. The GMC also says that the primary medical qualification must be independently verified before it can grant registration.",
            ]],
        ]],

        ['id' => 'gmc-school', 'eyebrow' => 'Practising in the UK', 'h' => 'The GMC and the medical school: what to check before you apply', 'p' => [
            "The GMC publishes the criteria it uses to decide whether an overseas primary medical qualification is acceptable, together with two lists of institutions: those it does not accept qualifications from, and those whose qualifications it assesses case by case. Read the following as published by the GMC on 25 November 2026.",
        ], 'list' => [
            'Criterion A: the awarding institution must have an entry in the World Directory of Medical Schools, and the degree title, duration, awarding body and any affiliations must match the listing. The qualification must also allow the holder to register to practise medicine in the country of the awarding body, which includes allowing a period of postgraduate clinical training in that country.',
            'Criterion B: the programme must have lasted at least three years, and the GMC expects no less than 5,500 clock hours of allopathic or clinical medicine, including practical and clinical experience, and excluding your own revision time.',
            'Criterion D: the programme must have given sufficient clinical and practical experience, through placements or rotations that include medicine and surgery and that the institution oversaw, with formal agreements with the placement providers.',
            'Criterion E: the majority of the programme, theory and clinical, must have been taught in person at the institution, including affiliated campuses and clinical settings. Significant online teaching is considered only in exceptional circumstances.',
        ], 'sub' => [
            ['h' => 'Two lists, and what they say about China', 'p' => [
                "The GMC says that it does not accept qualifications from the institutions on its first list, and that it assesses qualifications from the institutions on its second list on a case by case basis, which it says does not mean that the qualification will not be acceptable but does mean it needs a full assessment of the individual programme. Both lists include institutions in China. Some of the Chinese institutions on the second list also appear on the Ministry of Education list, which is the clearest reason why an MOE listing and the GMC's acceptance are different things.",
                "When we read the GMC's two lists on 25 November 2026, Xinjiang Medical University did not appear on either of them. That is only an observation about the lists on that day. It is not a statement by the GMC that it accepts XJMU qualifications, and the GMC itself says that a school which is not on its lists is not automatically acceptable, because the qualification must still satisfy all of its criteria. The GMC also asks anyone whose awarding body is on either list, or is not listed in the World Directory of Medical Schools, to contact it before applying to take PLAB or to register.",
            ]],
            ['h' => 'What to do', 'p' => [
                "Before you pay a deposit, read the GMC's guide Acceptable overseas qualifications for doctors in full, read both lists again in the week you decide, and contact the GMC with the name of the awarding body and the programme if you have any doubt. Ask the university in writing for the duration of the programme, the number of clinical hours in each year and where the clinical placements are held, because the GMC asks the institution for those same facts. Never rely on a summary from an agent, including this one.",
            ]],
        ], 'link' => ['who-listed-medical-universities-in-china', 'Read how to check a school in the World Directory of Medical Schools']],

        ['id' => 'foundation', 'eyebrow' => 'Practising in the UK', 'h' => 'After the GMC: the UK Foundation Programme and a training post', 'p' => [
            "A medical graduate who wants to work in the NHS normally starts in the UK Foundation Programme, a two-year programme whose first year is called F1. The UK Foundation Programme Office (UKFPO) publishes its eligibility rules every year. We read the pages for the 2027 recruitment round on 25 November 2026.",
            "As published by the UKFPO, people who are studying at or have qualified from a non-UK medical school must submit an eligibility application before they make a Foundation Programme application. To apply to the two-year programme an applicant must hold, or expect to be eligible for, provisional registration with the GMC, and the UKFPO says that anyone who holds or expects to be eligible for full registration is not eligible to apply to two-year programmes in the UK. The GMC itself says that a graduate must have provisional registration with a licence to practise before the first year of the Foundation Programme begins.",
            "For a graduate who still has to pass PLAB, the UKFPO sets fixed deadlines. For a programme starting in August 2027 the UKFPO says an applicant who needs to take PLAB must have qualified from medical school on or before 31 August 2026, otherwise the application is ineligible, and it says that its deadlines cannot be extended. These dates are for one recruitment round and change every year, so read the current pages. They show how tightly the timing of graduation, PLAB and registration is linked.",
        ], 'sub' => [
            ['h' => 'What this guide cannot tell you', 'p' => [
                "This guide does not say how many international graduates are offered Foundation posts, or how competitive they are. We have no official figure that we can verify, and we will not guess. Nobody can promise you a post. Plan so that your career still makes sense if the first application is not successful.",
            ]],
        ]],

        ['id' => 'ireland-eu', 'eyebrow' => 'Practising in Ireland and the EU', 'h' => 'Ireland, Germany, the Netherlands and the rest of the EU', 'p' => [
            "Rules in the EU are made country by country, and they depend on where the medical school is, not on your citizenship. Below are the pages we could read on 25 November 2026. Where we could not read an official page, we have left the rule out and told you who to ask.",
        ], 'sub' => [
            ['h' => 'Ireland: the Medical Council', 'p' => [
                "As published by the Medical Council of Ireland, doctors who qualified outside the EU or EEA are required to pass, or to be exempt from, its Pre-Registration Examination System (PRES). The Council lists three routes to general registration: the Certificate of Experience route, which needs a document that the Council considers at least equivalent to an Irish internship; the Higher Qualification route, which needs a higher qualification that the Council has recognised for PRES exemption; and the PRES route for applicants who do not meet the other two. On internship registration, the Council states that graduates of medical schools in non-EU or EEA states may not apply for internship posts or internship registration in Ireland. The page is written about the place of the medical school, and it does not say anything about the nationality of the student. If you plan to return to Ireland, ask the Medical Council which route would apply to your degree before you apply anywhere.",
            ]],
            ['h' => 'Germany: the Approbation', 'p' => [
                "As published in the German Federal Medical Act (Bundesärzteordnung), which we read on the official federal law site, a licence to practise (Approbation) requires, among other things, the knowledge of the German language needed for the profession. For a qualification gained in a third country, section 9d of the Act says that the proof of the necessary knowledge and skills is given by a Kenntnisprüfung, a licensing examination based on the content of the German state final examination, unless the applicant chooses the alternative route of an equivalence check, which is open only up to four weeks after the application is filed. The same section says that where the equivalence check finds substantial differences, a Kenntnisprüfung must be passed. The competent authority is that of the German federal state, called a Land, and its page is the one to read. We could not open the federal government's information portal, so we do not give timings or costs.",
            ]],
            ['h' => 'The Netherlands: the BIG register', 'p' => [
                "As published by the BIG register, the Dutch official register, the Certificate of Professional Competence procedure applies to anyone who holds a diploma from outside Europe. The Commission for Foreign Healthcare Graduates (CBGV) examines whether the education, diploma and any work experience are comparable to the Dutch standard. Doctors must take a professional content test, called the BI test. When we read the BIG register's test page on 25 November 2026, it said that the BI test for physicians was full for the year and that it was not known when participants could be scheduled again. The register says the whole application takes an average of six to 18 months. Check its page for the current position before you plan around it.",
            ]],
            ['h' => 'Other EU countries', 'p' => [
                "We could not read official pages for France, Italy, Spain, Sweden, Norway, Poland, Greece, Romania or other countries, and so we state no rule for them. Check the current rules with the national medical register or competent authority of the country where you want to work, and ask for them in writing.",
            ]],
            ['h' => 'Is a recognition in one EU country valid in another?', 'p' => [
                "The EU's Directive 2005/36/EC on the recognition of professional qualifications is the main law for moving within the EU. As published on EUR-Lex, its consolidated text of 29 October 2025 says in Article 3(3) that evidence of formal qualifications issued by a third country is regarded as evidence of formal qualifications if the holder has three years' professional experience in the profession on the territory of the Member State that recognised it, certified by that Member State. In plain words, a diploma from China that one country has recognised does not become an EU qualification on its own. Three years of professional experience in the recognising country is one condition the Directive sets. Whether a given country applies it to your case is for that country's authority to say.",
            ]],
        ]],

        ['id' => 'wdoms', 'eyebrow' => 'Checking a school', 'h' => 'WDOMS and what to check about any medical school', 'p' => [
            "The World Directory of Medical Schools (WDOMS) is the international record of medical schools. The GMC makes an entry in the directory a condition of its first criterion, so for UK purposes a listing is necessary. A listing is a record. It is not an inspection and not an endorsement, so it is never enough on its own.",
            "XJMU is listed in WDOMS and is on the MOE list, as published elsewhere on this site, and XJMU is eligible for ECFMG certification, which matters if you also think about the United States. You can search WDOMS yourself in a few minutes, and the same checklist works for any university anywhere.",
        ], 'list' => [
            'Find the exact name and city on the Ministry of Education list for the current year.',
            'Search the World Directory of Medical Schools and compare the degree title, duration and awarding body with your offer letter.',
            'Read your regulator\'s pages for any list of institutions it does not accept or accepts only after assessment, and re-read them in the week you pay.',
            'Ask the university in writing for the programme length, the clinical hours in each year and the hospitals where you will train.',
            'Ask the university to confirm in writing that your offer is for the English taught MBBS and not for a different programme.',
            'Write to your regulator with the name of the university and the programme and keep its reply.',
        ], 'sub' => [
            ['h' => 'Similar names are common', 'p' => [
                "Chinese cities and universities often have near-identical names. XJMU is in Urumqi, and it should not be confused with a university with a similar name in another province. Match the full name and the city every time.",
            ]],
        ], 'link' => ['non-moe-universities-in-china-mbbs-risks', 'Read the risks of universities that are not on the MOE list']],

        ['id' => 'structure', 'eyebrow' => 'The programme', 'h' => 'What the six years look like', 'p' => [
            "The MBBS at XJMU is a six-year programme that includes a one-year internship. The university lists 18 teaching hospitals and 95 internship hospitals. Teaching is in English, with introductory Chinese in years 1 and 2.",
            "The shape is familiar to anyone who knows a medical curriculum: basic medical sciences first, then clinical subjects, then hospital rotations and an internship. The difference from a European medical school is the setting. Your lectures are in English, but your patients in the later clinical years speak Chinese, and your daily life in Urumqi is in Chinese too.",
        ], 'sub' => [
            ['h' => 'Years 1 and 2', 'p' => [
                "The early years cover the basic medical sciences and introductory Chinese. Use them to build the habits that the later years need, and begin your Chinese from the first week.",
            ]],
            ['h' => 'Years 3 to 5', 'p' => [
                "Teaching moves towards clinical subjects and more time in the affiliated hospitals. Students who have worked on their Chinese find these years much easier.",
            ]],
            ['h' => 'Year 6: the internship', 'p' => [
                "The final year is a full clinical internship. This is worth checking against the rules you read earlier: the GMC's criteria look at clinical placements, and the Irish and German routes also refer to internships or clinical knowledge. Ask the university for a written description of the internship, and keep it.",
            ]],
        ], 'img' => ['src' => 'assets/images/affil-hospt.webp', 'alt' => 'Building of an affiliated teaching hospital where XJMU MBBS students complete their clinical years', 'caption' => 'The clinical years take place in affiliated teaching hospitals.']],

        ['id' => 'visa', 'eyebrow' => 'Visa', 'h' => 'The Chinese student visa from the UK and Europe', 'p' => [
            "An MBBS lasts six years, so an international student needs the long-term study visa, called the X1 visa. The visa process starts with admission, because the embassy asks for two papers that only the university can provide: the admission notice and the JW202 form.",
            "As published by the Chinese Visa Application Service Centre in London on its page for the X1 visa, long-term study of more than 180 days, which we read on 25 November 2026, an applicant provides the admission notice issued by the school in China and the original and a photocopy of the Visa Application for Study in China, Form JW201 or JW202. The page asks for a passport with at least six months of remaining validity and at least two blank visa pages, and one recent colour passport photograph with a white background. It also says that an applicant who is not applying in the country of citizenship must provide proof of legal status in the UK, such as a study visa or residence permit, and that an applicant under 18 must have the form signed by both parents or guardians. After entry, the page says, holders of the X1 visa must apply for a residence permit from the immigration department within 30 days.",
        ], 'sub' => [
            ['h' => 'Follow the page for your own country', 'p' => [
                "We read the London page. Chinese embassies and visa centres in other European countries publish their own pages, with their own fees and rules, and those pages are updated. Find the Chinese embassy or visa centre that covers your home address, read its page for the X1 visa, and look at the date on it. Do not fill in a visa form on a third-party website that only looks official.",
            ]],
            ['h' => 'What XJMU provides', 'p' => [
                "At XJMU the first-year package includes the student visa, health insurance and medical items, and the JW202 visa letter with airport pickup. You apply online, pay the ¥1,000 application fee, receive a Pre-Admission Letter, pay the ¥2,000 seat reservation deposit and then receive the admission letter and the JW202. The deposit is refunded if the JW202 visa letter is rejected, so ask admissions to confirm that in writing with the payment instructions.",
            ]],
            ['h' => 'No one can promise a visa', 'p' => [
                "The consular officer decides, and an officer may ask for more papers. Book your flight only after the visa is in your passport.",
            ]],
        ], 'img' => ['src' => 'assets/images/visa-process.webp', 'alt' => 'Illustration of five steps for a student visa: finalise the university, apply for the relevant documents, apply for the visa, an interview if applicable, then receive the visa and prepare to leave', 'caption' => 'A general illustration of the steps for a study visa. Follow the page of your own Chinese embassy or visa centre.'], 'link' => ['china-student-visa-for-mbbs', 'Read the full China student visa guide']],

        ['id' => 'language', 'eyebrow' => 'Language', 'h' => 'Language: Chinese in a programme taught in English', 'p' => [
            "Lectures are in English, but daily life, hospital corridors and, later, patient conversations are in Chinese. At XJMU, introductory Chinese is taught in years 1 and 2, and HSK level 4 is required by the time you graduate. HSK is the Chinese proficiency test, and level 4 is the level that is needed in order to graduate.",
            "For a student heading back to Europe, there is a second language to think about. The GMC requires evidence of English at its own standard, and Germany requires the German language knowledge needed for the profession. These are separate from the HSK. Plan your language study so that Chinese does not crowd out the language of the country where you intend to practise.",
        ], 'img' => ['src' => 'assets/images/language.webp', 'alt' => 'Classroom with desks, a projector and a whiteboard showing the Chinese characters for Chinese course, with an instructor and several students', 'caption' => 'Introductory Chinese is taught in years 1 and 2.'], 'link' => ['hsk-requirement-for-mbbs-in-china', 'Read the HSK requirement guide']],

        ['id' => 'apply', 'eyebrow' => 'How to apply', 'h' => 'How to apply to XJMU: a worked example', 'p' => [
            "The application is the same for all international applicants, wherever they live. The intakes are the March/Spring intake and the September/Fall intake.",
        ], 'list' => [
            'Check that you meet the XJMU requirements and settle the regulator questions in this guide.',
            'Choose between the March/Spring intake and the September/Fall intake, and plan to apply by the early deadline.',
            'Prepare your documents, record your self-introduction video and have the Chinese physical examination form completed.',
            'Submit the application and pay the ¥1,000 application fee as instructed.',
            'After the Pre-Admission Letter, pay the ¥2,000 seat reservation deposit, which is refunded if the JW202 visa letter is rejected.',
            'Apply for the student visa with the admission letter and the JW202, and book your flight after the visa is issued.',
        ], 'table' => $s['calendar'], 'sub' => [
            ['h' => 'Documents', 'p' => [
                "Prepare these early. Documents issued in the UK or Europe may need to be certified or translated, so ask admissions in writing what format is accepted.",
            ], 'list' => $s['documents']],
            ['h' => 'Scholarships', 'p' => [
                "Two scholarships are listed in the published fee structure, the Chinese Government Scholarship and the Xinjiang Government Scholarship for students from neighbouring countries. Places are limited, and eligibility depends on your situation, so ask admissions whether you qualify before you plan on one.",
            ]],
        ], 'img' => ['src' => 'assets/images/adm-img.webp', 'alt' => 'Admissions office desk where applicants ask about eligibility and documents', 'caption' => 'A written reply from admissions is the record that protects you.'], 'link' => ['mbbs-in-china-admission-calendar-2027', 'See the full admission calendar']],

        ['id' => 'mistakes', 'eyebrow' => 'Avoid', 'h' => 'Common mistakes, money and scams', 'p' => [
            "Most of the trouble that families meet is avoidable. These are the patterns to watch for.",
        ], 'sub' => [
            ['h' => 'Paying before checking the regulator', 'p' => [
                "Do not pay a deposit until you have read your regulator's current rules and received the university's answers in writing. For the GMC, the acceptability of your qualification is checked before the PLAB test, not after it.",
            ]],
            ['h' => 'Trusting a single source', 'p' => [
                "An agent, a video or a forum post is not a source for a regulator's rules. Use the regulator's own page, the MOE list and the university's written replies.",
            ]],
            ['h' => 'Believing a promise of recognition', 'p' => [
                "No university or agent can promise that a regulator will accept a qualification, that you will pass an exam, or that you will get a training post. Be careful with anyone who says otherwise.",
            ]],
            ['h' => 'Paying into a personal account', 'p' => [
                "Pay only to the university or to a channel that the university has confirmed in writing, keep every receipt and never pay for a guaranteed scholarship. If a payment request looks different from the university's published fee structure, ask admissions before you pay.",
            ]],
            ['h' => 'Leaving the language until later', 'p' => [
                "Students who postpone Chinese struggle in the clinical years. Begin in the first semester.",
            ]],
            ['h' => 'Ignoring that rules change', 'p' => [
                "Regulators change their rules, sometimes within the six years that you study. Keep reading their pages throughout the programme, and keep a dated copy of what you relied on.",
            ]],
        ]],

        ['id' => 'next', 'eyebrow' => 'Next steps', 'h' => 'What we checked, what we did not, and your next steps', 'p' => [
            "Everything in this guide that states a rule of a regulator comes from that regulator's own page, read on 25 November 2026: the GMC, the UK Foundation Programme Office, the Medical Council of Ireland, the German Federal Medical Act, the BIG register, EUR-Lex and the Chinese Visa Application Service Centre in London. The exchange rates come from the European Central Bank. The facts about the Ministry of Education notice and XJMU come from this site's published pages and from the university.",
            "We did not verify the rules of the other European countries, the full list of English scores for the GMC, the Irish Medical Council's detailed conditions for the Certificate of Experience and Higher Qualification routes, the German federal state authorities' timings and costs, the funding rules for study abroad in each country, or the number of international graduates who obtain training posts. For those, check the current rules with the regulator or authority concerned.",
            "Your next steps are simple. Read the GMC's guide on acceptable overseas qualifications, or the equivalent for the country where you want to practise. Check your marks against the 60% requirement in each science subject and ask admissions how your certificate is read. Ask for the fee structure and the programme details in writing. If you decide to go ahead, choose your intake and apply by the early deadline.",
        ], 'link' => ['mbbs-in-china-2027', 'Read the MBBS in China 2027 overview']],
    ],
    'faqs' => [
        ['Can I study MBBS in China from the UK?', 'Yes. Universities on the Ministry of Education list may enrol international students in the English taught MBBS, and XJMU is number 8 on the 2026–2027 list. Whether you can later practise in the UK is a separate question decided by the GMC, which you should read about before you apply.'],
        ['Does the GMC accept an MBBS from China?', 'The GMC decides case by case under published criteria and says it is at its discretion. It lists some institutions it does not accept and others it assesses individually. It also says that you must check your qualification is acceptable before you book PLAB. Read its guide and contact it about your own school.'],
        ['Is XJMU on the GMC\'s lists?', 'When we read the GMC\'s two lists on 25 November 2026, XJMU was not on either. That is an observation, not a statement that the GMC accepts XJMU, and the GMC says a school not on its lists is not automatically acceptable. Check the lists again before you pay.'],
        ['Do I need to pass PLAB after an MBBS in China?', 'The GMC says that the majority of international medical graduates show they meet its requirements by passing the two-part PLAB test, and it also mentions other ways, such as an acceptable overseas registration clinical exam. Read its current page, because rules change.'],
        ['Can I get a UK Foundation Programme place?', 'Non-UK graduates make an eligibility application first, and they need to be eligible for GMC provisional registration. We cannot say how many get a place, and nobody can promise one. Read the UKFPO\'s pages for the current round.'],
        ['What about Ireland, Germany or the Netherlands?', 'Each has its own route. Ireland\'s Medical Council lists three routes, including its PRES examination. Germany\'s Approbation involves a Kenntnisprüfung or an equivalence check plus German language. The Dutch BIG register uses an assessment and a BI test, and said test places were full when we read it. Check each page.'],
        ['What does the MBBS in China cost in pounds and euros?', 'The university fees for six years are about ¥229,700. At the European Central Bank rates of 24 November 2026 that is about £26,300 or €31,400. The first-year package of ¥49,950 is about £5,700 or €6,800. Banks add a few percent, and the yuan amount is the fixed one.'],
        ['Which marks does XJMU need, and how do A-levels fit?', 'At least 60% in each of physics, chemistry and biology, at A-level or an equivalent senior high-school level, and an age of 18 to 35. XJMU does not publish how letter grades convert to percentages, so send your results to admissions and ask in writing.'],
        ['Which Chinese visa do I need?', 'The X1 visa for study of more than 180 days. The London visa centre\'s page asks for the admission notice and the JW201 or JW202 form, and XJMU issues the JW202. After arrival you must apply for a residence permit within 30 days. Follow the page of your own embassy.'],
        ['How long is the MBBS in China, and is it in English?', 'At XJMU it is six years including a one-year internship, taught in English, with introductory Chinese in years 1 and 2 and HSK level 4 required by graduation.'],
        ['When are the intakes?', 'There are two: the March/Spring intake and the September/Fall intake. Applications for the September/Fall intake open on 1 March with an early deadline of 30 June, and for the March/Spring intake on 1 September with an early deadline of 15 December.'],
        ['Is a Chinese degree valid across the EU?', 'No single answer exists. Under Article 3(3) of Directive 2005/36/EC, a third-country qualification recognised by one Member State counts as an EU qualification only with three years\' professional experience there, certified by that state. Each country\'s own authority decides your case.'],
    ],
    'related' => ['mbbs-in-china-2027', 'mbbs-in-china-fees-2027', 'mbbs-in-china-eligibility-2027', 'china-student-visa-for-mbbs', 'moe-listed-mbbs-universities-in-china', 'who-listed-medical-universities-in-china', 'after-mbbs-in-china'],
];
