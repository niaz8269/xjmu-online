<?php

$s = require __DIR__ . '/_shared.php';

// Mid-market / official rates read on 25 November 2026 (see docs/sources/mbbs-in-china-for-central-asian-and-russian-speaking-students.md):
// National Bank of Kazakhstan (KZT per CNY), Central Bank of Uzbekistan (UZS per CNY), Bank of Russia (RUB per CNY).
$rate = ['date' => '25 November 2026', 'kzt' => 68.77, 'uzs' => 1799.17, 'rub' => 12.2767];
$round = fn (float $yuan, float $r, int $to) => number_format(round($yuan * $r, -$to));
$localRows = [];
foreach ([['First-year package', 49950, '7,000'], ['Each later year', 35950, '5,050'], ['Six years in total', 229700, '32,000']] as [$label, $yuan, $usd]) {
    $localRows[] = [
        $label,
        '¥' . number_format($yuan),
        'USD ' . $usd,
        'KZT ' . $round($yuan, $rate['kzt'], 4),
        'UZS ' . $round($yuan, $rate['uzs'], 6),
        'RUB ' . $round($yuan, $rate['rub'], 3),
    ];
}

return [
    'updated' => '2026-11-25',
    'title' => 'MBBS in China for Central Asian & Russian-Speaking Students',
    'description' => 'MBBS in China for students from Kazakhstan, Uzbekistan, Tajikistan, Russia and nearby: MOE list, X1 visa, fees in tenge, som and roubles, and what to check at home.',
    'keywords' => 'MBBS in China for Kazakhstan students, MBBS in China for Uzbek students, MBBS in China for Central Asian students, MBBS in China for Russian students, MBBS in China, study medicine in China in English, MBBS in China for Tajik students, MBBS in China for Kyrgyz students, nostrification of Chinese medical diploma, MOE listed MBBS, Xinjiang Medical University, медицина в Китае',
    'eyebrow' => 'Guide for students and parents in Central Asia and the Russian-speaking world',
    'h1' => 'MBBS in China for Central Asian and Russian-Speaking Students: Admission, Visa, Fees and Recognition at Home',
    'lead' => 'A plain-English guide for students and parents in Kazakhstan, Uzbekistan, Tajikistan, Kyrgyzstan, Turkmenistan, Azerbaijan, Russia, Armenia, Georgia and other Russian-speaking communities who are thinking about studying medicine in China in English. It covers the Ministry of Education list, eligibility, the visa, costs in tenge, som and roubles, and what each home authority says about foreign diplomas, with the date we read each rule. Xinjiang Medical University (XJMU) is used as one worked example.',
    'crumb' => 'MBBS in China for Central Asian and Russian-speaking students',
    'cta' => 'Talk to admissions',
    'sections' => [

        ['id' => 'overview', 'eyebrow' => 'Overview', 'h' => 'MBBS in China for Central Asian and Russian-speaking students: the short version', 'p' => [
            "MBBS stands for Bachelor of Medicine, Bachelor of Surgery. It is the six-year medical degree that Chinese universities offer to international students in English. Many families in Central Asia and in the Russian-speaking world ask whether it is a sensible choice. This guide gives the facts we can support, and it tells you clearly where you must check the rules of your own country.",
            "A medical degree has two lives. The first is in China: the university must be allowed to teach you, you need a visa, and you must pay the fees. The second is at home: your country decides whether, and how, a foreign medical diploma lets you work as a doctor. The first part is the same for everyone. The second part is different in every country, and it can change, so we send you to the official page and we say when we read it.",
            "We wrote this guide for readers who read English as a second language, so we use short sentences and we explain the special words. If a rule here matters for your decision, open the official page we name and read it yourself before you pay anything.",
        ], 'table' => ['caption' => 'The short version', 'head' => ['Question', 'Short answer'], 'rows' => [
            ['What is taught in English?', 'The MBBS at universities on the Chinese Ministry of Education (MOE) list for 2026–2027: 43 universities, 2,790 seats'],
            ['How long is the degree at XJMU?', 'Six years, including a one-year internship'],
            ['What does XJMU cost?', '¥49,950 in the first year (about USD 7,000) and ¥35,950 a year after that (about USD 5,050)'],
            ['Which visa do you need?', 'The X1 student visa, with the admission letter and the JW202 form from the university'],
            ['Is the degree accepted at home?', 'It depends on your country and its current rules. Read the official rule for your country below, and ask the ministry'],
        ]], 'img' => ['src' => 'assets/images/campus-img.webp', 'alt' => 'Aerial view of a large university building with a crowd seated outdoors in front of it at a ceremony, on the Xinjiang Medical University campus', 'caption' => 'Xinjiang Medical University campus, used in this guide as a worked example.']],

        ['id' => 'why-china', 'eyebrow' => 'Why China', 'h' => 'Why students in Central Asia and the Russian-speaking world look at China', 'p' => [
            "Families look at China for several reasons, and it helps to separate the reasons that are facts from the reasons that are hopes. A fact is something you can check. A hope is something an agent tells you.",
            "The first fact is that China publishes an official list of universities that may teach the MBBS to international students in English. You can check any university against it in a few minutes. We explain the list in the next section.",
            "The second fact is cost. A university that bundles the first-year costs into one package makes it easy to plan. At XJMU the first-year package is ¥49,950, and it includes the application charges, tuition, hostel, the student visa, health insurance, the JW202 visa letter and airport pickup. Later years cost ¥35,950 each. You will find the full table, converted into tenge, som and roubles, in the fees section.",
            "The third fact is that the teaching language of the medical subjects is English. This helps students from countries where Russian, not English, was the main foreign language, because it puts everyone in the class on the same footing. It does not remove the need to learn Chinese, which we explain in the language section.",
            "There are also honest reasons for caution. The degree is six years long. You will live far from home. You must learn Chinese for hospital work. And the rules for working at home as a doctor are set by your own country, not by China. A good decision weighs all of these.",
        ], 'sub' => [
            ['h' => 'Questions to ask yourself before you read on', 'list' => [
                'Can my family fund six years, with a reserve if costs rise?',
                'Am I ready to study in English and also learn Chinese?',
                'Do I know what my own country requires from a doctor who studied abroad?',
                'Do I want to work in my home country, in China, or somewhere else?',
            ]],
        ]],

        ['id' => 'moe-list', 'eyebrow' => 'Official list', 'h' => 'The Ministry of Education list of English-taught MBBS universities', 'p' => [
            "The Ministry of Education of China issues a notice that names the universities approved to enrol international students in the English-taught MBBS. The notice for 2026–2027 is numbered 教外司来〔2026〕94号 and was issued on 1 June 2026. It lists 43 universities and 2,790 seats in total.",
            "Under the same notice, a university that is not on the list may admit international students to clinical medicine only if the teaching is in Chinese, and it may not issue an English-language MBBS certificate. Teaching in two languages is not allowed as a way around this. For a student from Kazakhstan, Uzbekistan or Russia this is the first check to make, because it tells you whether the programme is approved on the Chinese side.",
            "Being on the list is not the same as being recognised in your country. These are two separate questions. The list answers the first one only. We say this again below, because marketing often mixes them.",
            "We do not rank universities or compare them on this page. The list has 43 names, and each one is a different city, climate and fee level. Our guide to the full list prints every name and seat number, and you can use the same checklist for each university you consider.",
        ], 'link' => ['moe-listed-mbbs-universities-in-china', 'See the MOE list of 43 universities with seats'], 'sub' => [
            ['h' => 'A warning about similar names', 'p' => [
                "Some universities have names that look alike. XJMU, Xinjiang Medical University, is in Urumqi and is number 8 on the list. Xinxiang Medical University is a different university in Henan province and is not on the 43-university list. Always check the exact name, the city and the number.",
            ]],
        ]],

        ['id' => 'xjmu', 'eyebrow' => 'Worked example', 'h' => 'Xinjiang Medical University as one worked example', 'p' => [
            "In the rest of this guide we use XJMU to show what a real application looks like, because we are its admissions office and can state its published facts exactly. You can use the same questions for any other university on the MOE list.",
            "XJMU is in Urumqi, the capital of the Xinjiang region. According to the reference article on Xinjiang that we read on 25 November 2026, the Xinjiang region has borders with Kazakhstan, Kyrgyzstan and Tajikistan, and also with Russia, Mongolia, Afghanistan, Pakistan and India. This is geography only. It does not tell you how long a journey from your city takes or what a ticket costs, so ask a travel agent for current routes.",
            "XJMU is number 8 on the 2026–2027 MOE list, with 60 seats. It is also ECFMG eligible. This matters only if you plan to look at the United States later, and in that case you must read the ECFMG's own rules. It teaches the MBBS in English, with 18 teaching hospitals and 95 internship hospitals.",
        ], 'table' => ['caption' => 'XJMU MBBS at a glance', 'head' => ['Item', 'Detail'], 'rows' => [
            ['City', 'Urumqi, Xinjiang'],
            ['MOE list 2026–2027', 'Number 8, 60 seats'],
            ['Language of teaching', 'English, with introductory Chinese in years 1 and 2 and HSK level 4 required by graduation'],
            ['Duration', '6 years including a 1-year internship'],
            ['Intakes', 'March/Spring intake and September/Fall intake'],
            ['Hospitals', '18 teaching hospitals and 95 internship hospitals'],
            ['CSCA test', 'Not required by XJMU'],
            ['First-year package', '¥49,950, about USD 7,000'],
            ['From the second year', '¥35,950 a year, about USD 5,050'],
        ]], 'img' => ['src' => 'assets/images/affil-hospt.webp', 'alt' => 'Tall hospital building with a red heart sculpture in front, labelled First Affiliated Hospital Xinjiang Medical University', 'caption' => 'The First Affiliated Hospital of Xinjiang Medical University.'], 'link' => ['xinjiang-medical-university', 'Read more about Xinjiang Medical University']],

        ['id' => 'eligibility', 'eyebrow' => 'Admission', 'h' => 'Who can apply: general rules and the XJMU rules', 'p' => [
            "Each Chinese university sets its own admission conditions, and they are published on its own pages. The conditions below are the published XJMU conditions. Use them as a model, and ask any other university for its own list in writing.",
        ], 'list' => $s['requirements'], 'sub' => [
            ['h' => 'Marks in science subjects', 'p' => [
                "XJMU asks for at least 60% in each of the science subjects, that is physics, chemistry and biology, in your school-leaving certificate or its equivalent. The requirement is for each subject, not for the average of all subjects. A high average does not help if one subject is below 60%.",
                "Schools in Kazakhstan, Uzbekistan, Russia and the other countries use different marking scales, such as five-point scales or percentage scales. We cannot tell you here how your own certificate maps onto 60%. XJMU's wording is the percentage in each science subject, so send a copy of your certificate to admissions and ask them to confirm in writing whether your marks meet the requirement before you pay any fee.",
            ]],
            ['h' => 'Age and health', 'p' => [
                "Applicants must be between 18 and 35 years old. You must also be in good health and free from communicable diseases, and you need a valid passport.",
            ]],
            ['h' => 'No entrance test at XJMU', 'p' => [
                "XJMU does not require the CSCA test, the Chinese standard test for international applicants. Other universities may ask for it or for their own tests, so check each university. A separate question is whether your home country asks anything from a student who goes abroad to study medicine. We did not find a rule of that kind that we can state, so check it with your ministry.",
            ]],
        ], 'link' => ['mbbs-in-china-eligibility-2027', 'Read the full eligibility guide']],

        ['id' => 'fees', 'eyebrow' => 'Cost', 'h' => 'Fees at XJMU, converted into tenge, som and roubles', 'p' => [
            "The fees are published in Chinese yuan. The yuan fee is the number that is fixed. The tenge, som and rouble amounts move every day, so treat them as a guide. For 2027 the fees are the same as for 2026.",
            "The first-year package is ¥49,950, which is about USD 7,000. It includes application processing, registration and admission charges, tuition, hostel accommodation, the student visa, health insurance and medical, the JW202 visa letter and airport pickup. From the second year the fee is ¥35,950 a year, about USD 5,050. Over six years the university fees come to about ¥229,700, about USD 32,000.",
        ], 'table' => [
            'caption' => 'XJMU MBBS fees in yuan, US dollars, Kazakhstani tenge, Uzbek som and Russian roubles (approximate, at ¥1 = KZT ' . $rate['kzt'] . ', UZS ' . number_format($rate['uzs'], 2) . ' and RUB ' . $rate['rub'] . ' on ' . $rate['date'] . ')',
            'head' => ['Fee', 'Chinese yuan', 'US dollars', 'Kazakhstani tenge', 'Uzbek som', 'Russian roubles'],
            'rows' => $localRows,
        ], 'sub' => [
            ['h' => 'Where the exchange rates come from', 'p' => [
                "The tenge rate is the official market rate published by the National Bank of Kazakhstan for 25 November 2026. The som rate is the official rate published by the Central Bank of Uzbekistan for the same day. The rouble rate is the official rate set by the Bank of Russia for the same day. We rounded tenge to the nearest 10,000, som to the nearest million and roubles to the nearest thousand.",
                "Banks and transfer services add a few percent for their own margin and fees, so you will pay more than these figures when you send money. A rate that is good today may be different next month. The amount that does not move is the yuan fee, so budget in yuan and convert on the day you pay.",
            ]],
            ['h' => 'What the package does not include', 'p' => [
                "Food, local transport, a mobile SIM and flights are your own costs. Typical monthly costs published for Urumqi are USD 150 to 250 for food, USD 10 to 20 for local transport and USD 10 to 15 for a SIM. Flights between your home country and Urumqi change with the season, so we publish no prices for them.",
            ]],
        ], 'link' => ['mbbs-in-china-fees-2027', 'Read the full MBBS in China fee guide']],

        ['id' => 'visa', 'eyebrow' => 'Visa and travel', 'h' => 'Visa and travel: the X1 visa, the JW202 form and visa-free travel', 'p' => [
            "An MBBS lasts six years, so you need the long-term study visa. China calls it the X1 visa. According to the visa instructions of the Chinese Embassy in Sweden (page dated 12 October 2026, read in November 2026), the X1 visa is for people who study in China for more than 180 days, and the X2 visa is for study of 180 days or less. A degree student is an X1 applicant.",
            "You cannot start the visa process alone. The embassy asks for two papers that come from the university: the admission letter and the JW201 or JW202 form. XJMU names the JW202 form in its published process. The visa process therefore starts with admission, not with the embassy.",
            "You apply for the visa in the country where you live, at the Chinese embassy, consulate or an official visa application centre that serves your address. The decision is made by the consular officer, and no university or agent can promise it.",
        ], 'img' => ['src' => 'assets/images/visa-process.webp', 'alt' => 'Diagram of five visa steps: finalising the university, applying for the documents, applying for the visa, a visa interview if applicable, and receiving the visa and preparing to depart', 'caption' => 'The visa starts only after the university has sent its papers.'], 'sub' => [
            ['h' => 'Visa-free entry is not a student visa', 'p' => [
                "China has visa-free arrangements for short visits with some countries, and they change from time to time. The National Immigration Administration of China publishes a table of mutual visa-exemption agreements. The version dated 14 April 2025, which we read on 25 November 2026, lists ordinary-passport arrangements for countries such as Kazakhstan, Georgia and Armenia, with a limit of 90 days in any 180 days. That table is about passports and does not mention study.",
                "A six-year degree is not a short visit. Do not plan to enter on a visa-free stay and then change to a study status. Apply for the X1 visa with the papers from the university, and read the page of the Chinese embassy for your own country.",
            ]],
            ['h' => 'What the embassy asks for: an example', 'p' => [
                "Embassies differ, so we give one example and its date. The Chinese Embassy in Azerbaijan published a visa page, shown as dated 11 May 2023, that lists for an X1 applicant the original and a photocopy of the admission letter, the study form JW201 or JW202, and proof of personal assets of at least USD 2,500 for a year or proof of a scholarship. That page may have changed since. Your own embassy sets the rule that applies to you, so read its current page and the date on it.",
            ]],
            ['h' => 'After you arrive', 'p' => [
                "The X1 visa is an entry document. According to the Chinese Embassy in Sweden (page dated 12 October 2026), an X1 holder must apply for a residence permit within 30 days of entering China. At XJMU the international student office helps new students with this. The yearly cost of the permit and the health examination is part of the later-year fee.",
            ]],
        ], 'link' => ['china-student-visa-for-mbbs', 'Read the full China student visa guide']],

        ['id' => 'structure', 'eyebrow' => 'Programme', 'h' => 'The six-year structure', 'p' => [
            "At XJMU the MBBS runs for six years, which is five years of study and a one-year internship. The internship is part of the degree. It is not an extra year that you pay for separately in your own country.",
            "A typical path looks like this. In the early years you study the basic medical sciences, together with introductory Chinese. Then the teaching moves towards clinical subjects. In the later years you spend more time in the affiliated hospitals, and the final year is a full clinical internship. XJMU lists 18 teaching hospitals and 95 internship hospitals for this work.",
            "If you come from a country where medical school begins with a different structure, for example a mix of pre-clinical and clinical years or a separate residency, do not assume the years match one for one. Ask each university for the curriculum in writing and compare it with what your own authority asks for.",
        ], 'img' => ['src' => 'assets/images/lab-img.webp', 'alt' => 'People in white laboratory coats working at long benches with bottles and equipment in a teaching laboratory', 'caption' => 'Basic medical sciences are taught first, in laboratories and classrooms.']],

        ['id' => 'language', 'eyebrow' => 'Language', 'h' => 'Language: English in class, Chinese at the bedside', 'p' => [
            "The medical subjects at XJMU are taught in English. Patients in the hospitals speak Chinese, so you also learn Chinese. Introductory Chinese is taught in years 1 and 2, and XJMU requires HSK level 4 by graduation. HSK is the official Chinese language test, and level 4 is an intermediate level.",
            "Russian is not the language of teaching. If you speak Russian, it helps you in your home community and with other students from the region, but you should not expect lectures, textbooks or exams in Russian. If you prefer to study medicine in Russian, a programme in a country that teaches in Russian may fit you better, and you can compare it fairly with the checklist in this guide.",
            "Start learning Chinese early. Students who delay it find the clinical years harder, because the work with patients depends on it. Ask admissions how many hours of Chinese are taught and what support is offered.",
        ], 'img' => ['src' => 'assets/images/language.webp', 'alt' => 'Classroom with a teacher standing in front of a whiteboard showing Chinese characters for Chinese course and students seated at desks', 'caption' => 'Chinese lessons run beside the medical subjects.'], 'link' => ['hsk-requirement-for-mbbs-in-china', 'Read about the HSK requirement']],

        ['id' => 'recognition', 'eyebrow' => 'At home', 'h' => 'Recognition and working at home: how to read the rules', 'p' => [
            "This is the part of the decision that matters most, and it is the part where web pages and agents make the most mistakes. A Chinese medical degree does not by itself give you the right to work as a doctor at home. Each country has its own steps. Usually there are two kinds of step. One step checks that your diploma is a real diploma from a real university, and this is often called recognition or nostrification. The other step checks that you are fit to treat patients, and this can be an exam, an assessment or an internship.",
            "Below we give what each official body says on its own pages, and the date on which we read it. Where we could not open an official page, we say so and we do not guess. Rules change. Read the page again in the month you apply.",
        ], 'sub' => [
            ['h' => 'What an official source looks like', 'p' => [
                "A good source is the website of a ministry, a state agency or a legal database that publishes the text of the rule with its number and date. A weak source is a blog, a forum post, a video or an agent. If someone says that your ministry approves XJMU or any other Chinese university, ask them to show you the page. We do not state that any ministry recognises or approves XJMU, because we did not find a page that says it.",
            ]],
            ['h' => 'Three questions for your ministry', 'list' => [
                'Does a Chinese medical diploma need recognition or nostrification before I can use it, and which body does it?',
                'After that, what must a doctor with a foreign diploma pass, such as an exam, an assessment or an internship?',
                'Does the ministry keep a list of universities whose diplomas are treated in a special way, and where is that list?',
            ]],
        ]],

        ['id' => 'russia', 'eyebrow' => 'Russia', 'h' => 'Russia: what Rosobrnadzor and Roszdravnadzor publish', 'p' => [
            "For Russia we read two official pages on 25 November 2026. The first is the page of Rosobrnadzor, the Federal Service for Supervision in Education and Science, on recognition of foreign education and qualifications. It says that recognition follows article 107 of the federal law on education and the Rosobrnadzor order No. 1443 of 26 July 2023. Foreign education is recognised without the procedure if an international treaty covers it or if it was obtained at an institution on a list set by the Government. Otherwise it is not recognised in Russia without going through the recognition procedure.",
            "The same page says that recognition gives two kinds of rights: academic rights, which allow further study in Russia, and professional rights, which allow work in a profession. The documents it names include the original diploma and its supplement, and a certified Russian translation of the diploma and the supplement that includes the seals. It states a state duty of 6,500 roubles and says that applications can be sent through the state services portal or the website nic.gov.ru, or by post. The page does not give a processing time and does not mention medical diplomas.",
            "The second page is from Roszdravnadzor, the Federal Service for Supervision in Healthcare, and is dated 26 December 2025. It says that since 1 January 2023 people who studied medicine, pharmacy or related fields abroad can practise in Russia only after passing specialist accreditation and after Roszdravnadzor has confirmed that their education meets Russian requirements. If the foreign diploma is not covered by an international agreement, it must first be recognised through Rosobrnadzor. Roszdravnadzor then reviews the application within 25 working days, and a positive decision lets the applicant apply to the Central Accreditation Commission.",
        ], 'sub' => [
            ['h' => 'What we did not verify for Russia', 'p' => [
                "We did not open the Government list of foreign institutions, so we cannot say whether any Chinese university is on it. We also did not verify the content of the accreditation exam. For these points, write to Rosobrnadzor, Roszdravnadzor or the Ministry of Health of Russia and ask for the current rule.",
            ]],
        ]],

        ['id' => 'kazakhstan', 'eyebrow' => 'Kazakhstan', 'h' => 'Kazakhstan: recognition, then certification', 'p' => [
            "For Kazakhstan we read the official legal texts that apply to a foreign diploma. Two orders matter. The Rules for recognition of documents on higher and postgraduate education were approved by the Minister of Science and Higher Education by order No. 268 of 12 June 2023. The text we read was the version updated to 12 August 2026. The Rules for certification of healthcare specialists were approved by the Minister of Health by order No. ҚР ДСМ-274 of 15 December 2020. The text we read was the version updated to 3 March 2026.",
            "As the recognition Rules are published, the National Centre for the Development of Higher Education decides the applications, and you can apply through the e-government portal or the state corporation. The reviewer checks that the document is genuine and that the issuing institution was authorised to teach when the document was issued. The Rules say that medical and pharmaceutical diplomas are not recognised on the basis of treaty provisions alone. They need an expert assessment, made with specialists that the health authority recommends. A document in a foreign language needs a notarised translation into Kazakh or Russian. The Rules give shorter time limits for a diploma that carries a legalisation or an apostille than for one that does not.",
            "As the certification Rules are published, a person who obtained a medical education outside Kazakhstan is admitted to certification only if the education has been recognised. The applicant must also pass the assessment of professional preparedness under the Rules for assessment of knowledge and skills. The result of certification is a certificate that admits a foreign-educated specialist to clinical practice.",
            "The Ministry of Science and Higher Education page on recognition says that even an apostilled foreign diploma still needs the recognition procedure. We read it on 25 November 2026.",
        ], 'sub' => [
            ['h' => 'What we did not verify for Kazakhstan', 'p' => [
                "Adilet, the official legal database, did not open properly in our tools, so we read the text of both orders on a legal-database mirror. Check the same orders on adilet.zan.kz before you rely on them. The Rules contain provisions that depend on the ranking of the issuing university and on special groups such as scholarship holders. We did not check how they apply to any Chinese university, and we state nothing about XJMU. Ask the National Centre or the Ministry of Health.",
            ]],
        ]],

        ['id' => 'other-countries', 'eyebrow' => 'Other countries', 'h' => 'Uzbekistan, Tajikistan, Kyrgyzstan and the other countries', 'p' => [
            "For Uzbekistan we found an official announcement, published on 14 January 2026 and updated on 22 January 2026, that explains how to apply for recognition and nostrification of education documents obtained abroad. You apply on the portal my.gov.uz, using the service called \"Recognition and nostrification of education documents obtained in a foreign state (confirmation of equivalence)\". The announcement points to a video guide and does not list the documents, and it does not mention medical diplomas.",
            "We did not find an official page of the Ministry of Health of Uzbekistan that says what a doctor with a foreign diploma must pass in order to work. The pages of the ministry that we opened on attestation did not mention foreign diplomas. So we state no rule for Uzbekistan beyond the recognition service. Check the current rules with the Ministry of Health and with the Ministry of Higher Education, Science and Innovation.",
            "For Tajikistan, Kyrgyzstan, Turkmenistan, Azerbaijan, Armenia and Georgia we could not open an official page on the recognition of a foreign medical diploma and the rules for foreign-educated doctors, so we state no rule. Check the current rules with the ministry of health and the ministry of education of your country, and ask them in writing.",
        ], 'table' => ['caption' => 'What this guide verified, by country, as read on 25 November 2026', 'head' => ['Country', 'Recognition of the diploma', 'Permission to practise as a doctor'], 'rows' => [
            ['Russia', 'Rosobrnadzor page read', 'Roszdravnadzor page read (dated 26 December 2025)'],
            ['Kazakhstan', 'Order No. 268 read (version to 12 August 2026)', 'Order No. ҚР ДСМ-274 read (version to 3 March 2026)'],
            ['Uzbekistan', 'General recognition service on my.gov.uz read', 'Not verified: check with the Ministry of Health'],
            ['Tajikistan, Kyrgyzstan, Turkmenistan', 'Not verified: check with the ministries', 'Not verified: check with the ministries'],
            ['Azerbaijan, Armenia, Georgia', 'Not verified: check with the ministries', 'Not verified: check with the ministries'],
        ]], 'sub' => [
            ['h' => 'Write the question down', 'p' => [
                "When you write to a ministry, put your questions in one short message. Name the university, the city and the programme, say that it is a six-year English-taught MBBS with a one-year internship, and ask which steps you must complete. Keep the reply. A written reply from the ministry is worth more than any summary on a website.",
            ]],
        ]],

        ['id' => 'documents', 'eyebrow' => 'Documents', 'h' => 'Documents, translation and legalisation', 'p' => [
            "Your documents will be read in three places: by the Chinese university, by the Chinese embassy, and later by your home authority. Prepare them carefully once.",
            "For the XJMU application you need the documents below.",
        ], 'list' => $s['documents'], 'sub' => [
            ['h' => 'Translation', 'p' => [
                "Ask the Chinese university which language versions of your documents it accepts. At home, the official bodies we read ask for translations of their own. Rosobrnadzor asks for a certified Russian translation that includes the seals. The Kazakh Rules ask for a notarised translation into Kazakh or Russian. Ask your authority which kind of translation it accepts, and keep the original documents safe.",
            ]],
            ['h' => 'Legalisation and apostille', 'p' => [
                "Legalisation and apostille are stamps that confirm a signature or a seal on a public document so that another country will trust it. The Kazakh recognition Rules we read give shorter time limits for a diploma with a legalisation or an apostille. Other bodies may ask for the same. We did not verify how each country treats a Chinese diploma, so ask your authority what it needs, and ask the Chinese university how it issues the diploma.",
            ]],
            ['h' => 'Names must match', 'p' => [
                "Your name must be spelled in the same way in the passport, the school certificate, the admission papers and the translations. Many countries in this region use more than one alphabet, so choose one spelling, follow your passport, and use it everywhere.",
            ]],
        ]],

        ['id' => 'apply', 'eyebrow' => 'How to apply', 'h' => 'How to apply to XJMU from Central Asia or Russia, step by step', 'p' => [
            "The application is the same for all international applicants. These are the published steps.",
        ], 'list' => [
            'Check that you meet the XJMU requirements, and settle the home-country questions with your ministry.',
            'Choose your intake, the March/Spring intake or the September/Fall intake, and plan to apply by the early deadline.',
            'Prepare your documents, record your self-introduction video and complete the medical report.',
            'Submit the application and pay the ¥1,000 application fee as instructed.',
            'After the Pre-Admission Letter, pay the ¥2,000 seat reservation deposit, which is refunded if the JW202 visa letter is rejected.',
            'Receive the admission letter and the JW202 form, apply for your X1 visa and book your flight.',
        ], 'table' => $s['calendar'], 'sub' => [
            ['h' => 'Check every letter', 'p' => [
                "When the admission letter and the JW202 form arrive, check your name, date of birth, nationality and passport number against your passport, letter by letter. A mistake is easy to fix now and hard to fix at the embassy.",
            ]],
        ], 'link' => ['mbbs-in-china-admission-calendar-2027', 'See the full admissions calendar']],

        ['id' => 'mistakes', 'eyebrow' => 'Avoid', 'h' => 'Common mistakes families make', 'p' => [
            "These mistakes are common and avoidable.",
        ], 'sub' => [
            ['h' => 'Mixing up the two approvals', 'p' => [
                "A university on the Chinese MOE list is approved to teach international students in English. That does not mean that your ministry has said anything about its graduates. Always ask both questions.",
            ]],
            ['h' => 'Relying on a general website', 'p' => [
                "Rules for foreign diplomas differ for Russia, Kazakhstan, Uzbekistan and the other countries, and they change. A page that talks about \"the CIS\" in general cannot be right for every country. Use the page of your own ministry.",
            ]],
            ['h' => 'Paying before you have written answers', 'p' => [
                "Do not pay a deposit until the university has confirmed in writing that you meet its requirements and what the fee covers, and until you have read your own country's rules.",
            ]],
            ['h' => 'Choosing a university outside the list', 'p' => [
                "Under the MOE notice, a university outside the list may not issue an English-language MBBS certificate. Check the name and the city against the list. Our guide on universities outside the list explains the risk.",
            ]],
            ['h' => 'Ignoring Chinese', 'p' => [
                "English is the language of the lectures, but Chinese is the language of the hospital. Start in the first semester.",
            ]],
        ], 'link' => ['non-moe-universities-in-china-mbbs-risks', 'Read about universities that are not on the MOE list']],

        ['id' => 'money', 'eyebrow' => 'Money and scams', 'h' => 'Money, scholarships and how to avoid scams', 'p' => [
            "Students from our region meet a lot of offers on social networks and messaging apps. Most are honest. Some are not. These rules protect you.",
            "Pay only to the university or to a payment channel that the university has confirmed in writing, and keep every receipt. Be careful with anyone who promises a visa, a scholarship or a seat. The consular officer decides on a visa, and scholarship places are limited. Never send money to a private person's account for a university fee.",
            "Two scholarships are listed in the published XJMU fee structure: the Chinese Government Scholarship and the Xinjiang Government Scholarship for students from neighbouring countries. The Chinese Government Scholarship pays 30,000 yuan a year to an undergraduate, and the Xinjiang Government Scholarship pays 10,000 yuan a year. Whether your country counts as a neighbouring country for this purpose, and how many places are open to you, are questions for admissions. Your own country may offer state support for study abroad, but we did not verify any such scheme, so ask your ministry.",
        ], 'table' => $s['scholarships'], 'link' => ['mbbs-in-china-scholarship-2027', 'Read the scholarship guide']],

        ['id' => 'life', 'eyebrow' => 'Daily life', 'h' => 'Daily life, climate and staying connected', 'p' => [
            "Everyday costs in Urumqi are modest, and the hostel is included in the fees. Winters can be cold and summers dry, so check the climate of any city before you choose it. Bring warm clothes when you arrive in cold months and buy a proper winter coat locally with the advice of senior students.",
            "Many families want to speak every day with their child. Video calls usually work well, but ask a current student about the apps that work reliably in China, because some foreign apps are not available there. Plan your visits home around the holidays and the residence-permit rules.",
        ], 'img' => ['src' => 'assets/images/mountains.webp', 'alt' => 'A blue mountain lake surrounded by snow-covered mountains and evergreen trees', 'caption' => 'Mountain scenery. Check the climate of any city before you choose it.'], 'link' => ['life-in-urumqi-for-international-students', 'Read the guide to life in Urumqi']],

        ['id' => 'next', 'eyebrow' => 'Next steps', 'h' => 'Your next steps, and what we did not verify', 'p' => [
            "First, read the official rule of your own country, using the pages named in this guide, and write down what it says and the date. Second, check your marks in each science subject against the 60% requirement and send a copy of your certificate to admissions. Third, ask admissions for the fee structure and the programme details in writing. Fourth, choose your intake and apply by the early deadline.",
            "To be clear about the limits of this guide: we verified the Russian and Kazakh rules named above, and the general recognition service in Uzbekistan. We did not verify the rules of the Ministry of Health of Uzbekistan, or any rule of Tajikistan, Kyrgyzstan, Turkmenistan, Azerbaijan, Armenia or Georgia. We did not verify any country's list of recognised universities, or how any ministry treats a Chinese university. For these points the answer is: check the current rules with the ministry.",
        ], 'link' => ['mbbs-in-china-2027', 'Read the main MBBS in China guide']],
    ],
    'faqs' => [
        ['Can students from Kazakhstan and Uzbekistan study MBBS in China in English?', 'Yes. Universities on the Chinese Ministry of Education list for 2026–2027 may teach international students the MBBS in English. The list has 43 universities and 2,790 seats. XJMU is number 8, with 60 seats.'],
        ['Is a Chinese MBBS accepted in Kazakhstan?', 'The Kazakh rules we read on 25 November 2026 say a foreign medical diploma must be recognised and that the holder must pass an assessment and be certified. Medical diplomas need an expert assessment. Check the current orders with the ministries.'],
        ['What must Russian citizens do with a Chinese medical diploma?', 'Rosobrnadzor publishes the recognition procedure and Roszdravnadzor, on a page dated 26 December 2025, says foreign-educated doctors need specialist accreditation and a Roszdravnadzor decision. Check the current rules before you apply.'],
        ['What about Uzbekistan, Tajikistan, Kyrgyzstan and other countries?', 'Uzbekistan has an online recognition service on my.gov.uz for foreign education documents. We could not verify the medical practice rules there or the rules of the other countries, so check the current rules with your ministries.'],
        ['Do I need a visa, or can I enter visa-free?', 'A six-year degree needs the X1 student visa, with the admission letter and the JW202 form from the university. Visa-free entry is for short visits, and the official tables we read do not list study.'],
        ['How much does XJMU cost in tenge, som and roubles?', 'At the rates of 25 November 2026 the first-year package of ¥49,950 is about KZT 3.44 million, UZS 90 million and RUB 613,000. Each later year of ¥35,950 is about KZT 2.47 million, UZS 65 million and RUB 441,000. Banks add a few percent.'],
        ['What marks do I need?', 'XJMU asks for at least 60% in each of physics, chemistry and biology in your school-leaving certificate or equivalent, an age between 18 and 35, good health and a valid passport. Ask admissions how your marking scale maps to this.'],
        ['Is the MBBS taught in Russian?', 'No. The medical subjects are taught in English. Chinese is also taught, with introductory Chinese in years 1 and 2 and HSK level 4 required by graduation.'],
        ['Is there an entrance test?', 'XJMU does not require the CSCA test. Other universities may have their own tests, and your home country may have its own conditions, so check both.'],
        ['When can I apply?', 'There are two intakes. The September/Fall intake opens on 1 March, with an early deadline of 30 June. The March/Spring intake opens on 1 September, with an early deadline of 15 December.'],
        ['Is XJMU recognised by my ministry?', 'We did not find an official page of any home ministry that says it recognises or approves XJMU, so we do not state it. XJMU is on the Chinese MOE list. Ask your ministry for its current rule in writing.'],
        ['Is the 60% rule for the average or for each subject?', 'It is for each science subject: physics, chemistry and biology. A high average does not replace a low mark in one of them.'],
        ['How do I avoid scams?', 'Check the university on the MOE list, pay only to the university or a channel it confirms in writing, keep receipts and do not pay for a promised visa or scholarship.'],
    ],
    'related' => ['mbbs-in-china-2027', 'mbbs-in-china-fees-2027', 'mbbs-in-china-eligibility-2027', 'china-student-visa-for-mbbs', 'moe-listed-mbbs-universities-in-china', 'non-moe-universities-in-china-mbbs-risks', 'life-in-urumqi-for-international-students'],
];
