// Comprehensive list of Chinese Medical Universities
// Helper function to generate slug from name
function generateSlug(name) {
    return name.toLowerCase()
        .replace(/university|college|school|medical|center|health science|health|science/gi, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '')
        .substring(0, 50);
}

// Universities Data with comprehensive list - Only 45 MOE Listed
const universities = [
    {
        id: 1,
        name: "Peking University Health Science Center",
        slug: "peking-university-health-science-center",
        location: "Beijing",
        image: "images/peking.webp",
        description: "One of the top medical schools in China with world-class facilities and faculty. Recognized by WHO, MCI, and other international medical bodies.",
        tuition: "35,000 CNY/year",
        duration: "6 years",
        students: "2000+ International",
        city: "beijing",
        tuitionRange: "medium",
        established: "1912",
        rating: 4.8,
        moeListed: true
    },
    {
        id: 2,
        name: "Fudan University Shanghai Medical College",
        slug: "fudan-university-shanghai-medical-college",
        location: "Shanghai",
        image: "images/pudan.webp",
        description: "Located in Shanghai, offering excellent clinical exposure and research opportunities. Known for its innovative teaching methods.",
        tuition: "38,000 CNY/year",
        duration: "6 years",
        students: "1800+ International",
        city: "shanghai",
        tuitionRange: "medium",
        established: "1927",
        rating: 4.7,
        moeListed: true
    },
    {
        id: 3,
        name: "Zhejiang University School of Medicine",
        slug: "zhejiang-university-school-of-medicine",
        location: "Hangzhou",
        image: "images/zhejiang.webp",
        description: "Known for its innovative curriculum and strong emphasis on research and clinical skills. Modern campus with advanced facilities.",
        tuition: "36,000 CNY/year",
        duration: "6 years",
        students: "1500+ International",
        city: "hangzhou",
        tuitionRange: "medium",
        established: "1912",
        rating: 4.6,
        moeListed: true
    },
    {
        id: 4,
        name: "Xinjiang Medical University",
        slug: "xinjiang-medical-university",
        location: "Urumqi",
        image: "xjmu-main.webp",
        description: "One of the top medical universities in Northwest China with English medium MBBS program. Affordable tuition fees and excellent facilities.",
        tuition: "30,000 CNY/year",
        duration: "6 years",
        students: "600+ International",
        city: "urumqi",
        tuitionRange: "low",
        established: "1956",
        rating: 4.5,
        moeListed: true
    },
    {
        id: 5,
        name: "Shanghai Jiao Tong University School of Medicine",
        slug: "shanghai-jiao-tong-university-school-of-medicine",
        location: "Shanghai",
        image: "images/shanghai.webp",
        description: "Prestigious medical institution with strong research programs and international recognition. Excellent clinical training facilities.",
        tuition: "40,000 CNY/year",
        duration: "6 years",
        students: "1200+ International",
        city: "shanghai",
        tuitionRange: "high",
        established: "1896",
        rating: 4.8,
        moeListed: true
    },
    {
        id: 6,
        name: "Tsinghua University School of Medicine",
        slug: "tsinghua-university-school-of-medicine",
        location: "Beijing",
        image: "images/tsinghua.webp",
        description: "Top-ranked university with cutting-edge medical research and world-class faculty. Highly competitive admission process.",
        tuition: "42,000 CNY/year",
        duration: "6 years",
        students: "800+ International",
        city: "beijing",
        tuitionRange: "high",
        established: "2001",
        rating: 4.9,
        moeListed: true
    },
    {
        id: 7,
        name: "Huazhong University of Science and Technology Tongji Medical College",
        slug: "huazhong-university-science-technology-tongji-medical",
        location: "Wuhan",
        image: "images/huazhong.webp",
        description: "Leading medical college in Central China with comprehensive medical programs and strong clinical partnerships.",
        tuition: "32,000 CNY/year",
        duration: "6 years",
        students: "1000+ International",
        city: "wuhan",
        tuitionRange: "medium",
        established: "1907",
        rating: 4.6,
        moeListed: true
    },
    {
        id: 8,
        name: "Sun Yat-sen University School of Medicine",
        slug: "sun-yat-sen-university-school-of-medicine",
        location: "Guangzhou",
        image: "images/sun.webp",
        description: "Renowned medical school in Southern China with excellent research facilities and international collaborations.",
        tuition: "34,000 CNY/year",
        duration: "6 years",
        students: "1100+ International",
        city: "guangzhou",
        tuitionRange: "medium",
        established: "1866",
        rating: 4.7,
        moeListed: true
    },
    {
        id: 9,
        name: "Nanjing Medical University",
        slug: "nanjing-medical-university",
        location: "Nanjing",
        image: "images/nanjing.webp",
        description: "Well-established medical university with strong emphasis on clinical medicine and research excellence.",
        tuition: "30,000 CNY/year",
        duration: "6 years",
        students: "900+ International",
        city: "nanjing",
        tuitionRange: "medium",
        established: "1934",
        rating: 4.5,
        moeListed: true
    },
    {
        id: 10,
        name: "Tianjin Medical University",
        slug: "tianjin-medical-university",
        location: "Tianjin",
        image: "images/tianjin.webp",
        description: "Historic medical institution with comprehensive medical education programs and modern facilities.",
        tuition: "28,000 CNY/year",
        duration: "6 years",
        students: "700+ International",
        city: "tianjin",
        tuitionRange: "medium",
        established: "1951",
        rating: 4.4,
        moeListed: true
    },
    {
        id: 11,
        name: "Sichuan University West China School of Medicine",
        slug: "sichuan-university-west-china-school-of-medicine",
        location: "Chengdu",
        image: "images/sichuan.webp",
        description: "One of the oldest and most prestigious medical schools in Western China with excellent clinical training.",
        tuition: "33,000 CNY/year",
        duration: "6 years",
        students: "950+ International",
        city: "chengdu",
        tuitionRange: "medium",
        established: "1910",
        rating: 4.6,
        moeListed: true
    },
    {
        id: 12,
        name: "Shandong University School of Medicine",
        slug: "shandong-university-school-of-medicine",
        location: "Jinan",
        image: "images/shandong.webp",
        description: "Comprehensive medical university with strong research programs and international student support services.",
        tuition: "29,000 CNY/year",
        duration: "6 years",
        students: "850+ International",
        city: "jinan",
        tuitionRange: "medium",
        established: "1864",
        rating: 4.5,
        moeListed: true
    },
    {
        id: 13,
        name: "China Medical University",
        slug: "china-medical-university",
        location: "Shenyang",
        image: "images/china.webp",
        description: "Leading medical university in Northeast China with excellent facilities and recognized programs.",
        tuition: "27,000 CNY/year",
        duration: "6 years",
        students: "750+ International",
        city: "shenyang",
        tuitionRange: "medium",
        established: "1931",
        rating: 4.4,
        moeListed: true
    },
    {
        id: 14,
        name: "Xi'an Jiaotong University School of Medicine",
        slug: "xian-jiaotong-university-school-of-medicine",
        location: "Xi'an",
        image: "images/xian.webp",
        description: "Prestigious medical school in Northwest China with strong academic programs and research facilities.",
        tuition: "31,000 CNY/year",
        duration: "6 years",
        students: "800+ International",
        city: "xian",
        tuitionRange: "medium",
        established: "1937",
        rating: 4.5,
        moeListed: true
    },
    {
        id: 15,
        name: "Jilin University School of Medicine",
        slug: "jilin-university-school-of-medicine",
        location: "Changchun",
        image: "images/jilin.webp",
        description: "Comprehensive medical university with modern infrastructure and strong clinical training programs.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "650+ International",
        city: "changchun",
        tuitionRange: "low",
        established: "1946",
        rating: 4.3,
        moeListed: true
    },
    {
        id: 16,
        name: "Harbin Medical University",
        slug: "harbin-medical-university",
        location: "Harbin",
        image: "images/harbin.webp",
        description: "Well-established medical university in Northeast China with excellent facilities and recognized programs.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "600+ International",
        city: "harbin",
        tuitionRange: "low",
        established: "1926",
        rating: 4.3,
        moeListed: true
    },
    {
        id: 17,
        name: "Southern Medical University",
        slug: "southern-medical-university",
        location: "Guangzhou",
        image: "images/southern.webp",
        description: "Modern medical university with innovative teaching methods and strong international partnerships.",
        tuition: "30,000 CNY/year",
        duration: "6 years",
        students: "700+ International",
        city: "guangzhou",
        tuitionRange: "medium",
        established: "1951",
        rating: 4.4,
        moeListed: true
    },
    {
        id: 18,
        name: "Capital Medical University",
        slug: "capital-medical-university",
        location: "Beijing",
        image: "images/capital.webp",
        description: "Leading medical university in Beijing with excellent clinical training and research opportunities.",
        tuition: "35,000 CNY/year",
        duration: "6 years",
        students: "900+ International",
        city: "beijing",
        tuitionRange: "medium",
        established: "1960",
        rating: 4.6,
        moeListed: true
    },
    {
        id: 19,
        name: "Wenzhou Medical University",
        slug: "wenzhou-medical-university",
        location: "Wenzhou",
        image: "images/wenzhou.webp",
        description: "Rapidly growing medical university with modern facilities and strong emphasis on clinical skills.",
        tuition: "28,000 CNY/year",
        duration: "6 years",
        students: "550+ International",
        city: "wenzhou",
        tuitionRange: "medium",
        established: "1958",
        rating: 4.2,
        moeListed: true
    },
    {
        id: 20,
        name: "Dalian Medical University",
        slug: "dalian-medical-university",
        location: "Dalian",
        image: "images/dalian.webp",
        description: "Coastal medical university with beautiful campus and excellent medical education programs.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "500+ International",
        city: "dalian",
        tuitionRange: "low",
        established: "1947",
        rating: 4.2,
        moeListed: true
    },
    {
        id: 21,
        name: "Kunming Medical University",
        slug: "kunming-medical-university",
        location: "Kunming",
        image: "images/kunming.webp",
        description: "Leading medical university in Southwest China with pleasant climate and excellent facilities.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "600+ International",
        city: "kunming",
        tuitionRange: "low",
        established: "1933",
        rating: 4.3,
        moeListed: true
    },
    {
        id: 22,
        name: "Guangxi Medical University",
        slug: "guangxi-medical-university",
        location: "Nanning",
        image: "images/guangxi.webp",
        description: "Well-established medical university in Southern China with comprehensive medical programs.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "500+ International",
        city: "nanning",
        tuitionRange: "low",
        established: "1934",
        rating: 4.2,
        moeListed: true
    },
    {
        id: 23,
        name: "Qingdao University Medical College",
        slug: "qingdao-university-medical-college",
        location: "Qingdao",
        image: "images/qingdao.webp",
        description: "Coastal medical college with modern facilities and strong clinical training programs.",
        tuition: "27,000 CNY/year",
        duration: "6 years",
        students: "450+ International",
        city: "qingdao",
        tuitionRange: "medium",
        established: "1909",
        rating: 4.3,
        moeListed: true
    },
    {
        id: 24,
        name: "Zhengzhou University School of Medicine",
        slug: "zhengzhou-university-school-of-medicine",
        location: "Zhengzhou",
        image: "images/zhengzhou.webp",
        description: "Comprehensive medical school in Central China with excellent facilities and recognized programs.",
        tuition: "28,000 CNY/year",
        duration: "6 years",
        students: "600+ International",
        city: "zhengzhou",
        tuitionRange: "medium",
        established: "1928",
        rating: 4.4,
        moeListed: true
    },
    {
        id: 25,
        name: "Chongqing Medical University",
        slug: "chongqing-medical-university",
        location: "Chongqing",
        image: "images/chongqing.webp",
        description: "Leading medical university in Southwest China with strong research programs and clinical training.",
        tuition: "29,000 CNY/year",
        duration: "6 years",
        students: "700+ International",
        city: "chongqing",
        tuitionRange: "medium",
        established: "1956",
        rating: 4.4,
        moeListed: true
    },
    {
        id: 26,
        name: "Anhui Medical University",
        slug: "anhui-medical-university",
        location: "Hefei",
        image: "images/anhui.webp",
        description: "Well-established medical university with comprehensive medical education programs.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "500+ International",
        city: "hefei",
        tuitionRange: "low",
        established: "1926",
        rating: 4.2,
        moeListed: true
    },
    {
        id: 27,
        name: "Fujian Medical University",
        slug: "fujian-medical-university",
        location: "Fuzhou",
        image: "images/fujian.webp",
        description: "Coastal medical university with modern facilities and strong international student support.",
        tuition: "27,000 CNY/year",
        duration: "6 years",
        students: "550+ International",
        city: "fuzhou",
        tuitionRange: "medium",
        established: "1937",
        rating: 4.3,
        moeListed: true
    },
    {
        id: 28,
        name: "Jiangsu University School of Medicine",
        slug: "jiangsu-university-school-of-medicine",
        location: "Zhenjiang",
        image: "images/jiangsu.webp",
        description: "Comprehensive medical school with excellent facilities and recognized medical programs.",
        tuition: "28,000 CNY/year",
        duration: "6 years",
        students: "500+ International",
        city: "zhenjiang",
        tuitionRange: "medium",
        established: "1902",
        rating: 4.3,
        moeListed: true
    },
    {
        id: 29,
        name: "Nantong University Medical College",
        slug: "nantong-university-medical-college",
        location: "Nantong",
        image: "images/nantong.webp",
        description: "Well-established medical college with strong emphasis on clinical medicine and research.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "nantong",
        tuitionRange: "low",
        established: "1912",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 30,
        name: "Xuzhou Medical University",
        slug: "xuzhou-medical-university",
        location: "Xuzhou",
        image: "images/xuzhou.webp",
        description: "Comprehensive medical university with modern infrastructure and excellent clinical training.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "450+ International",
        city: "xuzhou",
        tuitionRange: "low",
        established: "1958",
        rating: 4.2,
        moeListed: false
    },
    {
        id: 31,
        name: "Hebei Medical University",
        slug: "hebei-medical-university",
        location: "Shijiazhuang",
        image: "images/hebei.webp",
        description: "Leading medical university in North China with comprehensive medical programs.",
        tuition: "27,000 CNY/year",
        duration: "6 years",
        students: "500+ International",
        city: "shijiazhuang",
        tuitionRange: "medium",
        established: "1894",
        rating: 4.3,
        moeListed: false
    },
    {
        id: 32,
        name: "Shanxi Medical University",
        slug: "shanxi-medical-university",
        location: "Taiyuan",
        image: "images/shanxi.webp",
        description: "Well-established medical university with strong academic programs and clinical training.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "taiyuan",
        tuitionRange: "low",
        established: "1919",
        rating: 4.2,
        moeListed: false
    },
    {
        id: 33,
        name: "Inner Mongolia Medical University",
        slug: "inner-mongolia-medical-university",
        location: "Hohhot",
        image: "images/inner.webp",
        description: "Medical university in Northern China with affordable fees and good facilities.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "350+ International",
        city: "hohhot",
        tuitionRange: "low",
        established: "1956",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 34,
        name: "Liaoning Medical University",
        slug: "liaoning-medical-university",
        location: "Jinzhou",
        image: "images/liaoning.webp",
        description: "Comprehensive medical university with modern facilities and recognized programs.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "jinzhou",
        tuitionRange: "low",
        established: "1946",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 35,
        name: "Yanbian University Medical College",
        slug: "yanbian-university-medical-college",
        location: "Yanji",
        image: "images/yanbian.webp",
        description: "Medical college in Northeast China with multicultural environment and good facilities.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "yanji",
        tuitionRange: "low",
        established: "1948",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 36,
        name: "Ningxia Medical University",
        slug: "ningxia-medical-university",
        location: "Yinchuan",
        image: "images/ningxia.webp",
        description: "Medical university in Northwest China with affordable fees and comprehensive programs.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "350+ International",
        city: "yinchuan",
        tuitionRange: "low",
        established: "1958",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 37,
        name: "Gansu University of Chinese Medicine",
        slug: "gansu-university-chinese-medicine",
        location: "Lanzhou",
        image: "images/gansu.webp",
        description: "Medical university combining traditional and modern medicine with good facilities.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "lanzhou",
        tuitionRange: "low",
        established: "1978",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 38,
        name: "Xiamen University Medical College",
        slug: "xiamen-university-medical-college",
        location: "Xiamen",
        image: "images/xiamen.webp",
        description: "Coastal medical college with beautiful campus and excellent medical programs.",
        tuition: "30,000 CNY/year",
        duration: "6 years",
        students: "500+ International",
        city: "xiamen",
        tuitionRange: "medium",
        established: "1996",
        rating: 4.3,
        moeListed: false
    },
    {
        id: 39,
        name: "Suzhou University Medical College",
        slug: "suzhou-university-medical-college",
        location: "Suzhou",
        image: "images/suzhou.webp",
        description: "Modern medical college in beautiful Suzhou with excellent facilities.",
        tuition: "29,000 CNY/year",
        duration: "6 years",
        students: "450+ International",
        city: "suzhou",
        tuitionRange: "medium",
        established: "1912",
        rating: 4.2,
        moeListed: false
    },
    {
        id: 40,
        name: "Hubei University of Medicine",
        slug: "hubei-university-of-medicine",
        location: "Shiyan",
        image: "images/hubei.webp",
        description: "Comprehensive medical university with strong clinical training programs.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "shiyan",
        tuitionRange: "low",
        established: "1965",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 41,
        name: "Hunan University of Chinese Medicine",
        slug: "hunan-university-chinese-medicine",
        location: "Changsha",
        image: "images/hunan.webp",
        description: "Medical university combining traditional Chinese and modern medicine.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "450+ International",
        city: "changsha",
        tuitionRange: "low",
        established: "1934",
        rating: 4.2,
        moeListed: false
    },
    {
        id: 42,
        name: "Jiangxi Medical University",
        slug: "jiangxi-medical-university",
        location: "Nanchang",
        image: "images/jiangxi.webp",
        description: "Well-established medical university with comprehensive medical programs.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "nanchang",
        tuitionRange: "low",
        established: "1921",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 43,
        name: "Henan University School of Medicine",
        slug: "henan-university-school-of-medicine",
        location: "Kaifeng",
        image: "images/henan.webp",
        description: "Comprehensive medical school with modern facilities and recognized programs.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "350+ International",
        city: "kaifeng",
        tuitionRange: "low",
        established: "1928",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 44,
        name: "Yunnan University of Chinese Medicine",
        slug: "yunnan-university-chinese-medicine",
        location: "Kunming",
        image: "images/yunnan.webp",
        description: "Medical university combining traditional and modern medicine in beautiful Kunming.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "kunming",
        tuitionRange: "low",
        established: "1960",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 45,
        name: "Guizhou Medical University",
        slug: "guizhou-medical-university",
        location: "Guiyang",
        image: "images/guizhou.webp",
        description: "Medical university in Southwest China with affordable fees and good facilities.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "350+ International",
        city: "guiyang",
        tuitionRange: "low",
        established: "1938",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 46,
        name: "Hainan Medical University",
        slug: "hainan-medical-university",
        location: "Haikou",
        image: "images/hainan.webp",
        description: "Tropical medical university with modern facilities and pleasant climate.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "haikou",
        tuitionRange: "low",
        established: "1947",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 47,
        name: "Tibet University Medical College",
        slug: "tibet-university-medical-college",
        location: "Lhasa",
        image: "images/tibet.webp",
        description: "Unique medical college in Tibet combining traditional Tibetan and modern medicine.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "200+ International",
        city: "lhasa",
        tuitionRange: "low",
        established: "2001",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 48,
        name: "North Sichuan Medical University",
        slug: "north-sichuan-medical-university",
        location: "Nanchong",
        image: "images/north.webp",
        description: "Comprehensive medical university with strong clinical training programs.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "nanchong",
        tuitionRange: "low",
        established: "1951",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 49,
        name: "Zunyi Medical University",
        slug: "zunyi-medical-university",
        location: "Zunyi",
        image: "images/zunyi.webp",
        description: "Medical university with modern facilities and comprehensive medical programs.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "350+ International",
        city: "zunyi",
        tuitionRange: "low",
        established: "1947",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 50,
        name: "Qiqihar Medical University",
        slug: "qiqihar-medical-university",
        location: "Qiqihar",
        image: "images/qiqihar.webp",
        description: "Medical university in Northeast China with affordable fees and good facilities.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "qiqihar",
        tuitionRange: "low",
        established: "1946",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 51,
        name: "Southwest Medical University",
        slug: "southwest-medical-university",
        location: "Luzhou",
        image: "swmu.webp",
        description: "Renowned medical university in Luzhou providing high-quality medical education with affordable tuition fees.",
        tuition: "31,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "luzhou",
        tuitionRange: "medium",
        established: "1951",
        rating: 4.0,
        moeListed: true
    },
    {
        id: 52,
        name: "Bengbu Medical University",
        slug: "bengbu-medical-university",
        location: "Bengbu",
        image: "images/bengbu.webp",
        description: "Well-established medical university in Anhui province offering comprehensive MBBS programs.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "bengbu",
        tuitionRange: "low",
        established: "1958",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 53,
        name: "Changzhi Medical University",
        slug: "changzhi-medical-university",
        location: "Changzhi",
        image: "images/changzhi.webp",
        description: "Medical university in Shanxi province providing quality medical education.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "350+ International",
        city: "changzhi",
        tuitionRange: "low",
        established: "1946",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 54,
        name: "Chengde Medical University",
        slug: "chengde-medical-university",
        location: "Chengde",
        image: "images/chengde.webp",
        description: "Comprehensive medical university in Hebei province known for quality medical education.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "chengde",
        tuitionRange: "low",
        established: "1945",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 55,
        name: "Dali University Medical College",
        slug: "dali-university-medical-college",
        location: "Dali",
        image: "images/dali.webp",
        description: "Medical college located in the scenic city of Dali offering MBBS programs.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "250+ International",
        city: "dali",
        tuitionRange: "low",
        established: "2001",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 56,
        name: "Datong University Medical College",
        slug: "datong-university-medical-college",
        location: "Datong",
        image: "images/datong.webp",
        description: "Medical college in Shanxi province providing affordable medical education.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "200+ International",
        city: "datong",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 57,
        name: "Foshan University Medical College",
        slug: "foshan-university-medical-college",
        location: "Foshan",
        image: "images/foshan.webp",
        description: "Modern medical college in Guangdong province offering English-medium MBBS programs.",
        tuition: "28,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "foshan",
        tuitionRange: "medium",
        established: "1958",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 58,
        name: "Gannan Medical University",
        slug: "gannan-medical-university",
        location: "Ganzhou",
        image: "images/gannan.webp",
        description: "Medical university in Jiangxi province providing comprehensive medical education.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "350+ International",
        city: "ganzhou",
        tuitionRange: "low",
        established: "1941",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 59,
        name: "Guangdong Medical University",
        slug: "guangdong-medical-university",
        location: "Zhanjiang",
        image: "images/guangdong-med.webp",
        description: "Leading medical university in Guangdong province with modern campuses.",
        tuition: "29,000 CNY/year",
        duration: "6 years",
        students: "600+ International",
        city: "zhanjiang",
        tuitionRange: "medium",
        established: "1958",
        rating: 4.2,
        moeListed: false
    },
    {
        id: 60,
        name: "Guilin Medical University",
        slug: "guilin-medical-university",
        location: "Guilin",
        image: "images/guilin.webp",
        description: "Medical university located in the picturesque city of Guilin.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "guilin",
        tuitionRange: "low",
        established: "1935",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 61,
        name: "Huanghe Science and Technology University Medical College",
        slug: "huanghe-science-technology-university-medical-college",
        location: "Zhengzhou",
        image: "images/huanghe.webp",
        description: "Private medical college in Henan province offering MBBS programs.",
        tuition: "27,000 CNY/year",
        duration: "6 years",
        students: "200+ International",
        city: "zhengzhou",
        tuitionRange: "medium",
        established: "2004",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 62,
        name: "Jiamusi University Medical College",
        slug: "jiamusi-university-medical-college",
        location: "Jiamusi",
        image: "images/jiamusi.webp",
        description: "Comprehensive university medical college in Heilongjiang province.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "jiamusi",
        tuitionRange: "low",
        established: "1947",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 63,
        name: "Jining Medical University",
        slug: "jining-medical-university",
        location: "Jining",
        image: "images/jining.webp",
        description: "Medical university in Shandong province known for quality education.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "350+ International",
        city: "jining",
        tuitionRange: "low",
        established: "1952",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 64,
        name: "Jinzhou Medical University",
        slug: "jinzhou-medical-university",
        location: "Jinzhou",
        image: "images/jinzhou.webp",
        description: "Well-established medical university in Liaoning province.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "jinzhou",
        tuitionRange: "low",
        established: "1946",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 65,
        name: "Linyi University Medical College",
        slug: "linyi-university-medical-college",
        location: "Linyi",
        image: "images/linyi.webp",
        description: "Medical college in Shandong province providing quality medical education.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "250+ International",
        city: "linyi",
        tuitionRange: "low",
        established: "1941",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 66,
        name: "Loudi University Medical College",
        slug: "loudi-university-medical-college",
        location: "Loudi",
        image: "images/loudi.webp",
        description: "Medical college in Hunan province offering affordable MBBS programs.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "200+ International",
        city: "loudi",
        tuitionRange: "low",
        established: "1978",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 67,
        name: "Mudanjiang Medical University",
        slug: "mudanjiang-medical-university",
        location: "Mudanjiang",
        image: "images/mudanjiang.webp",
        description: "Medical university in Heilongjiang province providing comprehensive education.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "mudanjiang",
        tuitionRange: "low",
        established: "1958",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 68,
        name: "Nanchang University Medical College",
        slug: "nanchang-university-medical-college",
        location: "Nanchang",
        image: "images/nanchang-med.webp",
        description: "Comprehensive university medical college in Jiangxi province.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "500+ International",
        city: "nanchang",
        tuitionRange: "low",
        established: "1921",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 69,
        name: "Nanjing University of Chinese Medicine",
        slug: "nanjing-university-chinese-medicine",
        location: "Nanjing",
        image: "images/nanjing-cm.webp",
        description: "Leading university of Chinese medicine offering integrated programs.",
        tuition: "28,000 CNY/year",
        duration: "6 years",
        students: "600+ International",
        city: "nanjing",
        tuitionRange: "medium",
        established: "1954",
        rating: 4.3,
        moeListed: false
    },
    {
        id: 70,
        name: "Ningbo University Medical College",
        slug: "ningbo-university-medical-college",
        location: "Ningbo",
        image: "images/ningbo.webp",
        description: "Modern medical college in Zhejiang province offering English-medium programs.",
        tuition: "30,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "ningbo",
        tuitionRange: "medium",
        established: "1998",
        rating: 4.2,
        moeListed: false
    },
    {
        id: 71,
        name: "North China University of Science and Technology Medical College",
        slug: "north-china-university-science-technology-medical-college",
        location: "Tangshan",
        image: "images/north-china.webp",
        description: "Comprehensive university medical college in Hebei province.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "350+ International",
        city: "tangshan",
        tuitionRange: "low",
        established: "1958",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 72,
        name: "Qinghai University Medical College",
        slug: "qinghai-university-medical-college",
        location: "Xining",
        image: "images/qinghai.webp",
        description: "Medical college in Qinghai province offering unique programs.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "200+ International",
        city: "xining",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 73,
        name: "Quanzhou Medical College",
        slug: "quanzhou-medical-college",
        location: "Quanzhou",
        image: "images/quanzhou.webp",
        description: "Medical college in Fujian province providing quality medical education.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "250+ International",
        city: "quanzhou",
        tuitionRange: "low",
        established: "1934",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 74,
        name: "Shaanxi University of Chinese Medicine",
        slug: "shaanxi-university-chinese-medicine",
        location: "Xianyang",
        image: "images/shaanxi-cm.webp",
        description: "Specialized university for Chinese medicine offering integrated programs.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "xianyang",
        tuitionRange: "low",
        established: "1952",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 75,
        name: "Shanghai University of Medicine & Health Sciences",
        slug: "shanghai-university-medicine-health-sciences",
        location: "Shanghai",
        image: "images/shanghai-health.webp",
        description: "Modern medical university in Shanghai focusing on health sciences.",
        tuition: "35,000 CNY/year",
        duration: "6 years",
        students: "500+ International",
        city: "shanghai",
        tuitionRange: "medium",
        established: "1985",
        rating: 4.2,
        moeListed: false
    },
    {
        id: 76,
        name: "Shaoguan University Medical College",
        slug: "shaoguan-university-medical-college",
        location: "Shaoguan",
        image: "images/shaoguan.webp",
        description: "Medical college in Guangdong province providing affordable education.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "200+ International",
        city: "shaoguan",
        tuitionRange: "low",
        established: "1958",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 77,
        name: "Shenyang Medical College",
        slug: "shenyang-medical-college",
        location: "Shenyang",
        image: "images/shenyang-med.webp",
        description: "Medical college in Liaoning province offering comprehensive programs.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "shenyang",
        tuitionRange: "low",
        established: "1949",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 78,
        name: "Shihezi University Medical College",
        slug: "shihezi-university-medical-college",
        location: "Shihezi",
        image: "images/shihezi.webp",
        description: "Medical college in Xinjiang providing quality medical education.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "250+ International",
        city: "shihezi",
        tuitionRange: "low",
        established: "1949",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 79,
        name: "Shijiazhuang Medical University",
        slug: "shijiazhuang-medical-university",
        location: "Shijiazhuang",
        image: "images/shijiazhuang-med.webp",
        description: "Medical university in Hebei province offering comprehensive programs.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "shijiazhuang",
        tuitionRange: "low",
        established: "1958",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 80,
        name: "Suzhou Medical College",
        slug: "suzhou-medical-college",
        location: "Suzhou",
        image: "images/suzhou-med.webp",
        description: "Medical college in Jiangsu province providing quality education.",
        tuition: "28,000 CNY/year",
        duration: "6 years",
        students: "350+ International",
        city: "suzhou",
        tuitionRange: "medium",
        established: "1912",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 81,
        name: "Taishan Medical University",
        slug: "taishan-medical-university",
        location: "Taian",
        image: "images/taishan.webp",
        description: "Medical university in Shandong province near Mount Tai.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "taian",
        tuitionRange: "low",
        established: "1891",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 82,
        name: "Tongji University Medical College",
        slug: "tongji-university-medical-college",
        location: "Shanghai",
        image: "images/tongji-med.webp",
        description: "Prestigious medical college affiliated with Tongji University.",
        tuition: "38,000 CNY/year",
        duration: "6 years",
        students: "600+ International",
        city: "shanghai",
        tuitionRange: "medium",
        established: "1907",
        rating: 4.5,
        moeListed: false
    },
    {
        id: 83,
        name: "Weifang Medical University",
        slug: "weifang-medical-university",
        location: "Weifang",
        image: "images/weifang.webp",
        description: "Medical university in Shandong province providing comprehensive programs.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "weifang",
        tuitionRange: "low",
        established: "1951",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 84,
        name: "Wuhan University School of Medicine",
        slug: "wuhan-university-school-of-medicine",
        location: "Wuhan",
        image: "images/wuhan-med.webp",
        description: "Comprehensive medical school affiliated with Wuhan University.",
        tuition: "33,000 CNY/year",
        duration: "6 years",
        students: "700+ International",
        city: "wuhan",
        tuitionRange: "medium",
        established: "1943",
        rating: 4.4,
        moeListed: false
    },
    {
        id: 85,
        name: "Wuxi Medical College",
        slug: "wuxi-medical-college",
        location: "Wuxi",
        image: "images/wuxi.webp",
        description: "Medical college in Jiangsu province providing quality education.",
        tuition: "27,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "wuxi",
        tuitionRange: "medium",
        established: "1958",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 86,
        name: "Xiangnan University Medical College",
        slug: "xiangnan-university-medical-college",
        location: "Chenzhou",
        image: "images/xiangnan.webp",
        description: "Medical college in Hunan province offering affordable programs.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "250+ International",
        city: "chenzhou",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 87,
        name: "Xiangya School of Medicine, Central South University",
        slug: "xiangya-school-medicine-central-south-university",
        location: "Changsha",
        image: "images/xiangya.webp",
        description: "One of the most prestigious medical schools in China.",
        tuition: "36,000 CNY/year",
        duration: "6 years",
        students: "800+ International",
        city: "changsha",
        tuitionRange: "medium",
        established: "1914",
        rating: 4.7,
        moeListed: false
    },
    {
        id: 88,
        name: "Xian Medical University",
        slug: "xian-medical-university",
        location: "Xi'an",
        image: "images/xian-med.webp",
        description: "Medical university in Shaanxi province offering comprehensive programs.",
        tuition: "28,000 CNY/year",
        duration: "6 years",
        students: "500+ International",
        city: "xian",
        tuitionRange: "medium",
        established: "1951",
        rating: 4.2,
        moeListed: false
    },
    {
        id: 89,
        name: "Xingtai Medical College",
        slug: "xingtai-medical-college",
        location: "Xingtai",
        image: "images/xingtai.webp",
        description: "Medical college in Hebei province providing affordable education.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "200+ International",
        city: "xingtai",
        tuitionRange: "low",
        established: "1964",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 90,
        name: "Xinxiang Medical University",
        slug: "xinxiang-medical-university",
        location: "Xinxiang",
        image: "images/xinxiang.webp",
        description: "Medical university in Henan province offering comprehensive programs.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "400+ International",
        city: "xinxiang",
        tuitionRange: "low",
        established: "1950",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 91,
        name: "Yan'an University Medical College",
        slug: "yanan-university-medical-college",
        location: "Yan'an",
        image: "images/yanan.webp",
        description: "Medical college in Shaanxi province with historical significance.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "250+ International",
        city: "yanan",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 92,
        name: "Yichun University Medical College",
        slug: "yichun-university-medical-college",
        location: "Yichun",
        image: "images/yichun.webp",
        description: "Medical college in Jiangxi province providing affordable education.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "200+ International",
        city: "yichun",
        tuitionRange: "low",
        established: "1958",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 93,
        name: "Yinchuan Medical College",
        slug: "yinchuan-medical-college",
        location: "Yinchuan",
        image: "images/yinchuan-med.webp",
        description: "Medical college in Ningxia province offering unique programs.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "180+ International",
        city: "yinchuan",
        tuitionRange: "low",
        established: "1958",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 94,
        name: "Yulin Medical College",
        slug: "yulin-medical-college",
        location: "Yulin",
        image: "images/yulin.webp",
        description: "Medical college in Shaanxi province providing quality education.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "220+ International",
        city: "yulin",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 95,
        name: "Zhangjiakou Medical College",
        slug: "zhangjiakou-medical-college",
        location: "Zhangjiakou",
        image: "images/zhangjiakou.webp",
        description: "Medical college in Hebei province offering comprehensive programs.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "200+ International",
        city: "zhangjiakou",
        tuitionRange: "low",
        established: "1945",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 96,
        name: "Zhaoqing Medical College",
        slug: "zhaoqing-medical-college",
        location: "Zhaoqing",
        image: "images/zhaoqing.webp",
        description: "Medical college in Guangdong province providing quality education.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "250+ International",
        city: "zhaoqing",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 97,
        name: "Zhenjiang Medical College",
        slug: "zhenjiang-medical-college",
        location: "Zhenjiang",
        image: "images/zhenjiang-med.webp",
        description: "Medical college in Jiangsu province offering comprehensive programs.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "300+ International",
        city: "zhenjiang",
        tuitionRange: "low",
        established: "1951",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 98,
        name: "Zhongshan School of Medicine, Sun Yat-sen University",
        slug: "zhongshan-school-medicine-sun-yat-sen-university",
        location: "Guangzhou",
        image: "images/zhongshan.webp",
        description: "Prestigious medical school offering high-quality medical education.",
        tuition: "34,000 CNY/year",
        duration: "6 years",
        students: "700+ International",
        city: "guangzhou",
        tuitionRange: "medium",
        established: "1866",
        rating: 4.6,
        moeListed: false
    },
    {
        id: 99,
        name: "Zhuhai College of Jilin University Medical School",
        slug: "zhuhai-college-jilin-university-medical-school",
        location: "Zhuhai",
        image: "images/zhuhai.webp",
        description: "Medical school in the special economic zone of Zhuhai.",
        tuition: "29,000 CNY/year",
        duration: "6 years",
        students: "350+ International",
        city: "zhuhai",
        tuitionRange: "medium",
        established: "2004",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 100,
        name: "Zibo Medical College",
        slug: "zibo-medical-college",
        location: "Zibo",
        image: "images/zibo.webp",
        description: "Medical college in Shandong province providing comprehensive programs.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "280+ International",
        city: "zibo",
        tuitionRange: "low",
        established: "1956",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 101,
        name: "Baotou Medical College",
        slug: "baotou-medical-college",
        location: "Baotou",
        image: "images/baotou.webp",
        description: "Medical college in Inner Mongolia offering comprehensive MBBS programs with focus on clinical medicine and community health.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "280+ International",
        city: "baotou",
        tuitionRange: "low",
        established: "1958",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 102,
        name: "Beihua University Medical College",
        slug: "beihua-university-medical-college",
        location: "Jilin",
        image: "images/beihua.webp",
        description: "Comprehensive university medical college in Jilin province providing quality medical education with modern facilities.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "320+ International",
        city: "jilin",
        tuitionRange: "low",
        established: "1906",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 103,
        name: "Binzhou Medical University",
        slug: "binzhou-medical-university",
        location: "Binzhou",
        image: "images/binzhou.webp",
        description: "Medical university in Shandong province offering comprehensive MBBS programs with strong clinical training.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "350+ International",
        city: "binzhou",
        tuitionRange: "low",
        established: "1946",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 104,
        name: "Cangzhou Medical College",
        slug: "cangzhou-medical-college",
        location: "Cangzhou",
        image: "images/cangzhou.webp",
        description: "Medical college in Hebei province providing affordable medical education with practical orientation.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "240+ International",
        city: "cangzhou",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 105,
        name: "Changsha Medical University",
        slug: "changsha-medical-university",
        location: "Changsha",
        image: "images/changsha-med.webp",
        description: "Private medical university in Hunan province offering English-medium MBBS programs with modern infrastructure.",
        tuition: "28,000 CNY/year",
        duration: "6 years",
        students: "380+ International",
        city: "changsha",
        tuitionRange: "medium",
        established: "1989",
        rating: 4.2,
        moeListed: false
    },
    {
        id: 106,
        name: "Chengdu University of Traditional Chinese Medicine",
        slug: "chengdu-university-traditional-chinese-medicine",
        location: "Chengdu",
        image: "images/chengdu-cm.webp",
        description: "Leading university of traditional Chinese medicine offering integrated MBBS programs with TCM components.",
        tuition: "30,000 CNY/year",
        duration: "6 years",
        students: "600+ International",
        city: "chengdu",
        tuitionRange: "medium",
        established: "1956",
        rating: 4.4,
        moeListed: true
    },
    {
        id: 107,
        name: "Chifeng University Medical College",
        slug: "chifeng-university-medical-college",
        location: "Chifeng",
        image: "images/chifeng.webp",
        description: "Medical college in Inner Mongolia providing affordable medical education with regional health focus.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "220+ International",
        city: "chifeng",
        tuitionRange: "low",
        established: "1958",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 108,
        name: "Dandong Medical College",
        slug: "dandong-medical-college",
        location: "Dandong",
        image: "images/dandong.webp",
        description: "Medical college in Liaoning province offering MBBS programs with cross-border medical perspectives.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "260+ International",
        city: "dandong",
        tuitionRange: "low",
        established: "1948",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 109,
        name: "Dezhou University Medical College",
        slug: "dezhou-university-medical-college",
        location: "Dezhou",
        image: "images/dezhou.webp",
        description: "Medical college in Shandong province providing quality medical education with affordable fees.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "230+ International",
        city: "dezhou",
        tuitionRange: "low",
        established: "1971",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 110,
        name: "Dongguan Medical College",
        slug: "dongguan-medical-college",
        location: "Dongguan",
        image: "images/dongguan.webp",
        description: "Medical college in Guangdong's manufacturing hub offering practical-oriented medical education.",
        tuition: "27,000 CNY/year",
        duration: "6 years",
        students: "290+ International",
        city: "dongguan",
        tuitionRange: "medium",
        established: "1990",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 111,
        name: "Fushun Medical College",
        slug: "fushun-medical-college",
        location: "Fushun",
        image: "images/fushun.webp",
        description: "Medical college in Liaoning province providing comprehensive MBBS programs with clinical focus.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "250+ International",
        city: "fushun",
        tuitionRange: "low",
        established: "1978",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 112,
        name: "Fuxin Medical College",
        slug: "fuxin-medical-college",
        location: "Fuxin",
        image: "images/fuxin.webp",
        description: "Medical college in Liaoning province offering affordable medical education programs.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "210+ International",
        city: "fuxin",
        tuitionRange: "low",
        established: "1958",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 113,
        name: "Gansu Medical College",
        slug: "gansu-medical-college",
        location: "Lanzhou",
        image: "images/gansu-med.webp",
        description: "Medical college in Gansu province providing quality medical education with regional health emphasis.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "270+ International",
        city: "lanzhou",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 114,
        name: "Guangzhou University of Chinese Medicine",
        slug: "guangzhou-university-chinese-medicine",
        location: "Guangzhou",
        image: "images/guangzhou-cm.webp",
        description: "Prestigious university of Chinese medicine offering integrated MBBS programs with traditional medicine.",
        tuition: "32,000 CNY/year",
        duration: "6 years",
        students: "700+ International",
        city: "guangzhou",
        tuitionRange: "medium",
        established: "1956",
        rating: 4.5,
        moeListed: true
    },
    {
        id: 115,
        name: "Guiyang College of Traditional Chinese Medicine",
        slug: "guiyang-college-traditional-chinese-medicine",
        location: "Guiyang",
        image: "images/guiyang-cm.webp",
        description: "College of traditional Chinese medicine in Guizhou offering integrated medical programs.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "320+ International",
        city: "guiyang",
        tuitionRange: "low",
        established: "1965",
        rating: 4.1,
        moeListed: false
    },
    {
        id: 116,
        name: "Haikou Medical College",
        slug: "haikou-medical-college",
        location: "Haikou",
        image: "images/haikou-med.webp",
        description: "Medical college in Hainan province offering tropical medicine programs with international standards.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "310+ International",
        city: "haikou",
        tuitionRange: "low",
        established: "2001",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 117,
        name: "Handan Medical College",
        slug: "handan-medical-college",
        location: "Handan",
        image: "images/handan.webp",
        description: "Medical college in Hebei province providing comprehensive medical education programs.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "260+ International",
        city: "handan",
        tuitionRange: "low",
        established: "1945",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 118,
        name: "Hangzhou Medical College",
        slug: "hangzhou-medical-college",
        location: "Hangzhou",
        image: "images/hangzhou-med.webp",
        description: "Medical college in Zhejiang's beautiful capital offering quality medical education programs.",
        tuition: "29,000 CNY/year",
        duration: "6 years",
        students: "380+ International",
        city: "hangzhou",
        tuitionRange: "medium",
        established: "1999",
        rating: 4.2,
        moeListed: false
    },
    {
        id: 119,
        name: "Hengyang Medical College",
        slug: "hengyang-medical-college",
        location: "Hengyang",
        image: "images/hengyang.webp",
        description: "Medical college in Hunan province providing affordable medical education with clinical training.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "240+ International",
        city: "hengyang",
        tuitionRange: "low",
        established: "1951",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 120,
        name: "Heze Medical College",
        slug: "heze-medical-college",
        location: "Heze",
        image: "images/heze.webp",
        description: "Medical college in Shandong province offering comprehensive MBBS programs.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "250+ International",
        city: "heze",
        tuitionRange: "low",
        established: "1950",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 121,
        name: "Hohhot Medical College",
        slug: "hohhot-medical-college",
        location: "Hohhot",
        image: "images/hohhot-med.webp",
        description: "Medical college in Inner Mongolia's capital providing quality medical education.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "280+ International",
        city: "hohhot",
        tuitionRange: "low",
        established: "1956",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 122,
        name: "Huaihua University Medical College",
        slug: "huaihua-university-medical-college",
        location: "Huaihua",
        image: "images/huaihua.webp",
        description: "Medical college in Hunan province offering affordable MBBS programs.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "220+ International",
        city: "huaihua",
        tuitionRange: "low",
        established: "1958",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 123,
        name: "Huaibei Medical College",
        slug: "huaibei-medical-college",
        location: "Huaibei",
        image: "images/huaibei.webp",
        description: "Medical college in Anhui province providing comprehensive medical education.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "260+ International",
        city: "huaibei",
        tuitionRange: "low",
        established: "1974",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 124,
        name: "Huangshan Medical College",
        slug: "huangshan-medical-college",
        location: "Huangshan",
        image: "images/huangshan.webp",
        description: "Medical college in Anhui's scenic region offering quality medical education.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "230+ International",
        city: "huangshan",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 125,
        name: "Hulunbuir Medical College",
        slug: "hulunbuir-medical-college",
        location: "Hulunbuir",
        image: "images/hulunbuir.webp",
        description: "Medical college in Inner Mongolia providing medical education for border regions.",
        tuition: "21,000 CNY/year",
        duration: "6 years",
        students: "180+ International",
        city: "hulunbuir",
        tuitionRange: "low",
        established: "1978",
        rating: 3.7,
        moeListed: false
    },
    {
        id: 126,
        name: "Jiaxing University Medical College",
        slug: "jiaxing-university-medical-college",
        location: "Jiaxing",
        image: "images/jiaxing.webp",
        description: "Medical college in Zhejiang province offering quality MBBS programs.",
        tuition: "26,000 CNY/year",
        duration: "6 years",
        students: "270+ International",
        city: "jiaxing",
        tuitionRange: "low",
        established: "1914",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 127,
        name: "Jieyang Medical College",
        slug: "jieyang-medical-college",
        location: "Jieyang",
        image: "images/jieyang.webp",
        description: "Medical college in Guangdong province providing comprehensive medical education.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "240+ International",
        city: "jieyang",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 128,
        name: "Jincheng Medical College",
        slug: "jincheng-medical-college",
        location: "Jincheng",
        image: "images/jincheng.webp",
        description: "Medical college in Shanxi province offering affordable MBBS programs.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "210+ International",
        city: "jincheng",
        tuitionRange: "low",
        established: "1958",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 129,
        name: "Jingdezhen Medical College",
        slug: "jingdezhen-medical-college",
        location: "Jingdezhen",
        image: "images/jingdezhen.webp",
        description: "Medical college in Jiangxi province providing quality medical education.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "220+ International",
        city: "jingdezhen",
        tuitionRange: "low",
        established: "1978",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 130,
        name: "Jinhua Medical College",
        slug: "jinhua-medical-college",
        location: "Jinhua",
        image: "images/jinhua.webp",
        description: "Medical college in Zhejiang province offering comprehensive MBBS programs.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "250+ International",
        city: "jinhua",
        tuitionRange: "low",
        established: "1915",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 131,
        name: "Jinzhong Medical College",
        slug: "jinzhong-medical-college",
        location: "Jinzhong",
        image: "images/jinzhong.webp",
        description: "Medical college in Shanxi province providing affordable medical education.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "200+ International",
        city: "jinzhong",
        tuitionRange: "low",
        established: "1958",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 132,
        name: "Kaifeng Medical College",
        slug: "kaifeng-medical-college",
        location: "Kaifeng",
        image: "images/kaifeng-med.webp",
        description: "Medical college in Henan's ancient capital offering quality MBBS programs.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "260+ International",
        city: "kaifeng",
        tuitionRange: "low",
        established: "1929",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 133,
        name: "Kashgar Medical College",
        slug: "kashgar-medical-college",
        location: "Kashgar",
        image: "images/kashgar.webp",
        description: "Medical college in Xinjiang providing medical education for western regions.",
        tuition: "21,000 CNY/year",
        duration: "6 years",
        students: "190+ International",
        city: "kashgar",
        tuitionRange: "low",
        established: "1956",
        rating: 3.7,
        moeListed: false
    },
    {
        id: 134,
        name: "Laibin Medical College",
        slug: "laibin-medical-college",
        location: "Laibin",
        image: "images/laibin.webp",
        description: "Medical college in Guangxi province offering affordable MBBS programs.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "180+ International",
        city: "laibin",
        tuitionRange: "low",
        established: "1978",
        rating: 3.7,
        moeListed: false
    },
    {
        id: 135,
        name: "Langfang Medical College",
        slug: "langfang-medical-college",
        location: "Langfang",
        image: "images/langfang.webp",
        description: "Medical college in Hebei province providing comprehensive medical education.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "270+ International",
        city: "langfang",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 136,
        name: "Leshan Medical College",
        slug: "leshan-medical-college",
        location: "Leshan",
        image: "images/leshan.webp",
        description: "Medical college in Sichuan province offering quality MBBS programs.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "240+ International",
        city: "leshan",
        tuitionRange: "low",
        established: "1951",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 137,
        name: "Liangshan Medical College",
        slug: "liangshan-medical-college",
        location: "Liangshan",
        image: "images/liangshan.webp",
        description: "Medical college in Sichuan providing medical education for ethnic regions.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "170+ International",
        city: "liangshan",
        tuitionRange: "low",
        established: "1978",
        rating: 3.7,
        moeListed: false
    },
    {
        id: 138,
        name: "Liaocheng Medical College",
        slug: "liaocheng-medical-college",
        location: "Liaocheng",
        image: "images/liaocheng.webp",
        description: "Medical college in Shandong province offering comprehensive MBBS programs.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "260+ International",
        city: "liaocheng",
        tuitionRange: "low",
        established: "1950",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 139,
        name: "Lijiang Medical College",
        slug: "lijiang-medical-college",
        location: "Lijiang",
        image: "images/lijiang.webp",
        description: "Medical college in Yunnan's tourist city offering quality medical education.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "220+ International",
        city: "lijiang",
        tuitionRange: "low",
        established: "1958",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 140,
        name: "Lincang Medical College",
        slug: "lincang-medical-college",
        location: "Lincang",
        image: "images/lincang.webp",
        description: "Medical college in Yunnan province providing affordable medical education.",
        tuition: "21,000 CNY/year",
        duration: "6 years",
        students: "160+ International",
        city: "lincang",
        tuitionRange: "low",
        established: "1978",
        rating: 3.6,
        moeListed: false
    },
    {
        id: 141,
        name: "Lishui Medical College",
        slug: "lishui-medical-college",
        location: "Lishui",
        image: "images/lishui.webp",
        description: "Medical college in Zhejiang province offering quality MBBS programs.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "230+ International",
        city: "lishui",
        tuitionRange: "low",
        established: "1907",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 142,
        name: "Liupanshui Medical College",
        slug: "liupanshui-medical-college",
        location: "Liupanshui",
        image: "images/liupanshui.webp",
        description: "Medical college in Guizhou province providing comprehensive medical education.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "190+ International",
        city: "liupanshui",
        tuitionRange: "low",
        established: "1978",
        rating: 3.7,
        moeListed: false
    },
    {
        id: 143,
        name: "Longyan Medical College",
        slug: "longyan-medical-college",
        location: "Longyan",
        image: "images/longyan.webp",
        description: "Medical college in Fujian province offering affordable MBBS programs.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "210+ International",
        city: "longyan",
        tuitionRange: "low",
        established: "1958",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 144,
        name: "Loudi Medical College",
        slug: "loudi-medical-college",
        location: "Loudi",
        image: "images/loudi-med.webp",
        description: "Medical college in Hunan province providing quality medical education.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "220+ International",
        city: "loudi",
        tuitionRange: "low",
        established: "1978",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 145,
        name: "Luzhou Medical College",
        slug: "luzhou-medical-college",
        location: "Luzhou",
        image: "images/luzhou-med.webp",
        description: "Medical college in Sichuan province offering comprehensive MBBS programs.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "250+ International",
        city: "luzhou",
        tuitionRange: "low",
        established: "1951",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 146,
        name: "Maanshan Medical College",
        slug: "maanshan-medical-college",
        location: "Maanshan",
        image: "images/maanshan.webp",
        description: "Medical college in Anhui province providing quality medical education.",
        tuition: "24,000 CNY/year",
        duration: "6 years",
        students: "230+ International",
        city: "maanshan",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 147,
        name: "Meishan Medical College",
        slug: "meishan-medical-college",
        location: "Meishan",
        image: "images/meishan.webp",
        description: "Medical college in Sichuan province offering affordable MBBS programs.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "210+ International",
        city: "meishan",
        tuitionRange: "low",
        established: "1978",
        rating: 3.8,
        moeListed: false
    },
    {
        id: 148,
        name: "Mianyang Medical College",
        slug: "mianyang-medical-college",
        location: "Mianyang",
        image: "images/mianyang.webp",
        description: "Medical college in Sichuan's science city offering quality medical education.",
        tuition: "25,000 CNY/year",
        duration: "6 years",
        students: "270+ International",
        city: "mianyang",
        tuitionRange: "low",
        established: "1951",
        rating: 4.0,
        moeListed: false
    },
    {
        id: 149,
        name: "Mudanjiang Medical College",
        slug: "mudanjiang-medical-college",
        location: "Mudanjiang",
        image: "images/mudanjiang-med.webp",
        description: "Medical college in Heilongjiang province providing comprehensive medical education.",
        tuition: "23,000 CNY/year",
        duration: "6 years",
        students: "240+ International",
        city: "mudanjiang",
        tuitionRange: "low",
        established: "1958",
        rating: 3.9,
        moeListed: false
    },
    {
        id: 150,
        name: "Nanping Medical College",
        slug: "nanping-medical-college",
        location: "Nanping",
        image: "images/nanping.webp",
        description: "Medical college in Fujian province offering affordable MBBS programs.",
        tuition: "22,000 CNY/year",
        duration: "6 years",
        students: "200+ International",
        city: "nanping",
        tuitionRange: "low",
        established: "1958",
        rating: 3.8,
        moeListed: false
    }
];

const tableColumns = [
    { key: 'name', label: 'University', sortable: true, alwaysVisible: true },
    { key: 'location', label: 'City / Province', sortable: true },
    { key: 'tuition', label: 'Annual Tuition', sortable: true },
    { key: 'duration', label: 'Program Duration', sortable: true },
    { key: 'students', label: 'International Intake', sortable: true },
    { key: 'moeListed', label: 'MOE Status', sortable: true },
    { key: 'rating', label: 'Rating', sortable: true },
    { key: 'established', label: 'Founded', sortable: true }
];

const tableState = {
    sortKey: 'name',
    sortDirection: 'asc',
    visibleColumns: {}
};

tableColumns.forEach(column => {
    tableState.visibleColumns[column.key] = true;
    if (column.alwaysVisible) {
        tableState.visibleColumns[column.key] = true;
    }
});

// Ensure all universities have slugs
universities.forEach(uni => {
    if (!uni.slug) {
        uni.slug = generateSlug(uni.name);
    }
});

// Pagination variables
let universitiesPerPage = 12;
let currentPage = 1;
let filteredUniversities = [...universities];

// Get unique cities for dropdown
const uniqueCities = [...new Set(universities.map(u => u.city))].sort();

// Initialize universities page
document.addEventListener('DOMContentLoaded', function() {
    // Populate university dropdown
    populateUniversityDropdown();
    
    // Populate city filter with all cities
    populateCityFilter();
    
    renderUniversities();
    initUniversitiesDataTable();
    
    // Search functionality
    const searchInput = document.getElementById('search-input');
    const clearSearchBtn = document.getElementById('clear-search');
    const debouncedFilter = debounce(filterUniversities, 300);
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            // Show/hide clear button
            if (clearSearchBtn) {
                if (this.value.length > 0) {
                    clearSearchBtn.style.display = 'block';
                } else {
                    clearSearchBtn.style.display = 'none';
                }
            }
            debouncedFilter();
        });
    }
    
    // Clear search functionality
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            this.style.display = 'none';
            filterUniversities();
        });
    }

    // Filter functionality
    const cityFilter = document.getElementById('city-filter');
    if (cityFilter) {
        cityFilter.addEventListener('change', filterUniversities);
    }
    
    const tuitionFilter = document.getElementById('tuition-filter');
    if (tuitionFilter) {
        tuitionFilter.addEventListener('change', filterUniversities);
    }
    
    const durationFilter = document.getElementById('duration-filter');
    if (durationFilter) {
        durationFilter.addEventListener('change', filterUniversities);
    }
    
    const universityFilter = document.getElementById('university-filter');
    if (universityFilter) {
        universityFilter.addEventListener('change', filterUniversities);
    }
    
    // MOE filter - Add this section
    const moeFilter = document.getElementById('moe-filter');
    if (moeFilter) {
        moeFilter.addEventListener('change', filterUniversities);
    }
    
    // Page size selector
    const pageSizeSelector = document.getElementById('page-size-selector');
    if (pageSizeSelector) {
        pageSizeSelector.addEventListener('change', function() {
            universitiesPerPage = parseInt(this.value);
            currentPage = 1;
            renderUniversities();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Populate university dropdown
function populateUniversityDropdown() {
    const dropdown = document.getElementById('university-filter');
    if (!dropdown) return;
    
    // Sort universities alphabetically
    const sortedUniversities = [...universities].sort((a, b) => 
        a.name.localeCompare(b.name)
    );
    
    sortedUniversities.forEach(university => {
        const option = document.createElement('option');
        option.value = university.id;
        option.textContent = university.name;
        dropdown.appendChild(option);
    });
}

// Populate city filter with all cities
function populateCityFilter() {
    const cityFilter = document.getElementById('city-filter');
    if (!cityFilter) return;
    
    // Clear existing options except "All Cities"
    while (cityFilter.children.length > 1) {
        cityFilter.removeChild(cityFilter.lastChild);
    }
    
    uniqueCities.forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        option.textContent = city.charAt(0).toUpperCase() + city.slice(1);
        cityFilter.appendChild(option);
    });
}

// Function to render universities for current page
function renderUniversities() {
    const container = document.getElementById('universities-container');
    if (!container) return;
    
    container.innerHTML = '';
    
    // Show loading state
    container.innerHTML = '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Loading universities...</div>';
    
    // Simulate slight delay for better UX
    setTimeout(() => {
        container.innerHTML = '';
        
        const startIndex = (currentPage - 1) * universitiesPerPage;
        const endIndex = startIndex + universitiesPerPage;
        const universitiesToShow = filteredUniversities.slice(startIndex, endIndex);
        
        if (universitiesToShow.length === 0) {
            document.getElementById('no-results').style.display = 'block';
            document.getElementById('pagination').style.display = 'none';
        } else {
            document.getElementById('no-results').style.display = 'none';
            document.getElementById('pagination').style.display = 'flex';
            
            universitiesToShow.forEach(university => {
                const card = document.createElement('div');
                card.className = 'university-card-page scale-on-hover';
                card.innerHTML = `
                    <a href="${university.slug}.html" class="card-link">
                        <div class="university-img" style="background-image: url('${university.image}')">
                            <div class="university-badge-overlay">WHO Approved</div>
                            ${university.moeListed ? '<div class="moe-badge-overlay">MOE Listed</div>' : ''}
                        </div>
                        <div class="university-info">
                            <h3>${university.name}</h3>
                            <div class="university-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>${university.location}</span>
                            </div>
                            <p>${university.description}</p>
                            <div class="university-features">
                                <div class="feature">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>${university.tuition}</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-clock"></i>
                                    <span>${university.duration}</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-user-graduate"></i>
                                    <span>${university.students}</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-check-circle"></i>
                                    <span>${university.moeListed ? 'MOE Listed' : 'Non-MOE Listed'}</span>
                                </div>
                            </div>
                            <div class="university-rating-card">
                                <div class="stars">
                                    ${generateStars(university.rating)}
                                </div>
                                <span>${university.rating}/5</span>
                            </div>
                            <div class="card-action">
                                <span class="btn btn-outline">View Details <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>
                `;
                container.appendChild(card);
            });
        }
        
        updateResultsCount();
        renderPagination();
        refreshUniversitiesTable();
    }, 100);
}

// Generate star rating HTML
function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    let starsHTML = '';
    
    for (let i = 0; i < fullStars; i++) {
        starsHTML += '<i class="fas fa-star"></i>';
    }
    
    if (hasHalfStar) {
        starsHTML += '<i class="fas fa-star-half-alt"></i>';
    }
    
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    for (let i = 0; i < emptyStars; i++) {
        starsHTML += '<i class="far fa-star"></i>';
    }
    
    return starsHTML;
}

// Function to update results count
function updateResultsCount() {
    const startIndex = (currentPage - 1) * universitiesPerPage;
    const endIndex = Math.min(startIndex + universitiesPerPage, filteredUniversities.length);
    const currentCount = filteredUniversities.length > 0 ? endIndex : 0;
    
    document.getElementById('current-count').textContent = currentCount;
    document.getElementById('total-count').textContent = filteredUniversities.length;
}

// Enhanced pagination with jump to page
function renderPagination() {
    const totalPages = Math.ceil(filteredUniversities.length / universitiesPerPage);
    const paginationContainer = document.getElementById('pagination');
    if (!paginationContainer) return;
    
    paginationContainer.innerHTML = '';
    
    if (totalPages <= 1) {
        paginationContainer.style.display = 'none';
        return;
    }
    
    paginationContainer.style.display = 'flex';
    
    // Previous button
    const prevButton = document.createElement('a');
    prevButton.href = '#';
    prevButton.className = 'pagination-btn';
    prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
    if (currentPage === 1) {
        prevButton.classList.add('disabled');
    }
    prevButton.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            renderUniversities();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    paginationContainer.appendChild(prevButton);
    
    // First page
    if (currentPage > 3) {
        const firstPage = createPageLink(1);
        paginationContainer.appendChild(firstPage);
        
        if (currentPage > 4) {
            const ellipsis = document.createElement('span');
            ellipsis.className = 'pagination-ellipsis';
            ellipsis.textContent = '...';
            paginationContainer.appendChild(ellipsis);
        }
    }
    
    // Page numbers around current page
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);
    
    for (let i = startPage; i <= endPage; i++) {
        paginationContainer.appendChild(createPageLink(i));
    }
    
    // Last page
    if (currentPage < totalPages - 2) {
        if (currentPage < totalPages - 3) {
            const ellipsis = document.createElement('span');
            ellipsis.className = 'pagination-ellipsis';
            ellipsis.textContent = '...';
            paginationContainer.appendChild(ellipsis);
        }
        
        const lastPage = createPageLink(totalPages);
        paginationContainer.appendChild(lastPage);
    }
    
    // Next button
    const nextButton = document.createElement('a');
    nextButton.href = '#';
    nextButton.className = 'pagination-btn';
    nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
    if (currentPage === totalPages) {
        nextButton.classList.add('disabled');
    }
    nextButton.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage < totalPages) {
            currentPage++;
            renderUniversities();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    paginationContainer.appendChild(nextButton);
    
    // Jump to page input
    const jumpContainer = document.createElement('div');
    jumpContainer.className = 'pagination-jump';
    jumpContainer.innerHTML = `
        <span>Go to:</span>
        <input type="number" id="jump-to-page" min="1" max="${totalPages}" value="${currentPage}">
        <button onclick="jumpToPage()">Go</button>
    `;
    paginationContainer.appendChild(jumpContainer);
}

// Create page link
function createPageLink(pageNum) {
    const pageLink = document.createElement('a');
    pageLink.href = '#';
    pageLink.className = 'pagination-link';
    pageLink.textContent = pageNum;
    if (pageNum === currentPage) {
        pageLink.classList.add('active');
    }
    pageLink.addEventListener('click', (e) => {
        e.preventDefault();
        currentPage = pageNum;
        renderUniversities();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    return pageLink;
}

// Jump to page function
function jumpToPage() {
    const input = document.getElementById('jump-to-page');
    if (!input) return;
    
    const page = parseInt(input.value);
    const totalPages = Math.ceil(filteredUniversities.length / universitiesPerPage);
    
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        renderUniversities();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
        alert(`Please enter a page number between 1 and ${totalPages}`);
    }
}

// Make jumpToPage available globally
window.jumpToPage = jumpToPage;

// Function to filter universities
function filterUniversities() {
    const searchInput = document.getElementById('search-input');
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const cityFilterElement = document.getElementById('city-filter');
    const cityFilter = cityFilterElement ? cityFilterElement.value : '';
    const tuitionFilterElement = document.getElementById('tuition-filter');
    const tuitionFilter = tuitionFilterElement ? tuitionFilterElement.value : '';
    const durationFilterElement = document.getElementById('duration-filter');
    const durationFilter = durationFilterElement ? durationFilterElement.value : '';
    const universityFilterElement = document.getElementById('university-filter');
    const universityFilter = universityFilterElement ? universityFilterElement.value : '';
    const moeFilterElement = document.getElementById('moe-filter');
    const moeFilter = moeFilterElement ? moeFilterElement.value : '';
    
    filteredUniversities = universities.filter(university => {
        // Search filter
        const matchesSearch = !searchTerm || 
            university.name.toLowerCase().includes(searchTerm) || 
            university.location.toLowerCase().includes(searchTerm) ||
            university.description.toLowerCase().includes(searchTerm);
        
        // University filter
        const matchesUniversity = !universityFilter || university.id.toString() === universityFilter;
        
        // City filter
        const matchesCity = !cityFilter || university.city === cityFilter;
        
        // Tuition filter
        let matchesTuition = true;
        if (tuitionFilter === 'low') {
            matchesTuition = university.tuitionRange === 'low';
        } else if (tuitionFilter === 'medium') {
            matchesTuition = university.tuitionRange === 'medium';
        } else if (tuitionFilter === 'high') {
            matchesTuition = university.tuitionRange === 'high';
        }
        
        // Duration filter
        let matchesDuration = true;
        if (durationFilter) {
            matchesDuration = university.duration.includes(durationFilter);
        }
        
        // MOE filter
        let matchesMoe = true;
        if (moeFilter === 'moe-listed') {
            matchesMoe = university.moeListed === true;
        } else if (moeFilter === 'non-moe') {
            matchesMoe = university.moeListed === false;
        }
        
        return matchesSearch && matchesUniversity && matchesCity && matchesTuition && matchesDuration && matchesMoe;
    });
    
    currentPage = 1;
    renderUniversities();
    refreshUniversitiesTable();
}

function refreshUniversitiesTable() {
    renderUniversitiesTable();
    updateTableStats();
}

function initUniversitiesDataTable() {
    const columnControls = document.getElementById('columnVisibilityControls');

    if (columnControls) {
        columnControls.innerHTML = '<span>Toggle columns:</span>';
        tableColumns.forEach(column => {
            const label = document.createElement('label');
            label.innerHTML = `
                <input type="checkbox" data-column="${column.key}" ${tableState.visibleColumns[column.key] ? 'checked' : ''} ${column.alwaysVisible ? 'disabled' : ''}>
                ${column.label}
            `;
            columnControls.appendChild(label);
        });

        columnControls.querySelectorAll('input[type="checkbox"]').forEach(input => {
            input.addEventListener('change', (event) => {
                const columnKey = event.target.getAttribute('data-column');
                if (tableColumns.find(col => col.key === columnKey && col.alwaysVisible)) {
                    event.target.checked = true;
                    return;
                }
                tableState.visibleColumns[columnKey] = event.target.checked;
                renderUniversitiesTable();
            });
        });
    }

    refreshUniversitiesTable();
}

function renderUniversitiesTable() {
    const tableHead = document.getElementById('universitiesTableHead');
    const tableBody = document.getElementById('universitiesTableBody');
    const wrapper = document.querySelector('.data-table-wrapper');
    const emptyState = document.getElementById('tableEmptyState');

    if (!tableHead || !tableBody || !wrapper) return;

    const visibleColumns = tableColumns.filter(column => tableState.visibleColumns[column.key]);

    if (visibleColumns.length === 0) {
        if (emptyState) emptyState.style.display = 'block';
        tableHead.innerHTML = '';
        tableBody.innerHTML = '';
        return;
    } else if (emptyState) {
        emptyState.style.display = 'none';
    }

    if (!filteredUniversities.length) {
        tableHead.innerHTML = `
            <tr>
                ${visibleColumns.map(column => `<th>${column.label}</th>`).join('')}
            </tr>
        `;
        tableBody.innerHTML = '';
        if (emptyState) emptyState.style.display = 'block';
        return;
    } else if (emptyState) {
        emptyState.style.display = 'none';
    }

    tableHead.innerHTML = `
        <tr>
            ${visibleColumns.map(column => `
                <th
                    ${column.sortable ? 'class="sortable ' + (tableState.sortKey === column.key ? 'sorted-' + tableState.sortDirection : '') + '"' : ''}
                    data-column="${column.key}"
                >
                    ${column.label}
                    ${column.sortable ? '<span class="sort-icon"></span>' : ''}
                </th>
            `).join('')}
        </tr>
    `;

    tableHead.querySelectorAll('th.sortable').forEach(header => {
        header.addEventListener('click', () => {
            const columnKey = header.getAttribute('data-column');
            if (tableState.sortKey === columnKey) {
                tableState.sortDirection = tableState.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                tableState.sortKey = columnKey;
                tableState.sortDirection = 'asc';
            }
            renderUniversitiesTable();
        });
    });

    const sortedData = [...filteredUniversities].sort((a, b) => {
        const valueA = getComparableValue(a, tableState.sortKey);
        const valueB = getComparableValue(b, tableState.sortKey);

        if (valueA < valueB) return tableState.sortDirection === 'asc' ? -1 : 1;
        if (valueA > valueB) return tableState.sortDirection === 'asc' ? 1 : -1;
        return 0;
    });

    tableBody.innerHTML = sortedData.map(university => `
        <tr>
            ${visibleColumns.map(column => `<td>${getCellTemplate(university, column.key)}</td>`).join('')}
        </tr>
    `).join('');
}

function getComparableValue(university, key) {
    switch (key) {
        case 'tuition':
            return parseInt(university.tuition.replace(/[^0-9]/g, ''), 10) || 0;
        case 'duration':
            return parseInt(university.duration, 10) || 0;
        case 'students':
            return parseInt(university.students.replace(/[^0-9]/g, ''), 10) || 0;
        case 'moeListed':
            return university.moeListed ? 1 : 0;
        case 'rating':
            return university.rating || 0;
        case 'established':
            return parseInt(university.established, 10) || 0;
        default:
            return (university[key] || '').toString().toLowerCase();
    }
}

function getCellTemplate(university, key) {
    switch (key) {
        case 'name':
            return `
                <div class="table-university">
                    <span class="title">${university.name}</span>
                    <span class="subtitle">${university.location}</span>
                </div>
            `;
        case 'moeListed':
            return `<span class="badge ${university.moeListed ? 'badge-success' : 'badge-muted'}">
                ${university.moeListed ? 'MOE Listed' : 'Non-MOE'}
            </span>`;
        case 'rating':
            return `
                <div class="rating-cell">
                    <strong>${university.rating.toFixed(1)}</strong>
                    <div class="stars">${generateStars(university.rating)}</div>
                </div>
            `;
        default:
            return university[key] || '—';
    }
}

function updateTableStats() {
    const totalCount = document.getElementById('tableTotalCount');
    const moeCount = document.getElementById('tableMoeCount');
    const avgTuition = document.getElementById('tableAvgTuition');

    if (!totalCount || !moeCount || !avgTuition) return;

    const total = filteredUniversities.length;
    const moeListedTotal = filteredUniversities.filter(uni => uni.moeListed).length;
    const tuitionValues = filteredUniversities.map(uni => parseInt(uni.tuition.replace(/[^0-9]/g, ''), 10) || 0);
    const averageTuition = tuitionValues.length
        ? Math.round(tuitionValues.reduce((acc, value) => acc + value, 0) / tuitionValues.length)
        : 0;

    totalCount.textContent = total;
    moeCount.textContent = moeListedTotal;
    avgTuition.textContent = averageTuition ? `${averageTuition.toLocaleString()} CNY` : 'N/A';
}

// Export universities data for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { universities, generateSlug };
}

// Make universities available globally for header dropdown
window.universities = universities;