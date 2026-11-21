<?php

$s = require __DIR__ . '/_shared.php';

// City and climate group for each university on the MOE list, in the same order as $moeList in _shared.php.
$cityByName = [
    'Jilin University' => ['Changchun', 'A'], 'China Medical University' => ['Shenyang', 'A'], 'Dalian Medical University' => ['Dalian', 'B'],
    'Capital Medical University' => ['Beijing', 'B'], 'Tianjin Medical University' => ['Tianjin', 'B'], 'Shandong University' => ['Jinan', 'B'],
    'Fudan University' => ['Shanghai', 'C'], 'Xinjiang Medical University' => ['Urumqi', 'A'], 'Nanjing Medical University' => ['Nanjing', 'C'],
    'Jiangsu University' => ['Zhenjiang', 'C'], 'Wenzhou Medical University (incl. overseas campus)' => ['Wenzhou', 'C'], 'Zhejiang University' => ['Hangzhou', 'C'],
    'Wuhan University' => ['Wuhan', 'C'], 'Huazhong University of Science and Technology' => ['Wuhan', 'C'], "Xi'an Jiaotong University" => ["Xi'an", 'B'],
    'Southern Medical University' => ['Guangzhou', 'D'], 'Jinan University' => ['Guangzhou', 'D'], 'Guangxi Medical University' => ['Nanning', 'D'],
    'Sichuan University' => ['Chengdu', 'C'], 'Chongqing Medical University' => ['Chongqing', 'C'], 'Harbin Medical University' => ['Harbin', 'A'],
    'Beihua University' => ['Jilin City', 'A'], 'Jinzhou Medical University' => ['Jinzhou', 'A'], 'Qingdao University' => ['Qingdao', 'B'],
    'Hebei Medical University' => ['Shijiazhuang', 'B'], 'Ningxia Medical University' => ['Yinchuan', 'A'], 'Tongji University' => ['Shanghai', 'C'],
    'Shihezi University' => ['Shihezi', 'A'], 'Southeast University' => ['Nanjing', 'C'], 'Yangzhou University' => ['Yangzhou', 'C'],
    'Nantong University' => ['Nantong', 'C'], 'Soochow University' => ['Suzhou', 'C'], 'Ningbo University' => ['Ningbo', 'C'],
    'Fujian Medical University' => ['Fuzhou', 'D'], 'Anhui Medical University' => ['Hefei', 'C'], 'Xuzhou Medical University' => ['Xuzhou', 'B'],
    'Zhengzhou University' => ['Zhengzhou', 'B'], 'Guangzhou Medical University' => ['Guangzhou', 'D'], 'Shantou University' => ['Shantou', 'D'],
    'Kunming Medical University' => ['Kunming', 'E'], 'Southwest Medical University' => ['Luzhou', 'C'], 'Xiamen University' => ['Xiamen', 'D'],
    'Shanghai Jiao Tong University' => ['Shanghai', 'C'],
];
$groupName = [
    'A' => 'Very cold winters',
    'B' => 'Cold winters, hot summers',
    'C' => 'Cool winters, hot humid summers',
    'D' => 'Mild winters, hot humid summers',
    'E' => 'Mild all year',
];
$cityRows = [];
foreach ($s['moeRows'] as $row) {
    if ($row[0] === '' || !isset($cityByName[$row[1]])) {
        continue;
    }
    [$city, $g] = $cityByName[$row[1]];
    $cityRows[] = [$row[1], $city, $groupName[$g], $row[2]];
}

$seatsByGroup = [];
foreach ($s['moeRows'] as $row) {
    if ($row[0] === '' || !isset($cityByName[$row[1]])) {
        continue;
    }
    $g = $cityByName[$row[1]][1];
    $seatsByGroup[$g]['n'] = ($seatsByGroup[$g]['n'] ?? 0) + 1;
    $seatsByGroup[$g]['seats'] = ($seatsByGroup[$g]['seats'] ?? 0) + (int) $row[2];
}
$seatChartRows = [];
foreach ($groupName as $g => $name) {
    $seatChartRows[] = [$name . ' (' . $seatsByGroup[$g]['n'] . ' universities)', [$seatsByGroup[$g]['seats']]];
}

$tempChartRows = [
    ['Harbin', [-18, 23]], ['Changchun', [-14, 23]], ['Urumqi', [-12, 24]], ['Shenyang', [-11, 25]], ['Yinchuan', [-7, 24]],
    ['Dalian', [-4, 24]], ['Beijing', [-3, 27]], ['Tianjin', [-3, 27]], ["Xi'an", [0, 27]], ['Jinan', [0, 27]],
    ['Shanghai', [4, 29]], ['Wuhan', [4, 29]], ['Hangzhou', [4, 29]], ['Chengdu', [6, 26]], ['Chongqing', [8, 29]],
    ['Kunming', [9, 20]], ['Xiamen', [13, 29]], ['Guangzhou', [14, 29]],
];

return [
    'updated' => '2026-11-17',
    'title' => 'Compare MBBS Universities in China 2027 | City, Weather, Fees',
    'description' => 'Compare MOE listed MBBS universities in China by city, weather, cost of living, fees, seats and daily life, with a scorecard to choose the right one for you.',
    'keywords' => 'best MBBS university in China, compare MBBS universities in China, MBBS universities in China list, best city for MBBS in China, MBBS in China weather, cheapest MBBS in China, MBBS in China fees comparison, MOE listed MBBS universities in China, English taught MBBS in China, MBBS in China 2027, MBBS in China',
    'eyebrow' => 'University comparison guide',
    'h1' => 'Compare MBBS Universities in China: City, Weather, Fees and Daily Life',
    'lead' => 'There are 43 universities on the 2026–2027 Ministry of Education list. This guide shows how to compare them fairly: by city and climate, cost of living, fees, seats, distance from home and daily life, so you can choose with a scorecard instead of an advertisement.',
    'crumb' => 'Compare MBBS universities in China',
    'cta' => 'Ask admissions',
    'sections' => [

        ['id' => 'overview', 'eyebrow' => 'Overview', 'h' => 'How to compare MBBS universities in China', 'p' => [
            "Search for the best MBBS university in China and you will find dozens of lists, each with a different order. Most of them rank by whatever the agency selling the seat happens to represent. This guide does not rank universities. It shows you the questions that actually separate one choice from another, and gives you the facts we can stand behind.",
            "The starting point is approval. In June 2026 the Ministry of Education (MOE) issued notice 教外司来〔2026〕94号, which lists 43 universities allowed to teach the English taught MBBS to international students in 2026–2027, with 2,790 places between them. Every university in this guide is on that list. A university that is not on it is not a safe choice, however cheap.",
            "After approval, five things decide how the next six years feel: the climate of the city, the cost of living there, the tuition and what it includes, the distance from home, and the size and style of the city. A student from a hot country may struggle with a long northern winter. A student on a tight budget may find a coastal megacity too expensive. A student who wants halal food and a quiet routine may prefer a smaller city. None of these has a single right answer, so the job is to match them to you.",
            "One honest note before we start. We are the admissions office of Xinjiang Medical University (XJMU) in Urumqi, number 8 on the list, with 60 places for international students. We print exact fees only for XJMU, because those are ours and we can verify them. For other universities we describe how to compare and where to find the current figure, instead of repeating numbers from agency websites that are often years out of date.",
        ], 'img' => ['src' => 'assets/images/counselling.webp', 'alt' => 'Student and parents comparing MBBS universities in China by city, climate and fees during counselling', 'caption' => 'Compare on the same few factors for every university you consider.']],

        ['id' => 'approval', 'eyebrow' => 'Start here', 'h' => 'Step one: only compare universities on the MOE list', 'p' => [
            "The MOE list is the filter that comes before every other comparison. It is the official record of which Chinese universities may teach the English taught MBBS to international students. Our MOE listed MBBS universities page prints the full table of 43 universities and seats. Below, the same universities are grouped by city and climate so you can see the geography at a glance.",
            "Check three things for any university you are considering. First, that its name appears on the notice. Second, that the programme is the English taught MBBS or clinical medicine for international students, not a different course. Third, that the university's own international admissions page, not only an agent, confirms the fees and deadlines for the year you plan to apply. Names on the list can be similar to names of other institutions, so match the full name.",
        ], 'link' => ['moe-listed-mbbs-universities-in-china', 'See the full MOE list of 43 universities'], 'table' => ['caption' => 'MOE listed universities by city and climate group (2026–2027)', 'head' => ['University', 'City', 'Climate group', 'Seats'], 'rows' => $cityRows], 'charts' => [['caption' => 'MOE places for international MBBS students by climate group, 2026–2027', 'unit' => '', 'min' => 0, 'max' => 1200, 'series' => ['Seats'], 'rows' => $seatChartRows, 'note' => 'Seats are summed from the MOE notice of 1 June 2026; the total is 2,790.']]],

        ['id' => 'weather', 'eyebrow' => 'Weather', 'h' => 'Compare by weather: five climate groups across the 43 cities', 'p' => [
            "China is large, and the weather you live in for six years depends on where you go. The 43 universities fall into five rough climate groups. The group names in the table above use these labels, and the table below gives approximate average temperatures for January and July in well-known cities. They are rounded long-term monthly averages, intended to show the pattern, not to predict a particular week. Always check a current forecast before you pack.",
        ], 'table' => ['caption' => 'Approximate average temperature by city, rounded (°C)', 'head' => ['City', 'Climate group', 'January', 'July'], 'rows' => [
            ['Harbin', 'Very cold winters', 'about −18', 'about 23'],
            ['Changchun', 'Very cold winters', 'about −14', 'about 23'],
            ['Urumqi', 'Very cold winters', 'about −12', 'about 24'],
            ['Shenyang', 'Very cold winters', 'about −11', 'about 25'],
            ['Yinchuan', 'Very cold winters', 'about −7', 'about 24'],
            ['Dalian', 'Cold winters, hot summers', 'about −4', 'about 24'],
            ['Beijing', 'Cold winters, hot summers', 'about −3', 'about 27'],
            ['Tianjin', 'Cold winters, hot summers', 'about −3', 'about 27'],
            ["Xi'an", 'Cold winters, hot summers', 'about 0', 'about 27'],
            ['Jinan', 'Cold winters, hot summers', 'about 0', 'about 27'],
            ['Shanghai', 'Cool winters, hot humid summers', 'about 4', 'about 29'],
            ['Wuhan', 'Cool winters, hot humid summers', 'about 4', 'about 29'],
            ['Hangzhou', 'Cool winters, hot humid summers', 'about 4', 'about 29'],
            ['Chengdu', 'Cool winters, hot humid summers', 'about 6', 'about 26'],
            ['Chongqing', 'Cool winters, hot humid summers', 'about 8', 'about 29'],
            ['Kunming', 'Mild all year', 'about 9', 'about 20'],
            ['Xiamen', 'Mild winters, hot humid summers', 'about 13', 'about 29'],
            ['Guangzhou', 'Mild winters, hot humid summers', 'about 14', 'about 29'],
        ]], 'charts' => [['caption' => 'January and July average temperature by city (°C, approximate)', 'unit' => '', 'min' => -20, 'max' => 32, 'series' => ['January', 'July'], 'rows' => $tempChartRows, 'note' => 'Rounded long-term monthly averages, for pattern only. Check a current forecast before you pack.']], 'sub' => [
            ['h' => 'Group A: very cold winters (north-east and north-west)', 'p' => [
                "Harbin, Changchun, Shenyang, Jilin City, Jinzhou, Urumqi, Shihezi and Yinchuan have long winters with temperatures well below freezing for months. The air is often dry, and buildings are heated indoors, so life inside is warm. Summers are short and pleasant to hot. Students from tropical countries need proper winter clothing and a few weeks to adjust. The reward is clear skies, low humidity and, in many of these cities, lower living costs.",
                "Urumqi belongs here. It has a dry continental climate, with a cold snowy winter and a hot dry summer. Our Life in Urumqi guide explains clothing, hostel heating and the first-month routine.",
            ]],
            ['h' => 'Group B: cold winters, hot summers (northern plain and Shandong)', 'p' => [
                "Beijing, Tianjin, Dalian, Jinan, Qingdao, Shijiazhuang, Xi'an, Zhengzhou and Xuzhou have a real winter with frost and some snow, but it is shorter and less severe than in Group A. Summers are hot, and the main rains fall in July and August. Coastal Dalian and Qingdao are cooler in summer and have more sea breeze. Spring in the north can be dusty, and air quality varies by season.",
            ]],
            ['h' => 'Group C: cool winters, hot humid summers (Yangtze region and the south-west basin)', 'p' => [
                "Shanghai, Nanjing, Hangzhou, Suzhou, Wuhan, Hefei, Chengdu, Chongqing and neighbouring cities have winters that are cool and damp rather than freezing, with indoor heating less common than in the north, and summers that are hot, humid and long. Several of these cities have a reputation for rainy, grey weather in winter. If you dislike humidity, check the July figures carefully.",
            ]],
            ['h' => 'Group D: mild winters, hot humid summers (south and south-east coast)', 'p' => [
                "Guangzhou, Shantou, Xiamen, Fuzhou and Nanning have the warmest winters on the list. You can wear light jackets in January. The trade-off is long hot and humid summers, and a typhoon season on the coast between roughly July and September. For students from South Asia or Africa, this is the group whose weather feels most familiar.",
            ]],
            ['h' => 'Group E: mild all year (Kunming)', 'p' => [
                "Kunming sits on a high plateau, so the summers are comfortably cool and the winters are mild, with a lot of sunshine. It is often described as a spring-like city. It is also the only university from this group on the list.",
            ]],
        ], 'img' => ['src' => 'assets/images/mountains.webp', 'alt' => 'Snow-capped Tianshan mountains near Urumqi, an example of a cold-winter city for MBBS students in China', 'caption' => 'Cold-winter cities such as Urumqi suit students who can plan for the season.']],

        ['id' => 'cost-of-living', 'eyebrow' => 'Cost of living', 'h' => 'Compare by cost of living: big city versus smaller city', 'p' => [
            "Tuition is only part of the cost of studying medicine in China. Food, local transport, a phone plan, clothes, travel home and small emergencies add up over six years, and they depend on the city more than on the university. Published student cost guides generally place Beijing and Shanghai at the top, with typical monthly living costs of around ¥3,500 to ¥5,500 for an international student, and smaller cities lower. Those guides are written by agencies and are not official, so treat them as a pattern, not a quote.",
            "A useful way to think about it is three tiers. The first tier is the large coastal and capital cities, Beijing, Shanghai, Guangzhou and similar: highest rents and restaurant prices, most choice, and most international food. The second tier is large regional capitals such as Wuhan, Chengdu, Xi'an, Hangzhou, Nanjing, Chongqing, Shenyang and Changchun: moderate costs with the services of a big city. The third tier is smaller cities and regional centres: the lowest costs, a quieter life and fewer international restaurants.",
            "At XJMU, our published budget for food is USD 150 to 250 a month, with local transport at USD 10 to 20 and a mobile SIM at USD 10 to 15. Hostel accommodation is part of the fee, at ¥4,500 a year after the first year. That is the figure we can verify. For any other university, ask for a similar line-by-line budget from a current student, not only from the admissions office.",
        ], 'table' => $s['living'], 'img' => ['src' => 'assets/images/localbazar.webp', 'alt' => 'A local bazaar in Urumqi where students buy halal food, fruit and daily supplies', 'caption' => 'Local markets keep food costs low in smaller cities such as Urumqi.'], 'sub' => [
            ['h' => 'What changes with the city', 'list' => [
                "Rent, if you live off campus: the largest difference between cities, but most first-year students stay in university hostels.",
                "Restaurants and delivery: canteen meals are cheap everywhere, but eating out in a first-tier city costs more.",
                "Transport: metro systems in large cities are cheap but you will use them often. Smaller cities rely on buses and taxis.",
                "Travel home: the flight to your home country varies by city and season. A city with an international airport and direct flights can save a lot over six years.",
            ]],
        ]],

        ['id' => 'fees', 'eyebrow' => 'Fees', 'h' => 'Compare by fees: what a tuition number really tells you', 'p' => [
            "Published tuition for English taught MBBS in China varies a great deal between universities. Public listings put it anywhere from about ¥21,000 to about ¥48,000 a year, and you will see both lower and higher figures quoted. Those listings come from agencies and are often years out of date, so we do not copy them here. Use them only to understand that the range is wide, then ask each university for its current figure.",
            "A tuition number is only comparable when you know what sits behind it. Two universities can show the same tuition and cost very different amounts once accommodation, insurance, the residence permit and the medical examination are added. Some universities bundle these into a first-year package. Others charge them separately each year. Before you compare, put every university on the same footing: the first-year total and the yearly total after that.",
            "For XJMU, the September 2026 first-year package is ¥49,950, about USD 7,000, and it includes application processing, tuition, hostel accommodation, the student visa, health insurance and medical, the JW202 visa letter and airport pickup. From the second year the total is ¥35,950 a year, about USD 5,050. Over six years, the total comes to about ¥229,700, which is about USD 32,000. The full breakdown is in our MBBS in China fees guide.",
        ], 'table' => $s['feeFirstYear'], 'link' => ['mbbs-in-china-fees-2027', 'Read the full MBBS in China fees guide'], 'charts' => [['caption' => 'XJMU cost per year, RMB ¥ (2027 intakes, unchanged from 2026; six-year total about ¥229,700)', 'unit' => '', 'min' => 0, 'max' => 55000, 'series' => ['Fee per year'], 'rows' => [['Year 1 (package)', [49950]], ['Year 2', [35950]], ['Year 3', [35950]], ['Year 4', [35950]], ['Year 5', [35950]], ['Year 6', [35950]]], 'note' => 'Year 1 includes application processing, visa, insurance and medical. Living costs and flights are extra.']], 'sub' => [
            ['h' => 'A comparison sheet you can copy', 'p' => [
                "Build one row per university and fill each column from the university's own current notice or from written answers. Leave a cell blank if you do not have a figure: a blank is more honest than a guess.",
            ], 'list' => [
                "First-year total and what it includes",
                "Yearly total from the second year",
                "Whether accommodation, insurance, residence permit and medical are inside the fee",
                "Application fee and seat reservation deposit, and whether they are refundable",
                "Whether fees are fixed for the six years or may change",
                "The scholarships the university offers to international students",
            ]],
        ], 'img' => ['src' => 'assets/images/adm-img.webp', 'alt' => 'Admissions desk where international MBBS students receive a written fee breakdown for China', 'caption' => 'Ask for the fee breakdown in writing for every university.']],

        ['id' => 'distance', 'eyebrow' => 'Distance and travel', 'h' => 'Compare by distance from home and travel time', 'p' => [
            "Distance matters because you will fly home for holidays, and sometimes in an emergency. China is vast, and the difference between the nearest and the furthest city is several hours in the air. Cities in the north-west are the closest to Pakistan, Central Asia and parts of the Middle East. Cities on the east coast are further, but have the busiest international airports and the most direct connections.",
            "From Islamabad, Urumqi is about 2 hours 50 minutes by direct flight, which makes it one of the closest cities on the list for students from Pakistan. For a student from the Indian subcontinent, Africa or South-East Asia, a large coastal hub may be easier. When you compare, check three things: whether there is a direct flight to your home city, how often it flies, and what a return ticket usually costs. Over six years, a ticket you can afford twice a year is worth more than a university that looks good on paper.",
        ]],

        ['id' => 'daily-life', 'eyebrow' => 'Daily life', 'h' => 'Compare by daily life: food, community and campus', 'p' => [
            "Medical study is intense, so the quality of daily life matters. The three things students mention most are food, community and the campus itself.",
        ], 'sub' => [
            ['h' => 'Halal food', 'p' => [
                "Halal food is easy to find in Xinjiang and Ningxia, where many people are Muslim, and in some historic Muslim districts in other cities. In large cities there are halal restaurants and shops, but you may need to travel to reach them. In smaller cities without a large Muslim population, you may rely on the canteen, on cooking in your hostel, or on a small number of restaurants. Ask each university what the canteen offers and where students buy halal meat.",
            ]],
            ['h' => 'Community', 'p' => [
                "A student from a small community often gets the most support from seniors from their own country. Before you commit, ask how many international students are in the university, how many come from your country, and whether there is a student association that helps new arrivals. A friendly senior who meets you at the airport removes a lot of stress in the first week.",
            ]],
            ['h' => 'Campus and hostel', 'p' => [
                "Look at how many students share a hostel room, whether there is heating in winter and air conditioning in summer, how the canteen works, and how far the hostel is from classrooms and the teaching hospital. A short, safe walk between hostel, class and hospital saves time every day for six years.",
            ]],
        ], 'img' => ['src' => 'assets/images/campus1.webp', 'alt' => 'Campus of Xinjiang Medical University where international MBBS students live and study in Urumqi', 'caption' => 'Campus layout, hostel and canteen shape your daily routine.']],

        ['id' => 'clinical', 'eyebrow' => 'Clinical training', 'h' => 'Compare by teaching hospitals and clinical training', 'p' => [
            "The second half of an MBBS is learned at patients' bedsides, so the teaching hospitals matter as much as the lecture halls. When you compare universities, ask which hospitals take international students, how big they are, and how rotations are arranged. A programme with a large affiliated hospital and clear rotation rules is better than a famous name with vague arrangements.",
            "Every university on the list teaches the medical subjects in English, but patients speak Chinese, so clinical years rely on Chinese. That includes XJMU, where introductory Chinese is taught in years 1 and 2 and HSK-4 is required by graduation. Ask any university you compare how much Chinese is taught, who supports you on the wards, and what happens if you struggle with the language.",
            "On this point, the figures for XJMU are confirmed: the university has 18 teaching hospitals and 95 internship hospitals. When you compare other universities, ask each one for its current figures in writing, because numbers copied from old brochures are often out of date.",
        ], 'link' => ['english-taught-mbbs-in-china', 'How English taught MBBS works in China']],

        ['id' => 'scorecard', 'eyebrow' => 'Decision tool', 'h' => 'A scorecard to choose between two or three universities', 'p' => [
            "Pick two or three universities that are on the MOE list and that survive your first filter on budget and climate. Score each one from 1 to 5 on the factors below. Give each factor a weight from 1 to 3 based on how much it matters to you, multiply, and add up. The point is not the final number. It is that writing down your own weights stops you from being steered by whichever consultant speaks most confidently.",
        ], 'table' => ['caption' => 'Sample scorecard: replace the weights with your own', 'head' => ['Factor', 'Weight (1 to 3)', 'University A', 'University B', 'University C'], 'rows' => [
            ['On the MOE list and programme confirmed', '3', '', '', ''],
            ['Total six-year cost', '3', '', '', ''],
            ['Climate you can live with', '2', '', '', ''],
            ['Distance and flights home', '2', '', '', ''],
            ['Halal food and community', '2', '', '', ''],
            ['Teaching hospitals and rotations', '3', '', '', ''],
            ['Scholarship chances', '1', '', '', ''],
            ['Total', '', '', '', ''],
        ]], 'img' => ['src' => 'assets/images/lab-img.webp', 'alt' => 'Medical laboratory at Xinjiang Medical University used in the practical training of MBBS students', 'caption' => 'Teaching facilities and clinical access belong in your scorecard too.']],

        ['id' => 'profiles', 'eyebrow' => 'Examples', 'h' => 'Which city suits which student? Five typical profiles', 'p' => [
            "These profiles are not recommendations of a particular university. They show how the same facts lead different students to different groups of cities.",
        ], 'sub' => [
            ['h' => 'The budget-first student', 'p' => ["Looks at the six-year total first, then at the city. A third-tier city with a low cost of living and bundled fees often wins. Ask about everything the fee does not include."]],
            ['h' => 'The warm-weather student', 'p' => ["Dislikes cold. Group D and Kunming are the most comfortable, and Group C is workable if humidity does not bother you. This student should avoid a cold-winter city unless they are prepared to buy winter clothing and adjust."]],
            ['h' => 'The student who wants to be close to home', 'p' => ["Chooses by flight time. For students from Pakistan, a city in the north-west is the nearest. For students from elsewhere, a city with an international airport and a direct flight to home usually wins."]],
            ['h' => 'The student who wants halal food and a quiet routine', 'p' => ["Looks first at Xinjiang and Ningxia, where halal food is part of everyday life, and then checks what the canteen and nearby restaurants offer in any other city."]],
            ['h' => 'The student who wants a global big-city experience', 'p' => ["Accepts higher costs for the chance to live in Beijing, Shanghai or Guangzhou. This student should budget more and check whether the hostel is on or near campus."]],
        ], 'img' => ['src' => 'assets/images/festivals.webp', 'alt' => 'International students celebrating a festival together at Xinjiang Medical University in Urumqi', 'caption' => 'Community and festivals matter as much as the city name.']],

        ['id' => 'xjmu', 'eyebrow' => 'Where XJMU fits', 'h' => 'Where Xinjiang Medical University fits in the comparison', 'p' => [
            "XJMU is on the MOE list at number 8, with 60 places for international students. It sits in Group A, so winters are cold and summers are hot and dry. Its strengths on the factors above are a published and bundled fee, a first-year package of ¥49,950 and ¥35,950 a year after that, hostel accommodation included in the fee, easy access to halal food, and a short direct flight from Islamabad.",
            "Its trade-offs are equally clear. The winter is long and cold, Urumqi is far from the Chinese coast, and the city is smaller and quieter than the first-tier cities. A student who wants a global metropolis, or who cannot live with a northern winter, may be better served elsewhere. We would rather you chose the right city than the wrong one.",
            "If you want to see what a normal week looks like, read our Life in Urumqi guide, and check the admissions calendar below for the next intake.",
        ], 'table' => $s['calendar'], 'link' => ['life-in-urumqi-for-international-students', 'Read the Life in Urumqi guide'], 'video' => $s['video']],

        ['id' => 'questions', 'eyebrow' => 'Questions to ask', 'h' => 'Questions to ask every university before you decide', 'p' => [
            "Send the same questions to every university and agent on your shortlist, and keep the answers in writing. If a university cannot answer a question clearly, treat that as information.",
        ], 'list' => [
            "Is the university on the 2026–2027 Ministry of Education list, and is the programme the English taught MBBS?",
            "What is the first-year total and the yearly total after that, and what is included in each?",
            "Which application fees and deposits are refundable, and when?",
            "How many international students are in the MBBS year group, and how many are from my country?",
            "Which teaching hospitals do international students use, and how are rotations arranged?",
            "How much Chinese is taught, and what level is required by graduation?",
            "What is the hostel like: room sharing, heating, cooling, laundry and internet?",
            "What halal options are there on campus and nearby?",
            "Which scholarships are open to international students, and what are the deadlines?",
            "Can I speak to a current student from my country?",
        ]],

        ['id' => 'mistakes', 'eyebrow' => 'Avoid these', 'h' => 'Common mistakes when comparing universities', 'p' => [
            "Most regrets come from a short list of avoidable mistakes.",
        ], 'list' => [
            "Choosing from a ranking you cannot explain. Many lists rank by commission, not quality.",
            "Comparing tuition only, and ignoring accommodation, insurance, residence permit and medical costs.",
            "Choosing by famous city name without checking the climate, the cost and the distance from home.",
            "Trusting old fee tables. Figures from a few years ago are common on agency websites.",
            "Skipping the clinical question. Ask how many hospitals, how big and how rotations work.",
            "Never speaking to a student who is already there.",
            "Leaving the home-country licensing rules until after you enrol. Read what your national regulator requires first.",
        ]],

        ['id' => 'next', 'eyebrow' => 'Next steps', 'h' => 'What to do next', 'p' => [
            "Make a short list of two or three universities from the MOE list, fill in the scorecard with your own weights, and send each university the same written questions. Read the guide for your country if you want the home-licensing context, and check the fees and scholarship guides if budget is the deciding factor. If Urumqi is on your list, our admissions team will answer your questions in writing so you can compare our answers with those of other universities.",
        ], 'link' => ['mbbs-in-china-scholarship-2027', 'Read the scholarship guide']],
    ],
    'faqs' => [
        ['Which is the best MBBS university in China?', 'There is no single best one. All 43 universities on the 2026–2027 MOE list are approved for the English taught MBBS, so the best choice is the one that fits your budget, climate, distance from home and clinical training needs.'],
        ['How many universities in China can teach MBBS to international students?', 'The Ministry of Education notice of 1 June 2026 lists 43 universities for 2026–2027, with 2,790 places in total.'],
        ['Which city is best for MBBS in China?', 'It depends on what you value. Big coastal cities offer the most choice and the highest costs. Smaller and western cities often cost less. Warm cities suit students who dislike winter, and north-western cities are closest to Pakistan.'],
        ['What is the weather like for MBBS students in China?', 'It varies by city. The north-east and north-west have very cold winters, the northern plain has cold winters and hot summers, the Yangtze region has cool damp winters and hot humid summers, and the south coast is mild in winter. Kunming is mild all year.'],
        ['Which MBBS university in China is cheapest?', 'Published tuition varies widely, and old figures are common online. Compare the first-year total and yearly total including accommodation, insurance and medical, and ask each university for its current written fee.'],
        ['How much does MBBS cost at Xinjiang Medical University?', 'The first-year package is ¥49,950, about USD 7,000. From the second year it is ¥35,950 a year, about USD 5,050. The university has confirmed that fees for the 2027 intakes are the same as for 2026.'],
        ['Is a bigger city better for MBBS?', 'Not necessarily. Big cities offer more choice but cost more. Smaller cities can be quieter and cheaper, and what matters is the quality of the teaching hospital and your own fit with the city.'],
        ['Is halal food available in Chinese cities?', 'It is easiest to find in Xinjiang and Ningxia and in some Muslim districts of other cities. In smaller cities, ask the university what the canteen serves and where students buy halal meat.'],
        ['Do I need to learn Chinese at every university?', 'Yes. Medical subjects are taught in English, but clinical work with patients is in Chinese. At XJMU, introductory Chinese is taught in years 1 and 2 and HSK-4 is required by graduation.'],
        ['How can I check that a university is genuine?', 'Look for its full name on the Ministry of Education notice of 1 June 2026, then confirm the fees and deadlines on the university\'s own international admissions page.'],
        ['Which city in China is closest to Pakistan?', 'Urumqi, in the north-west, is the closest major city on the list, about 2 hours 50 minutes by direct flight from Islamabad.'],
        ['Will my home country accept an MBBS from China?', 'Each country has its own regulator and rules, and they change. Check your national medical council\'s current requirements before you enrol.'],
    ],
    'related' => ['moe-listed-mbbs-universities-in-china', 'mbbs-in-china-fees-2027', 'life-in-urumqi-for-international-students'],
];
