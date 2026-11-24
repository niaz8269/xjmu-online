<?php

$s = require __DIR__ . '/_shared.php';

// Exchange rates in the naira and cedi table: official central-bank reference rates for 24 November 2026,
// the latest published on the day this guide was written. Sources are listed in docs/sources/mbbs-in-china-for-african-students.md.
$fxAf = ['date' => '24 November 2026', 'ngn' => 209.0959, 'ghs' => 1.7784];
$rows = [];
foreach ([['First-year package', 49950, '7,000'], ['Each later year', 35950, '5,050'], ['Six years in total', 229700, '32,000']] as [$label, $yuan, $usd]) {
    $rows[] = [$label, '¥' . number_format($yuan), 'USD ' . $usd, 'NGN ' . number_format(round($yuan * $fxAf['ngn'], -4)), 'GHS ' . number_format(round($yuan * $fxAf['ghs'], -3))];
}

return [
    'updated' => '2026-11-25',
    'title' => 'MBBS in China for African Students 2027 | Fees, Visa, Licensing',
    'description' => 'MBBS in China for African students, with Nigerian and Ghanaian examples: the MOE English-taught list, fees in yuan, naira and cedis, X1 visa, and home licensing checks.',
    'keywords' => 'MBBS in China for African students, MBBS in China for Nigerian students, MBBS in China for Ghanaian students, MBBS in China, study MBBS in China, MBBS in China fees in naira, MDCN assessment examination, China student visa Nigeria, MOE listed MBBS, English taught MBBS, Xinjiang Medical University',
    'eyebrow' => 'Guide for African students and parents',
    'h1' => 'MBBS in China for African Students 2027: Fees, Visa and Licensing at Home',
    'lead' => 'A plain guide for students and parents in Nigeria, Ghana, Kenya, Uganda, Tanzania, Cameroon, Zambia, Zimbabwe, Botswana, Ethiopia, Rwanda and other African countries who want to study MBBS in China: what the Ministry of Education list means, what the visa involves, what the fees look like in naira and cedis, and what your own medical council requires before you can practise at home. Xinjiang Medical University is used as a worked example, and every home-country rule is quoted from the regulator with the date we read it.',
    'crumb' => 'MBBS in China for African students',
    'cta' => 'Talk to admissions',
    'sections' => [

        ['id' => 'overview', 'eyebrow' => 'Overview', 'h' => 'MBBS in China for African students: what you need to know first', 'p' => [
            "Studying MBBS in China is a six-year decision with two halves. The first half is China: whether the university is allowed to teach international students in English, what it requires, what it costs and how the visa works. The second half is home: what your country's medical council requires of a doctor who trained abroad, because that council, not the Chinese university, decides whether and how you can be licensed to practise in your own country.",
            "Most guides spend all their space on the first half. This one gives both halves equal weight. Where a fact is published by an official body, such as the Chinese Ministry of Education, a Chinese consulate or a medical council, we say which body published it and the date we read it. Where we could not open an official page, we say so and tell you to check with the regulator instead of guessing, because a wrong rule in a guide can cost a family years and a great deal of money.",
            "Throughout the guide we use Xinjiang Medical University (XJMU) in Urumqi as a worked example, because its requirements, fees and intakes are published and we can state them exactly. XJMU is number 8 on the Ministry of Education list for 2026-2027, with 60 seats. The general facts, about the list, the visa, the language and the licensing questions, apply to any English-taught MBBS in China, and you should check the same things at any university you consider.",
        ], 'img' => ['src' => 'assets/images/lab-img.webp', 'alt' => 'A student in a white coat working at a laboratory bench with bottles and glassware', 'caption' => 'The first years of an MBBS are laboratory-based basic medical sciences.']],

        ['id' => 'checklist', 'eyebrow' => 'Checklist', 'h' => 'Six questions to settle before you pay anyone', 'p' => [
            "Write the answers to these six questions down, in this order. If any answer is no or unknown, stop and find out before you send money.",
        ], 'list' => [
            'Is the university on the current Ministry of Education (MOE) list of institutions approved to teach the English-taught MBBS to international students?',
            'Do you meet the university\'s admission requirements, including the marks in each science subject and the age limits?',
            'Do you know the full six-year cost in yuan, and what is included in each fee?',
            'Do you know which visa you need and which papers only the university can give you?',
            'Have you read your own medical council\'s current rules for graduates of foreign universities, including any examination you would have to pass?',
            'Will the money go only to the university or to a payment channel the university has confirmed in writing?',
        ], 'sub' => [
            ['h' => 'Why the order matters', 'p' => [
                "Questions one to four are about China. Question five is about home, and it is the one families most often leave until too late. Question six protects you from the problems described near the end of this guide. If you can answer all six from official sources and written replies, you have done most of the work that matters.",
            ]],
        ]],

        ['id' => 'why-china', 'eyebrow' => 'Why China', 'h' => 'Why African students consider studying MBBS in China', 'p' => [
            "Students and parents across Africa look at medicine abroad for a simple reason: the number of people who want to study medicine is larger than the number of places at home, and a degree abroad is one way to keep the goal alive. China is one of several destinations families compare. We do not publish statistics about how many African students go to China or how many succeed, because we cannot verify them, and we do not rank countries.",
            "What we can describe are the features of the China route that are written down and checkable. The Ministry of Education publishes a list of universities approved to teach the MBBS in English to international students. Fees at XJMU are published in yuan and are the same for 2027 as for 2026. Teaching is in English, with Chinese taught alongside it, and the programme includes a clinical internship. Those facts let you plan, and they let you compare any university with any other on the same checklist.",
            "What the China route does not do is remove the work at home. Wherever you study, you will have to meet your own country's rules for practising there. The rest of this guide shows you how to find those rules and how to read them.",
        ]],

        ['id' => 'moe-list', 'eyebrow' => 'The list', 'h' => 'The Ministry of Education English-taught list and why it matters', 'p' => [
            "Since the notice of 1 June 2026, the Ministry of Education has listed 43 universities for the 2026-2027 intake, with 2,790 seats between them, as institutions approved to teach the English-taught MBBS to international students. The notice is numbered 教外司来〔2026〕94号. The full list, with seats, is on our page about the MOE list, and a table of it is at the end of this section.",
            "The list matters because of what our own page on non-listed universities explains: under the notice, a university that is not on the list may admit international students to clinical medicine only in Chinese, and may not issue an English-language MBBS certificate. If an agent offers you an English-taught MBBS at a university you cannot find on the list, ask for the university's entry in the notice in writing before you pay anything.",
            "Being on the list is a Chinese requirement. It is not a promise about what your home council will accept. Those are two separate questions, and the licensing section below deals with the second.",
        ], 'link' => ['moe-listed-mbbs-universities-in-china', 'See the full MOE list with seats'], 'table' => ['caption' => 'MOE list 2026-2027: the first ten entries, from the full list of 43 (' . $s['moeSource'] . ')', 'head' => ['No.', 'University', 'Seats'], 'rows' => array_slice($s['moeRows'], 0, 10)], 'img' => ['src' => 'assets/images/campus-img.webp', 'alt' => 'A large university building with a flag, and a crowd seated in rows in front of it during an open-air ceremony', 'caption' => 'A campus building and an open-air ceremony at Xinjiang Medical University.']],

        ['id' => 'eligibility', 'eyebrow' => 'Admission', 'h' => 'Eligibility: what a Chinese university asks for, with XJMU as the example', 'p' => [
            "Each university sets its own admission requirements, so read the page of the university you are applying to. As an example, these are the published requirements for the MBBS at XJMU.",
        ], 'list' => $s['requirements'], 'sub' => [
            ['h' => 'Marks in each science subject', 'p' => [
                "The requirement is at least 60% in each of the science subjects, which means physics, chemistry and biology on their own, not an average across them. Check each subject on your certificate. If one subject is below 60%, a strong average does not make up for it.",
            ]],
            ['h' => 'Which certificate counts', 'p' => [
                "XJMU's published wording is a senior high-school certificate with science subjects, \"HSSC, A-Level or equivalent\". Students in Africa hold different certificates, and this guide does not claim to know how the admissions office maps each one. Send your certificate and your subject grades to admissions and ask, in writing, whether they meet the requirement. Keep the reply.",
            ]],
            ['h' => 'Age and entrance tests', 'p' => [
                "Applicants must be between 18 and 35 years old. XJMU does not require the CSCA test, and the published requirements do not list another entrance test for admission. A different university may ask for something else, and your own country may have its own rules, so confirm both.",
            ]],
        ], 'link' => ['mbbs-in-china-eligibility-2027', 'Read the full eligibility guide']],

        ['id' => 'language', 'eyebrow' => 'Language', 'h' => 'Language: English in the lecture room, Chinese outside it', 'p' => [
            "The MBBS at an MOE-listed university is taught in English, but you will live in a Chinese-speaking city and, in the later years, meet patients and hospital staff who speak Chinese. At XJMU, introductory Chinese is taught in years 1 and 2 and the Chinese proficiency test HSK level 4 is required by graduation.",
            "Treat the language as part of the course and not as an extra. Students who start in the first semester find the clinical years easier. Our page on the HSK requirement explains the test in plain language.",
            "If you come from a country where English is not the main language, such as Ethiopia or the French-speaking parts of Cameroon, do not assume the university's English requirement is the only language question. Ask the admissions office what it expects, and also check whether your medical council asks for proof of English-language ability from foreign-trained graduates. Some councils do, and the Nigerian notice quoted below is one that mentions an English paper for some graduates.",
        ], 'link' => ['hsk-requirement-for-mbbs-in-china', 'Read the HSK requirement guide'], 'img' => ['src' => 'assets/images/language.webp', 'alt' => 'A classroom with desks, a projector and a whiteboard showing the Chinese words for Chinese language course', 'caption' => 'A Chinese language class: Chinese is taught alongside the English-medium MBBS.']],

        ['id' => 'fees', 'eyebrow' => 'Cost', 'h' => 'Fees: the yuan figures that are fixed', 'p' => [
            "At XJMU the fees are published in Chinese yuan and are the same for 2027 as for 2026. The first-year package is ¥49,950, about USD 7,000. Each later year is ¥35,950, about USD 5,050. Over six years the university fees come to about ¥229,700, about USD 32,000. The first-year package covers the items below, so you do not pay them again separately in year one.",
        ], 'table' => $s['feeFirstYear'], 'link' => ['mbbs-in-china-fees-2027', 'See the full MBBS in China fee guide']],

        ['id' => 'yearly', 'eyebrow' => 'Cost', 'h' => 'Yearly fees and living costs', 'p' => [
            "These are the published per-year fees from the second year onward, and the living costs the university publishes for Urumqi. Other universities will publish different figures, and living costs depend on the city, so compare each university on the same basis.",
        ], 'table' => $s['feeYearly']],

        ['id' => 'living', 'eyebrow' => 'Cost', 'h' => 'Everyday costs in Urumqi', 'p' => [
            "Food, local transport and a mobile SIM are paid by you. Flights, your own pocket money, books and any travel home are not included in the published fees, so add them to your own budget.",
        ], 'table' => $s['living']],

        ['id' => 'fees-local', 'eyebrow' => 'Cost in your currency', 'h' => 'The fees in naira and Ghanaian cedis', 'p' => [
            "Families in Nigeria and Ghana pay in naira and cedis, so here are the same fees in your currency. The yuan fee is what is fixed. The naira and cedi amounts move with the exchange rate every day, so treat them as a guide, not as a quote.",
            "The Central Bank of Nigeria published an official rate of ¥1 = NGN " . number_format($fxAf['ngn'], 2) . " for " . $fxAf['date'] . ", and the Bank of Ghana published a mid rate of ¥1 = GHS " . number_format($fxAf['ghs'], 4) . " for the same day. We read both on 25 November 2026. Banks and transfer services add a margin, often a few percent, so your real cost will be somewhat higher than the table shows. In Nigeria especially, the rate you can actually get can differ from the official rate, so ask your bank for its quote on the day you pay.",
            "For Kenyan shillings, Zambian kwacha, Botswana pula and other currencies, take the yuan amount and multiply it by your bank's current yuan rate. We do not print a table for every currency, because we would be publishing numbers that go out of date within days. Before every payment, ask the university in writing what the payment covers and where to send it. We come back to this in the section on scams.",
        ], 'table' => [
            'caption' => 'XJMU MBBS fees in yuan, US dollars, Nigerian naira and Ghanaian cedis (approximate, at ¥1 = NGN ' . number_format($fxAf['ngn'], 2) . ' per the Central Bank of Nigeria and ¥1 = GHS ' . number_format($fxAf['ghs'], 4) . ' per the Bank of Ghana, rates of ' . $fxAf['date'] . '; naira rounded to the nearest 10,000 and cedis to the nearest 1,000)',
            'head' => ['Fee', 'Chinese yuan', 'US dollars', 'Nigerian naira', 'Ghanaian cedis'],
            'rows' => $rows,
        ]],

        ['id' => 'visa', 'eyebrow' => 'Visa', 'h' => 'The student visa: X1, JW202 and where to apply from Africa', 'p' => [
            "An MBBS lasts six years, so you need the long-term student visa, called the X1 visa, which the Chinese consulate in Lagos describes as issued to people who intend to study in China for more than 180 days (page dated September 2024, read on 25 November 2026). You cannot apply for it first. The consulate asks for papers that come from the university, which are the admission notice and the study confirmation form, JW201 or JW202. At XJMU the form is the JW202. The visa process therefore starts with admission, not with the embassy.",
            "Visa rules and fees are set by each Chinese embassy or consulate and can change, so read the page for the country where you apply. Below are the pages we could read. We do not state a visa fee, because it depends on the country and changes.",
        ], 'sub' => [
            ['h' => 'Applying from Nigeria', 'p' => [
                "The Chinese Consulate General in Lagos publishes guidelines for Nigerian applicants (page dated September 2024, read on 25 November 2026). For university students it lists a copy of the admission notice, a copy of the JW201 or JW202 form, proof of the final academic qualification, proof of funds and a physical examination form. It states that proof of funds is US$2,500 per year or a scholarship certificate, and that the qualification must be authenticated by the Ministry of Education in Nigeria. It also says X1 holders must apply for a residence permit within 30 days of entry.",
                "The same page says regular applications go through the China Visa Application Centre in Abuja or Lagos, and that processing normally takes four working days, with the final decision resting with the visa officer. Read the page itself before you apply, because the list of documents and the fees may have changed since it was written.",
            ]],
            ['h' => 'Applying from Kenya', 'p' => [
                "The Chinese Embassy in Kenya announced in a notice dated 19 March 2025 that online visa application services would start on 31 March 2025 (read on 25 November 2026). The steps are: register on the visa centre website in Nairobi, complete the online form and upload the documents, and, when the status shows that the passport is to be submitted, go to the centre in person with the passport and documents, give fingerprints unless exempt, and pay the fees. After approval you return to collect the passport and visa.",
            ]],
            ['h' => 'Applying from Ghana and other countries', 'p' => [
                "We could not read a current visa page for Ghana or the other countries in this guide, so we do not state their document lists. Look for the page of the Chinese embassy or consulate that covers your country, or of the Chinese Visa Application Service Center it names, and read the list for X1 study visas. If you live outside your country of citizenship, embassy pages usually ask for proof that you are staying legally where you apply.",
            ]],
            ['h' => 'After arrival', 'p' => [
                "The X1 visa lets you enter China. Your permission to stay for the programme is the residence permit, which you apply for after arrival. At XJMU the residence permit fee of ¥800 and the physical examination fee of ¥350 are listed in the yearly fees from the second year.",
            ]],
        ], 'link' => ['china-student-visa-for-mbbs', 'Read the full China student visa guide'], 'img' => ['src' => 'assets/images/visa-process.webp', 'alt' => 'A diagram of the visa steps: finalising the university, applying with relevant documents, applying for the visa, visa interview if applicable, and receiving the visa and preparing for departure', 'caption' => 'The visa comes after admission. A visa interview may be asked for in some cases.']],

        ['id' => 'six-years', 'eyebrow' => 'Programme', 'h' => 'The six-year structure', 'p' => [
            "At XJMU the MBBS lasts six years, including a one-year internship. Other universities can structure the course differently, so ask each one for its curriculum in writing. In outline, the years at XJMU run like this.",
        ], 'sub' => [
            ['h' => 'Years 1 and 2: foundations and language', 'p' => [
                "The early years cover the basic medical sciences and introductory Chinese. Use them to build study habits and to learn enough Chinese for daily life.",
            ]],
            ['h' => 'Years 3 to 5: from the basic sciences to the hospital', 'p' => [
                "Teaching moves towards clinical subjects, and you spend more time in the teaching hospitals, where Chinese becomes more useful. XJMU has 18 teaching hospitals and 95 internship hospitals.",
            ]],
            ['h' => 'Year 6: internship', 'p' => [
                "The final year is a clinical internship. This matters at home, because several African councils have their own rules about internships. Compare what the council requires with what the university's internship provides, and ask the university to confirm in writing.",
            ]],
        ], 'img' => ['src' => 'assets/images/affil-hospt.webp', 'alt' => 'A tall hospital building under a blue sky, labelled as the First Affiliated Hospital of Xinjiang Medical University', 'caption' => 'The First Affiliated Hospital, one of the hospitals where clinical teaching takes place.']],

        ['id' => 'licensing', 'eyebrow' => 'Back home', 'h' => 'Returning home: licensing rules in African countries', 'p' => [
            "This is the part to read twice. In every country, the national medical council decides who may practise there. A foreign degree does not give you the right to practise by itself, and rules differ from one country to the next. We give below the rules we could read on the regulator's own website, with the date, and for the others we tell you to check with the regulator.",
            "Two warnings. First, rules change, so read the council's current notice before you rely on anything here. Second, we do not write that any council \"recognises\" or \"approves\" a particular university, because we found no official page that says so about XJMU. Whether your council accepts a particular degree is a question for the council, and you should ask it in writing.",
        ], 'sub' => [
            ['h' => 'Nigeria: the MDCN assessment examination', 'p' => [
                "As published by the Medical and Dental Council of Nigeria (MDCN) in its notice on the 2026 programmes for foreign-trained medical and dental graduates, read on 25 November 2026: the assessment examination is for candidates trained in countries other than Nigeria whose training institutions are currently listed in the World Directory of Medical Schools (WDOMS). Candidates must hold degrees of approved training institutions, which the MDCN verifies through a global verification process. XJMU is listed in WDOMS, as stated on our page about WDOMS, and you can check the entry at wdoms.org.",
                "The notice also says candidates must show evidence of eligibility to practise in their country of training, namely a current practising licence and a Certificate of Good Standing from that country's medical regulatory authority, not from the training institution. It does not say how a graduate of a Chinese university should meet this. Write to the MDCN and ask what it accepts in that case, and keep the reply.",
                "The notice gives the format as a computer-based test of multiple-choice questions, clinical examinations with long and short cases or an objective structured clinical examination, and an English-language paper for graduates whose language of instruction was not English. For the 2026 sittings it lists fees of N250,000 for Nigerian candidates and N400,000 for non-Nigerian candidates, which are non-refundable, and requires an upload of stamped verification through EPIC or Dataflow, five credit passes in English Language, Mathematics, Physics, Chemistry and Biology at not more than two sittings, and the medical degree certificate. The notice scheduled the last 2026 sitting for 28 and 29 October 2026, so look for the MDCN's next notice for later dates.",
            ]],
            ['h' => 'Ghana: check with the Medical and Dental Council', 'p' => [
                "We could not open the Medical and Dental Council of Ghana's own website on the day we wrote this guide, so we do not state its rules. Check the current rules with the Medical and Dental Council of Ghana, including whether graduates of foreign universities must pass a registration examination, how many attempts are allowed and how your qualifications are verified. News reports on this subject have changed over time and have mentioned court proceedings about examinations, which is another reason to read the council's own notices and not summaries.",
            ]],
            ['h' => 'Kenya: check with the Kenya Medical Practitioners and Dentists Council', 'p' => [
                "The Kenya Medical Practitioners and Dentists Council (KMPDC) publishes a page for foreign-trained doctors, but we could not open it when we tried, so we do not state its rules. Check the current rules with the KMPDC, including which examinations it requires of foreign-trained graduates before an internship and before registration.",
            ]],
            ['h' => 'Zimbabwe: the council registration assessment', 'p' => [
                "As published by the Medical and Dental Practitioners Council of Zimbabwe (MDPCZ) on its council examination page, read on 25 November 2026: the council holds registration examinations for foreign-trained medical and dental applicants. The assessments are scheduled in March, June and October of each year. For medicine, there are four papers, paediatrics, obstetrics and gynaecology, medicine and surgery, each worth 50 marks and lasting one hour. The page does not list eligibility or retry rules, so ask the council for them.",
            ]],
            ['h' => 'Tanzania: the Medical Council of Tanganyika', 'p' => [
                "As published by the Medical Council of Tanganyika on its provisional registration page, read on 25 November 2026: provisional registration for internship requires completed undergraduate training and a pass in the pre-internship examination conducted by the council. Applicants who trained outside Tanzania must provide a certificate of evaluation of academic qualifications from the Tanzania Commission for Universities, and non-Tanzanians must submit a certified copy of their passport.",
            ]],
            ['h' => 'Zambia: the Health Professions Council of Zambia', 'p' => [
                "As published by the Health Professions Council of Zambia (HPCZ) on its practitioner registration page, read on 25 November 2026: practitioners trained outside Zambia apply for temporary registration, which is valid for two years under an approved supervisor. The documents listed include proof of passing the HPCZ professional licensure examination, proof of verification of the professional qualification from the Zambia Qualifications Authority, a certificate of good standing and a medical examination report from a government facility in Zambia. To move to full registration, the page says the practitioner must have worked at least 24 months under temporary registration. The page does not say how to sit the licensure examination, so ask the council.",
            ]],
            ['h' => 'Uganda, Cameroon, Botswana, Ethiopia, Rwanda and other countries', 'p' => [
                "We did not verify the rules of the medical councils or ministries in these countries, so we do not state them. Find the official website of your country's medical council or the ministry that regulates doctors, read its current notice for graduates of foreign universities, and write to it with a clear question: what must a graduate of an English-taught MBBS from a Chinese university pass or provide to be registered here? Ask for the answer in writing.",
            ]],
        ], 'link' => ['after-mbbs-in-china', 'Read what happens after an MBBS in China']],

        ['id' => 'prepare', 'eyebrow' => 'Planning', 'h' => 'Planning for the licensing examination from the first year', 'p' => [
            "The notices above have one thing in common: an examination, an assessment or a period of supervised practice stands between a foreign-trained graduate and full registration. Do not treat this as a surprise for the end of the sixth year. It is a goal for the first year, because the content comes from the whole programme.",
            "Keep your notes by subject, revise a little in every semester and join a group of students from your own country who study in China. They will know which notices to follow and how earlier graduates prepared. Use the regulator's own notices to find the format and dates, and work backwards from the date you expect to sit.",
            "Keep your own file of documents that the council may ask for years later: certificates, transcripts, proof of the internship and any letters from the university. The notices quoted above all ask for verified documents.",
        ], 'img' => ['src' => 'assets/images/clinic.webp', 'alt' => 'A person in a white coat examining the leg of a patient lying on a treatment couch in a clinic', 'caption' => 'Clinical skills are practised with patients in the later years.']],

        ['id' => 'apply', 'eyebrow' => 'How to apply', 'h' => 'How to apply to XJMU from Africa: a worked example', 'p' => [
            "The application at XJMU is the same for all international applicants, wherever they live. The steps below are the published ones.",
        ], 'list' => [
            'Check that you meet the requirements, and that you know what your own medical council requires.',
            'Choose your intake, and plan to apply by the early deadline.',
            'Prepare your documents, record your self-introduction video and complete the medical report.',
            'Submit the application and pay the ¥1,000 application fee as instructed.',
            'After the Pre-Admission Letter, pay the ¥2,000 seat reservation deposit, which is refunded if the JW202 visa letter is rejected.',
            'Apply for your student visa with the admission letter and the JW202, and book your flight.',
        ], 'table' => $s['calendar'], 'sub' => [
            ['h' => 'Intakes', 'p' => [
                "There are two intakes, the September/Fall intake and the March/Spring intake. Applications for the September/Fall intake open on 1 March, with an early deadline of 30 June and a regular deadline of 15 August. Applications for the March/Spring intake open on 1 September, with an early deadline of 15 December and a regular deadline of 15 January. Early deadlines are recommended for scholarship applications.",
            ]],
        ], 'link' => ['mbbs-in-china-admission-calendar-2027', 'See the full admissions calendar']],

        ['id' => 'documents', 'eyebrow' => 'Documents', 'h' => 'Documents to prepare', 'p' => [
            "These are the documents XJMU lists. Start early, because some of them take time to obtain.",
        ], 'list' => $s['documents'], 'sub' => [
            ['h' => 'Documents that take longest', 'p' => [
                "The police non-criminal record certificate and the medical report usually take the longest, and the Lagos consulate's page asks for an authenticated qualification as well. Ask the relevant offices how long each document takes in your country, and start at least a few months before the deadline.",
            ]],
            ['h' => 'Passport', 'p' => [
                "Check that your passport is valid for long enough, because the consulate page we read asks for at least six months of validity and blank visa pages. If you renew the passport after applying, tell the admissions office at once, because your papers use the passport details you applied with.",
            ]],
        ], 'img' => ['src' => 'assets/images/counselling.webp', 'alt' => 'A group of people bending over a table to read maps and papers together', 'caption' => 'Read every document with care. Ask for answers in writing.']],

        ['id' => 'mistakes', 'eyebrow' => 'Avoid', 'h' => 'Common mistakes African families make', 'p' => [
            "These are patterns that cause trouble. Each can be avoided.",
        ], 'sub' => [
            ['h' => 'Not checking the home rules', 'p' => [
                "Reading the council's notice should come before paying a deposit. Check it for yourself, not through an agent.",
            ]],
            ['h' => 'Assuming a listing is the same as recognition', 'p' => [
                "Being on the MOE list, or in WDOMS, is not the same as being approved by your own council. Ask the council what it requires.",
            ]],
            ['h' => 'Comparing only the fee', 'p' => [
                "Compare what each fee includes, the length of the programme and the teaching hospitals, not only the lowest figure.",
            ]],
            ['h' => 'Leaving Chinese until later', 'p' => [
                "Start in the first semester. The clinical years are easier for students who did.",
            ]],
            ['h' => 'Ignoring exchange rates', 'p' => [
                "A weaker local currency raises the cost of a yuan fee. Keep a reserve, and do not plan around the lowest rate you have seen.",
            ]],
        ]],

        ['id' => 'scams', 'eyebrow' => 'Safety', 'h' => 'Money and scams: warning signs', 'p' => [
            "Most agents and universities are honest, but a family that sends money abroad should know the real warning signs. We name them plainly, because they are the ones that cause the most harm.",
        ], 'list' => [
            'The university is not on the current MOE list, but the offer says the MBBS is taught in English.',
            'You are asked to pay into a personal account, a mobile wallet or a cryptocurrency address instead of the university or a payment channel the university has confirmed in writing.',
            'There is no JW202 or admission letter, or the agent says you can enter China on a tourist or short-term visa and change it later. The X1 visa is the visa for long-term study.',
            'You are promised a visa, a scholarship or a licence at home. No university or agent can guarantee a visa, because the consular officer decides, and the scholarships listed here have limited places.',
            'You are pressed to pay today, or told the fee will rise tomorrow, and you are not given the fee structure in writing.',
        ], 'sub' => [
            ['h' => 'What to do', 'p' => [
                "Ask the university's admissions office, in writing, to confirm any person or channel that asks you for money. Keep every receipt. If you doubt a payment request, stop and check it with the university first.",
            ]],
        ], 'link' => ['non-moe-universities-in-china-mbbs-risks', 'Read about universities that are not on the MOE list']],

        ['id' => 'scholarship', 'eyebrow' => 'Scholarships', 'h' => 'Scholarships and ways to lower the cost', 'p' => [
            "Two scholarships are listed in the published XJMU fee structure. Places are limited, so apply by the early deadline, and ask admissions whether you are eligible. The amounts below are stipends in yuan per person per year.",
        ], 'table' => $s['scholarships']],

        ['id' => 'next', 'eyebrow' => 'Next steps', 'h' => 'Your next steps', 'p' => [
            "Write down which council or ministry regulates doctors in your country, and read its current notice for graduates of foreign universities. Check your marks in physics, chemistry and biology against the 60% requirement. Ask admissions for the fee structure and the programme details in writing, and then choose your intake and apply by the early deadline.",
            "If you have a question this guide does not answer, ask. A clear question usually gets a clear answer, and the answer is worth keeping.",
        ], 'video' => $s['video']],
    ],
    'faqs' => [
        ['Is MBBS in China good for African students?', 'It can be, if the university is on the Ministry of Education list, you meet its requirements, you can fund the six years, and you have checked your own medical council\'s rules for graduates of foreign universities. We cannot judge it for you, and we do not publish success statistics.'],
        ['Can Nigerian students study MBBS in China?', 'Yes, subject to the admission requirements. At XJMU you need at least 60% in each science subject and an age of 18 to 35. Separately, the MDCN publishes an assessment examination for graduates trained abroad, so read its current notice.'],
        ['Is MBBS in China valid in Nigeria?', 'The MDCN notice we read on 25 November 2026 says its assessment examination is for graduates of institutions listed in WDOMS, who must show a current practising licence and a Certificate of Good Standing from their country of training\'s regulator. Ask the MDCN how this applies to graduates of Chinese universities.'],
        ['What happens after MBBS in China for Ghanaian students?', 'We could not read the Medical and Dental Council of Ghana\'s own website, so we do not state its rules. Check the current requirements for graduates of foreign universities with the council before you apply.'],
        ['How much does MBBS in China cost in naira?', 'At XJMU the first-year package is ¥49,950, which is about NGN 10,440,000 at the Central Bank of Nigeria rate of 24 November 2026. Later years are ¥35,950 each. Banks add a margin, so ask for a quote.'],
        ['How much does MBBS in China cost in cedis?', 'At the Bank of Ghana mid rate of 24 November 2026, the XJMU first-year package of ¥49,950 is about GHS 89,000, and each later year of ¥35,950 is about GHS 64,000. The yuan fee is fixed. The cedi amount moves with the rate.'],
        ['Which visa do African students need for MBBS in China?', 'The X1 visa, for study of more than 180 days. You apply with the admission letter and the JW201 or JW202 form that the university sends you. Read the page of the Chinese embassy or consulate for your country, because the details differ.'],
        ['Is the MBBS in China taught in English?', 'At universities on the Ministry of Education list, yes. At XJMU, Chinese is also taught in years 1 and 2, and HSK level 4 is required by graduation.'],
        ['Do I need an entrance exam to study MBBS in China?', 'XJMU does not require the CSCA test and lists no entrance test in its published requirements. Another university may differ, and your own council may have its own rules.'],
        ['How long is the MBBS in China?', 'At XJMU it is six years including a one-year internship. Ask other universities for their programme length in writing.'],
        ['When are the intakes?', 'There are two, the March/Spring intake and the September/Fall intake. Applications for the September/Fall intake open on 1 March and for the March/Spring intake on 1 September.'],
        ['How do I avoid scams when applying?', 'Check the university on the MOE list, pay only to the university or a channel it confirms in writing, never pay a personal account, insist on a JW202 and admission letter, and keep every receipt.'],
    ],
    'related' => ['mbbs-in-china-2027', 'mbbs-in-china-fees-2027', 'mbbs-in-china-eligibility-2027', 'china-student-visa-for-mbbs', 'moe-listed-mbbs-universities-in-china', 'non-moe-universities-in-china-mbbs-risks', 'hsk-requirement-for-mbbs-in-china'],
];
