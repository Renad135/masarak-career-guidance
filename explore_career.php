<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/translations.php';
require_once __DIR__ . '/includes/career_paths.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . '/login.php');
}

$lang = getCurrentLanguage();

// Get major name from URL parameter
$major_name_ar = isset($_GET['major_ar']) ? sanitizeInput($_GET['major_ar']) : '';
$major_name_en = isset($_GET['major_en']) ? sanitizeInput($_GET['major_en']) : '';

if (empty($major_name_ar) && empty($major_name_en)) {
    redirect(SITE_URL . '/results.php');
}

// Career options from recommendation model (e.g. rec) - comma-separated job/career names
// When present, we use ONLY this for career paths/jobs (each recommendation has its own Explore page)
$career_options_param = isset($_GET['career_options']) ? trim(sanitizeInput($_GET['career_options'])) : '';

if ($career_options_param !== '') {
    // Build career data entirely from this recommendation's career_options
    $jobs = array_map('trim', explode(',', $career_options_param));
    $jobs = array_filter($jobs);
    $career_data = [
        'career_paths' => $jobs,
        'job_examples' => $jobs,
        'required_skills' => [],
        'work_environments' => [],
        'image' => 'default.jpg'
    ];
} else {
    // Use predefined career_paths_data when major is in our map
    $major_code = getMajorCode($major_name_ar, $major_name_en);
    $career_data = getCareerData($major_code, $lang);
    if (!$career_data) {
        $career_data = [
            'career_paths' => $lang === 'ar' ? ['مسار مهني 1', 'مسار مهني 2'] : ['Career Path 1', 'Career Path 2'],
            'job_examples' => $lang === 'ar' ? ['وظيفة 1', 'وظيفة 2'] : ['Job 1', 'Job 2'],
            'required_skills' => $lang === 'ar' ? ['مهارة 1', 'مهارة 2'] : ['Skill 1', 'Skill 2'],
            'work_environments' => $lang === 'ar' ? ['بيئة 1', 'بيئة 2'] : ['Environment 1', 'Environment 2'],
            'image' => 'default.jpg'
        ];
    }
}

$major_display_name = $lang === 'ar' ? $major_name_ar : $major_name_en;
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang === 'ar' ? 'استكشف المسار المهني' : 'Explore Career Path'; ?> - <?php echo $major_display_name; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <div class="container" style="margin-top: 2rem;">
        <div class="career-explore-container">
            <!-- Header Section -->
            <div class="career-header">
                <a href="<?php echo SITE_URL; ?>/results.php" class="back-btn">
                    <i class="fas fa-arrow-<?php echo $lang === 'ar' ? 'right' : 'left'; ?>"></i> <?php echo $lang === 'ar' ? 'العودة للنتائج' : 'Back to Results'; ?>
                </a>
                <h1 class="career-title"><?php echo $major_display_name; ?></h1>
                <p class="career-subtitle"><?php echo $lang === 'ar' ? 'استكشف المسارات المهنية والوظائف المتاحة' : 'Explore career paths and available job opportunities'; ?></p>
            </div>

            <!-- Main Content: Image + Text Layout -->
            <div class="career-content-wrapper">
                <div class="career-image-section">
                    <div class="career-image-box">
                        <img src="<?php echo SITE_URL; ?>/assets/images/careers/<?php echo $career_data['image']; ?>" 
                             alt="<?php echo $major_display_name; ?>" 
                             onerror="this.src='<?php echo SITE_URL; ?>/assets/images/careers/default.jpg'">
                        <div class="image-overlay">
                            <div class="overlay-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h3><?php echo $major_display_name; ?></h3>
                            <p><?php echo $lang === 'ar' ? 'استكشف المستقبل المهني' : 'Explore Your Career Future'; ?></p>
                        </div>
                    </div>
                </div>

                <div class="career-info-section">
                    <!-- Career Paths -->
                    <div class="career-info-card">
                        <div class="career-info-header">
                            <i class="fas fa-route"></i>
                            <h2><?php echo $lang === 'ar' ? 'المسارات المهنية' : 'Career Paths'; ?></h2>
                        </div>
                        <ul class="career-list">
                            <?php foreach ($career_data['career_paths'] as $path): ?>
                                <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($path); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Job Examples -->
                    <div class="career-info-card">
                        <div class="career-info-header">
                            <i class="fas fa-briefcase"></i>
                            <h2><?php echo $lang === 'ar' ? 'أمثلة الوظائف' : 'Job Examples'; ?></h2>
                        </div>
                        <ul class="career-list">
                            <?php foreach ($career_data['job_examples'] as $job): ?>
                                <li><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($job); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <?php if (!empty($career_data['required_skills'])): ?>
                    <!-- Required Skills -->
                    <div class="career-info-card">
                        <div class="career-info-header">
                            <i class="fas fa-tools"></i>
                            <h2><?php echo $lang === 'ar' ? 'المهارات المطلوبة' : 'Required Skills'; ?></h2>
                        </div>
                        <div class="skills-grid">
                            <?php foreach ($career_data['required_skills'] as $skill): ?>
                                <span class="skill-badge"><?php echo htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($career_data['work_environments'])): ?>
                    <!-- Work Environments -->
                    <div class="career-info-card">
                        <div class="career-info-header">
                            <i class="fas fa-building"></i>
                            <h2><?php echo $lang === 'ar' ? 'بيئات العمل النموذجية' : 'Typical Work Environments'; ?></h2>
                        </div>
                        <ul class="career-list">
                            <?php foreach ($career_data['work_environments'] as $env): ?>
                                <li><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($env); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
