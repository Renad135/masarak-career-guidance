<?php
// Career Paths Data for MASARAK
// This file contains career paths, job examples, and skills for each major

$career_paths_data = [
    'MED' => [ // Medicine
        'career_paths' => [
            'ar' => ['الممارسة السريرية', 'البحث الطبي', 'الصحة العامة', 'التعليم الطبي', 'إدارة الرعاية الصحية'],
            'en' => ['Clinical Practice', 'Medical Research', 'Public Health', 'Medical Education', 'Healthcare Administration']
        ],
        'job_examples' => [
            'ar' => ['طبيب عام', 'طبيب أطفال', 'جراح', 'طبيب باطني', 'طبيب طب الطوارئ', 'أخصائي أشعة', 'طبيب تخدير', 'طبيب أمراض', 'باحث طبي', 'أخصائي صحة عامة'],
            'en' => ['General Practitioner (GP)', 'Pediatrician', 'Surgeon', 'Internist', 'Emergency Medicine Physician', 'Radiologist', 'Anesthesiologist', 'Pathologist', 'Medical Research Scientist', 'Public Health Specialist']
        ],
        'required_skills' => [
            'ar' => ['التشخيص الطبي', 'المهارات السريرية', 'التواصل مع المرضى', 'اتخاذ القرارات', 'العمل تحت الضغط', 'القيادة', 'البحث العلمي'],
            'en' => ['Medical Diagnosis', 'Clinical Skills', 'Patient Communication', 'Decision Making', 'Working Under Pressure', 'Leadership', 'Scientific Research']
        ],
        'work_environments' => [
            'ar' => ['المستشفيات', 'العيادات الخاصة', 'مراكز البحث', 'المؤسسات الصحية', 'الجامعات'],
            'en' => ['Hospitals', 'Private Clinics', 'Research Centers', 'Health Institutions', 'Universities']
        ],
        'image' => 'medicine.jpg'
    ],
    'CS' => [ // Computer Science
        'career_paths' => [
            'ar' => ['تطوير البرمجيات', 'أمن المعلومات', 'الذكاء الاصطناعي', 'علوم البيانات', 'هندسة الأنظمة'],
            'en' => ['Software Development', 'Cybersecurity', 'Artificial Intelligence', 'Data Science', 'Systems Engineering']
        ],
        'job_examples' => [
            'ar' => ['مطور برمجيات', 'مهندس برمجيات', 'مطور تطبيقات', 'مطور ويب', 'مطور ألعاب', 'أخصائي أمن معلومات', 'عالم بيانات', 'مهندس ذكاء اصطناعي'],
            'en' => ['Software Developer', 'Software Engineer', 'Application Developer', 'Web Developer', 'Game Developer', 'Cybersecurity Specialist', 'Data Scientist', 'AI Engineer']
        ],
        'required_skills' => [
            'ar' => ['البرمجة', 'حل المشكلات', 'الخوارزميات', 'هياكل البيانات', 'قواعد البيانات', 'التعاون', 'التعلم المستمر'],
            'en' => ['Programming', 'Problem Solving', 'Algorithms', 'Data Structures', 'Databases', 'Collaboration', 'Continuous Learning']
        ],
        'work_environments' => [
            'ar' => ['شركات التقنية', 'الاستوديوهات', 'المؤسسات المالية', 'الشركات الناشئة', 'العمل الحر'],
            'en' => ['Tech Companies', 'Studios', 'Financial Institutions', 'Startups', 'Freelancing']
        ],
        'image' => 'computer-science.jpg'
    ],
    'NUR' => [ // Nursing
        'career_paths' => [
            'ar' => ['التمريض السريري', 'تمريض الأطفال', 'تمريض الطوارئ', 'تمريض العناية المركزة', 'إدارة التمريض'],
            'en' => ['Clinical Nursing', 'Pediatric Nursing', 'Emergency Nursing', 'Intensive Care Nursing', 'Nursing Management']
        ],
        'job_examples' => [
            'ar' => ['ممرض عام', 'ممرض أطفال', 'ممرض طوارئ', 'ممرض عناية مركزة', 'ممرض جراحة', 'ممرض صحة نفسية', 'ممرض صحة مجتمع', 'مدير تمريض'],
            'en' => ['General Nurse', 'Pediatric Nurse', 'Emergency Nurse', 'ICU Nurse', 'Surgical Nurse', 'Mental Health Nurse', 'Community Health Nurse', 'Nursing Manager']
        ],
        'required_skills' => [
            'ar' => ['الرعاية التمريضية', 'التواصل', 'التعاطف', 'العمل الجماعي', 'إدارة الوقت', 'المراقبة', 'التوثيق'],
            'en' => ['Nursing Care', 'Communication', 'Empathy', 'Teamwork', 'Time Management', 'Monitoring', 'Documentation']
        ],
        'work_environments' => [
            'ar' => ['المستشفيات', 'العيادات', 'مراكز الرعاية', 'المدارس', 'المنازل'],
            'en' => ['Hospitals', 'Clinics', 'Care Centers', 'Schools', 'Homes']
        ],
        'image' => 'nursing.jpg'
    ],
    'DEN' => [ // Dentistry
        'career_paths' => [
            'ar' => ['طب الأسنان العام', 'تقويم الأسنان', 'جراحة الفم', 'طب أسنان الأطفال', 'زراعة الأسنان'],
            'en' => ['General Dentistry', 'Orthodontics', 'Oral Surgery', 'Pediatric Dentistry', 'Dental Implants']
        ],
        'job_examples' => [
            'ar' => ['طبيب أسنان عام', 'أخصائي تقويم', 'جراح فم', 'طبيب أسنان أطفال', 'أخصائي زراعة', 'أخصائي لثة', 'أخصائي أسنان تجميلي'],
            'en' => ['General Dentist', 'Orthodontist', 'Oral Surgeon', 'Pediatric Dentist', 'Implant Specialist', 'Periodontist', 'Cosmetic Dentist']
        ],
        'required_skills' => [
            'ar' => ['المهارات اليدوية', 'الدقة', 'التواصل', 'التشخيص', 'التعامل مع المرضى', 'التركيز', 'التحديث المستمر'],
            'en' => ['Manual Dexterity', 'Precision', 'Communication', 'Diagnosis', 'Patient Handling', 'Focus', 'Continuous Updates']
        ],
        'work_environments' => [
            'ar' => ['العيادات الخاصة', 'المستشفيات', 'مراكز طب الأسنان', 'المؤسسات التعليمية'],
            'en' => ['Private Clinics', 'Hospitals', 'Dental Centers', 'Educational Institutions']
        ],
        'image' => 'dentistry.jpg'
    ],
    'CE' => [ // Civil Engineering
        'career_paths' => [
            'ar' => ['الهندسة الإنشائية', 'هندسة الطرق', 'هندسة المياه', 'هندسة البيئة', 'إدارة المشاريع'],
            'en' => ['Structural Engineering', 'Road Engineering', 'Water Engineering', 'Environmental Engineering', 'Project Management']
        ],
        'job_examples' => [
            'ar' => ['مهندس إنشائي', 'مهندس طرق', 'مهندس مياه', 'مهندس بيئة', 'مدير مشروع', 'مهندس موقع', 'مهندس تصميم', 'مهندس فحص'],
            'en' => ['Structural Engineer', 'Road Engineer', 'Water Engineer', 'Environmental Engineer', 'Project Manager', 'Site Engineer', 'Design Engineer', 'Inspection Engineer']
        ],
        'required_skills' => [
            'ar' => ['التصميم الهندسي', 'الرياضيات', 'الفيزياء', 'إدارة المشاريع', 'القيادة', 'حل المشكلات', 'البرمجيات الهندسية'],
            'en' => ['Engineering Design', 'Mathematics', 'Physics', 'Project Management', 'Leadership', 'Problem Solving', 'Engineering Software']
        ],
        'work_environments' => [
            'ar' => ['مواقع البناء', 'المكاتب الهندسية', 'الشركات الإنشائية', 'الدوائر الحكومية'],
            'en' => ['Construction Sites', 'Engineering Offices', 'Construction Companies', 'Government Departments']
        ],
        'image' => 'civil-engineering.jpg'
    ],
    'EE' => [ // Electrical Engineering
        'career_paths' => [
            'ar' => ['هندسة الطاقة', 'هندسة الإلكترونيات', 'هندسة الاتصالات', 'هندسة التحكم', 'هندسة الحاسوب'],
            'en' => ['Power Engineering', 'Electronics Engineering', 'Communications Engineering', 'Control Engineering', 'Computer Engineering']
        ],
        'job_examples' => [
            'ar' => ['مهندس كهرباء', 'مهندس إلكترونيات', 'مهندس اتصالات', 'مهندس تحكم', 'مهندس حاسوب', 'مهندس طاقة', 'مهندس شبكات'],
            'en' => ['Electrical Engineer', 'Electronics Engineer', 'Communications Engineer', 'Control Engineer', 'Computer Engineer', 'Power Engineer', 'Network Engineer']
        ],
        'required_skills' => [
            'ar' => ['الدوائر الكهربائية', 'الإلكترونيات', 'البرمجة', 'الرياضيات', 'الفيزياء', 'التصميم', 'التحليل'],
            'en' => ['Electrical Circuits', 'Electronics', 'Programming', 'Mathematics', 'Physics', 'Design', 'Analysis']
        ],
        'work_environments' => [
            'ar' => ['شركات الطاقة', 'شركات الاتصالات', 'المصانع', 'المختبرات', 'المكاتب الهندسية'],
            'en' => ['Power Companies', 'Telecommunications Companies', 'Factories', 'Laboratories', 'Engineering Offices']
        ],
        'image' => 'electrical-engineering.jpg'
    ],
    'ARCH' => [ // Architecture
        'career_paths' => [
            'ar' => ['التصميم المعماري', 'التخطيط العمراني', 'التصميم الداخلي', 'الحفاظ على التراث', 'الاستشارات المعمارية'],
            'en' => ['Architectural Design', 'Urban Planning', 'Interior Design', 'Heritage Preservation', 'Architectural Consulting']
        ],
        'job_examples' => [
            'ar' => ['مهندس معماري', 'مصمم معماري', 'مخطط عمراني', 'مصمم داخلي', 'مستشار معماري', 'مدير مشروع معماري'],
            'en' => ['Architect', 'Architectural Designer', 'Urban Planner', 'Interior Designer', 'Architectural Consultant', 'Architectural Project Manager']
        ],
        'required_skills' => [
            'ar' => ['التصميم', 'الإبداع', 'الرسم', 'البرمجيات المعمارية', 'التخطيط', 'التواصل', 'الخيال'],
            'en' => ['Design', 'Creativity', 'Drawing', 'Architectural Software', 'Planning', 'Communication', 'Imagination']
        ],
        'work_environments' => [
            'ar' => ['المكاتب المعمارية', 'الاستوديوهات', 'مواقع البناء', 'المؤسسات الحكومية'],
            'en' => ['Architectural Offices', 'Studios', 'Construction Sites', 'Government Institutions']
        ],
        'image' => 'architecture.jpg'
    ],
    'LAW' => [ // Law
        'career_paths' => [
            'ar' => ['المحاماة', 'القضاء', 'النيابة العامة', 'الاستشارات القانونية', 'القانون الدولي'],
            'en' => ['Law Practice', 'Judiciary', 'Public Prosecution', 'Legal Consulting', 'International Law']
        ],
        'job_examples' => [
            'ar' => ['محامي', 'قاضي', 'نيابة عامة', 'مستشار قانوني', 'محامي شركات', 'محامي جنائي', 'محامي مدني', 'محامي دولي'],
            'en' => ['Lawyer', 'Judge', 'Public Prosecutor', 'Legal Advisor', 'Corporate Lawyer', 'Criminal Lawyer', 'Civil Lawyer', 'International Lawyer']
        ],
        'required_skills' => [
            'ar' => ['التحليل القانوني', 'البحث', 'الكتابة', 'التواصل', 'المنطق', 'الذاكرة', 'الخطابة'],
            'en' => ['Legal Analysis', 'Research', 'Writing', 'Communication', 'Logic', 'Memory', 'Oratory']
        ],
        'work_environments' => [
            'ar' => ['المحاكم', 'المكاتب القانونية', 'الشركات', 'المؤسسات الحكومية', 'المنظمات الدولية'],
            'en' => ['Courts', 'Law Offices', 'Companies', 'Government Institutions', 'International Organizations']
        ],
        'image' => 'law.jpg'
    ],
    'IS' => [ // Information Systems
        'career_paths' => [
            'ar' => ['إدارة نظم المعلومات', 'تحليل البيانات', 'الأمن السيبراني', 'إدارة المشاريع التقنية', 'الاستشارات التقنية'],
            'en' => ['Information Systems Management', 'Data Analysis', 'Cybersecurity', 'IT Project Management', 'IT Consulting']
        ],
        'job_examples' => [
            'ar' => ['مدير نظم معلومات', 'محلل بيانات', 'أخصائي أمن معلومات', 'مدير مشاريع تقنية', 'مستشار تقني', 'محلل نظم', 'مدير قواعد بيانات'],
            'en' => ['Information Systems Manager', 'Data Analyst', 'Cybersecurity Specialist', 'IT Project Manager', 'IT Consultant', 'Systems Analyst', 'Database Administrator']
        ],
        'required_skills' => [
            'ar' => ['إدارة البيانات', 'التحليل', 'الأمن السيبراني', 'إدارة المشاريع', 'التواصل', 'حل المشكلات'],
            'en' => ['Data Management', 'Analysis', 'Cybersecurity', 'Project Management', 'Communication', 'Problem Solving']
        ],
        'work_environments' => [
            'ar' => ['الشركات', 'المؤسسات الحكومية', 'البنوك', 'شركات التقنية'],
            'en' => ['Companies', 'Government Institutions', 'Banks', 'Tech Companies']
        ],
        'image' => 'information-systems.jpg'
    ],
    'SE' => [ // Software Engineering
        'career_paths' => [
            'ar' => ['هندسة البرمجيات', 'إدارة المشاريع البرمجية', 'هندسة الجودة', 'هندسة الأنظمة', 'الاستشارات البرمجية'],
            'en' => ['Software Engineering', 'Software Project Management', 'Quality Engineering', 'Systems Engineering', 'Software Consulting']
        ],
        'job_examples' => [
            'ar' => ['مهندس برمجيات', 'مهندس أنظمة', 'مهندس جودة', 'مدير مشاريع برمجية', 'مهندس DevOps', 'مهندس اختبار', 'مهندس بنية تحتية'],
            'en' => ['Software Engineer', 'Systems Engineer', 'Quality Engineer', 'Software Project Manager', 'DevOps Engineer', 'Test Engineer', 'Infrastructure Engineer']
        ],
        'required_skills' => [
            'ar' => ['البرمجة', 'هندسة البرمجيات', 'إدارة المشاريع', 'الجودة', 'التعاون', 'التصميم'],
            'en' => ['Programming', 'Software Engineering', 'Project Management', 'Quality', 'Collaboration', 'Design']
        ],
        'work_environments' => [
            'ar' => ['شركات البرمجيات', 'الاستوديوهات', 'الشركات الناشئة', 'المؤسسات الكبيرة'],
            'en' => ['Software Companies', 'Studios', 'Startups', 'Large Corporations']
        ],
        'image' => 'software-engineering.jpg'
    ],
    'IE' => [ // Industrial Engineering
        'career_paths' => [
            'ar' => ['هندسة العمليات', 'إدارة الجودة', 'هندسة الإنتاج', 'التحسين المستمر', 'إدارة سلسلة التوريد'],
            'en' => ['Process Engineering', 'Quality Management', 'Production Engineering', 'Continuous Improvement', 'Supply Chain Management']
        ],
        'job_examples' => [
            'ar' => ['مهندس عمليات', 'مهندس جودة', 'مهندس إنتاج', 'مهندس تحسين', 'مدير سلسلة توريد', 'مهندس تخطيط', 'مهندس كفاءة'],
            'en' => ['Process Engineer', 'Quality Engineer', 'Production Engineer', 'Improvement Engineer', 'Supply Chain Manager', 'Planning Engineer', 'Efficiency Engineer']
        ],
        'required_skills' => [
            'ar' => ['التحليل', 'التحسين', 'إدارة العمليات', 'الجودة', 'التخطيط', 'القيادة'],
            'en' => ['Analysis', 'Optimization', 'Process Management', 'Quality', 'Planning', 'Leadership']
        ],
        'work_environments' => [
            'ar' => ['المصانع', 'شركات التصنيع', 'المؤسسات الصناعية', 'شركات الخدمات'],
            'en' => ['Factories', 'Manufacturing Companies', 'Industrial Institutions', 'Service Companies']
        ],
        'image' => 'industrial-engineering.jpg'
    ],
    'FD' => [ // Fashion Design
        'career_paths' => [
            'ar' => ['تصميم الأزياء', 'إنتاج الأزياء', 'تسويق الأزياء', 'إدارة العلامات التجارية', 'الاستشارات الأزياء'],
            'en' => ['Fashion Design', 'Fashion Production', 'Fashion Marketing', 'Brand Management', 'Fashion Consulting']
        ],
        'job_examples' => [
            'ar' => ['مصمم أزياء', 'مصمم أزياء راقية', 'مصمم أزياء رجالية', 'مصمم أزياء نسائية', 'مدير علامة تجارية', 'مستشار أزياء', 'منسق أزياء'],
            'en' => ['Fashion Designer', 'Haute Couture Designer', 'Menswear Designer', 'Womenswear Designer', 'Brand Manager', 'Fashion Consultant', 'Stylist']
        ],
        'required_skills' => [
            'ar' => ['الإبداع', 'التصميم', 'الرسم', 'الخياطة', 'التسويق', 'الاتجاهات', 'التواصل'],
            'en' => ['Creativity', 'Design', 'Drawing', 'Sewing', 'Marketing', 'Trends', 'Communication']
        ],
        'work_environments' => [
            'ar' => ['بيوت الأزياء', 'الاستوديوهات', 'المتاجر', 'المعارض', 'العمل الحر'],
            'en' => ['Fashion Houses', 'Studios', 'Stores', 'Shows', 'Freelancing']
        ],
        'image' => 'fashion-design.jpg'
    ],
    'TH' => [ // Tourism and Hospitality
        'career_paths' => [
            'ar' => ['إدارة الفنادق', 'إدارة المطاعم', 'إدارة السياحة', 'التسويق السياحي', 'التخطيط السياحي'],
            'en' => ['Hotel Management', 'Restaurant Management', 'Tourism Management', 'Tourism Marketing', 'Tourism Planning']
        ],
        'job_examples' => [
            'ar' => ['مدير فندق', 'مدير مطعم', 'منظم رحلات', 'مرشد سياحي', 'مدير تسويق سياحي', 'مدير ضيافة', 'منسق فعاليات'],
            'en' => ['Hotel Manager', 'Restaurant Manager', 'Travel Organizer', 'Tour Guide', 'Tourism Marketing Manager', 'Hospitality Manager', 'Event Coordinator']
        ],
        'required_skills' => [
            'ar' => ['إدارة الضيافة', 'التواصل', 'الخدمة', 'التخطيط', 'التسويق', 'اللغات', 'التعامل مع العملاء'],
            'en' => ['Hospitality Management', 'Communication', 'Service', 'Planning', 'Marketing', 'Languages', 'Customer Service']
        ],
        'work_environments' => [
            'ar' => ['الفنادق', 'المطاعم', 'وكالات السفر', 'المنتجعات', 'المؤسسات السياحية'],
            'en' => ['Hotels', 'Restaurants', 'Travel Agencies', 'Resorts', 'Tourism Institutions']
        ],
        'image' => 'tourism.jpg'
    ],
    'TM' => [ // Tourism Management
        'career_paths' => [
            'ar' => ['إدارة السياحة', 'التخطيط السياحي', 'التسويق السياحي', 'إدارة الوجهات', 'الاستشارات السياحية'],
            'en' => ['Tourism Management', 'Tourism Planning', 'Tourism Marketing', 'Destination Management', 'Tourism Consulting']
        ],
        'job_examples' => [
            'ar' => ['مدير سياحة', 'مخطط سياحي', 'مدير تسويق سياحي', 'مدير وجهة سياحية', 'مستشار سياحي', 'منظم فعاليات سياحية'],
            'en' => ['Tourism Manager', 'Tourism Planner', 'Tourism Marketing Manager', 'Destination Manager', 'Tourism Consultant', 'Tourism Event Organizer']
        ],
        'required_skills' => [
            'ar' => ['إدارة السياحة', 'التخطيط', 'التسويق', 'التحليل', 'التواصل', 'اللغات'],
            'en' => ['Tourism Management', 'Planning', 'Marketing', 'Analysis', 'Communication', 'Languages']
        ],
        'work_environments' => [
            'ar' => ['المؤسسات السياحية', 'وكالات السفر', 'المؤسسات الحكومية', 'المنتجعات'],
            'en' => ['Tourism Institutions', 'Travel Agencies', 'Government Institutions', 'Resorts']
        ],
        'image' => 'tourism-management.jpg'
    ]
];

// Function to get career data for a major
function getCareerData($major_code, $lang = 'ar') {
    global $career_paths_data;
    if (isset($career_paths_data[$major_code])) {
        $data = $career_paths_data[$major_code];
        return [
            'career_paths' => $data['career_paths'][$lang] ?? $data['career_paths']['en'],
            'job_examples' => $data['job_examples'][$lang] ?? $data['job_examples']['en'],
            'required_skills' => $data['required_skills'][$lang] ?? $data['required_skills']['en'],
            'work_environments' => $data['work_environments'][$lang] ?? $data['work_environments']['en'],
            'image' => $data['image'] ?? 'default.jpg'
        ];
    }
    return null;
}

// Function to get major code from major name
function getMajorCode($major_name_ar, $major_name_en) {
    $major_codes = [
        'طب' => 'MED', 'Medicine' => 'MED',
        'علوم الحاسب' => 'CS', 'Computer Science' => 'CS',
        'تمريض' => 'NUR', 'Nursing' => 'NUR',
        'أسنان' => 'DEN', 'Dentistry' => 'DEN',
        'هندسة مدنية' => 'CE', 'Civil Engineering' => 'CE',
        'هندسة كهربائية' => 'EE', 'Electrical Engineering' => 'EE',
        'عمارة' => 'ARCH', 'Architecture' => 'ARCH',
        'قانون' => 'LAW', 'Law' => 'LAW',
        'نظم المعلومات' => 'IS', 'Information Systems' => 'IS',
        'هندسة البرمجيات' => 'SE', 'Software Engineering' => 'SE',
        'هندسة صناعية' => 'IE', 'Industrial Engineering' => 'IE',
        'تصميم أزياء' => 'FD', 'Fashion Design' => 'FD',
        'سياحة وإدارة ضيافة' => 'TH', 'Tourism and Hospitality Management' => 'TH',
        'إدارة سياحية' => 'TM', 'Tourism Management' => 'TM'
    ];
    
    return $major_codes[$major_name_ar] ?? $major_codes[$major_name_en] ?? 'CS';
}
?>
