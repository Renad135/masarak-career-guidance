<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/translations.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . '/login.php');
}

$user_id = getCurrentUserId();
$lang = getCurrentLanguage();

// Generate recommendations if not exists
$pdo = getDBConnection();

// Check if recommendations exist
$stmt = $pdo->prepare("SELECT COUNT(*) FROM recommendations WHERE user_id = ?");
$stmt->execute([$user_id]);
$hasRecommendations = $stmt->fetchColumn() > 0;

if (!$hasRecommendations) {
    // Get user data
    $stmt = $pdo->prepare("SELECT * FROM academic_data WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $academic = $stmt->fetch();
    
    $stmt = $pdo->prepare("SELECT * FROM interests_survey WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $survey = $stmt->fetch();
    
    if ($academic && $survey) {
        // Call Python recommendation script
        $weighted_score = $academic['weighted_score'] ?? 0;
        $preferred_field = $survey['preferred_field'];
        $interest_nature = $survey['interest_nature'];
        $work_environment = $survey['work_environment'];
        
        $command = "python " . escapeshellarg(__DIR__ . "/ai/recommendation_engine.py") . " " . 
                   escapeshellarg($user_id) . " " . 
                   escapeshellarg($weighted_score) . " " . 
                   escapeshellarg($preferred_field) . " " . 
                   escapeshellarg($interest_nature) . " " . 
                   escapeshellarg($work_environment);
        
        // For Windows, try both python and python3
        $output = [];
        $return_var = 1;
        exec($command . " 2>&1", $output, $return_var);
        
        // If failed, try python3
        if ($return_var !== 0) {
            $command = "python3 " . escapeshellarg(__DIR__ . "/ai/recommendation_engine.py") . " " . 
                       escapeshellarg($user_id) . " " . 
                       escapeshellarg($weighted_score) . " " . 
                       escapeshellarg($preferred_field) . " " . 
                       escapeshellarg($interest_nature) . " " . 
                       escapeshellarg($work_environment);
            exec($command . " 2>&1", $output, $return_var);
        }
        
        // Log errors if any (for debugging)
        if ($return_var !== 0 && !empty($output)) {
            error_log("Python recommendation error: " . implode("\n", $output));
        }
    }
}

// Get recommendations
$stmt = $pdo->prepare("SELECT * FROM recommendations WHERE user_id = ? ORDER BY match_score DESC");
$stmt->execute([$user_id]);
$recommendations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('recommendations'); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <div class="container" style="margin-top: 2rem;">
        <div class="results-container">
            <div class="card">
                <h2 class="card-title"><?php echo t('your_recommendations'); ?></h2>
                
                <?php if (empty($recommendations)): ?>
                    <p style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                        <?php echo $lang === 'ar' ? 'لا توجد توصيات متاحة. يرجى إكمال الاستبيان أولاً.' : 'No recommendations available. Please complete the survey first.'; ?>
                    </p>
                    <div style="text-align: center;">
                        <a href="<?php echo SITE_URL; ?>/survey.php" class="btn btn-primary">
                            <?php echo $lang === 'ar' ? 'اذهب إلى الاستبيان' : 'Go to Survey'; ?>
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach ($recommendations as $rec): ?>
                        <div class="recommendation-card">
                            <div class="recommendation-header">
                                <div>
                                    <div class="major-name">
                                        <?php echo $lang === 'ar' ? $rec['major_name_ar'] : $rec['major_name_en']; ?>
                                    </div>
                                    <div class="college-name">
                                        <?php echo $lang === 'ar' ? $rec['college_ar'] : $rec['college_en']; ?>
                                    </div>
                                </div>
                                <div>
                                    <span class="probability-badge probability-<?php echo $rec['acceptance_probability']; ?>">
                                        <?php echo t($rec['acceptance_probability']); ?>
                                    </span>
                                    <div class="match-score" style="margin-top: 0.5rem;">
                                        <?php echo number_format($rec['match_score'], 1); ?>%
                                    </div>
                                </div>
                            </div>
                            <div class="recommendation-reason">
                                <strong><?php echo t('reason'); ?>:</strong><br>
                                <?php echo $lang === 'ar' ? $rec['recommendation_reason_ar'] : $rec['recommendation_reason_en']; ?>
                            </div>
                            <div style="margin-top: 1.5rem; text-align: center;">
                                <?php
                                $exploreUrl = SITE_URL . '/explore_career.php?major_ar=' . urlencode($rec['major_name_ar']) . '&major_en=' . urlencode($rec['major_name_en']);
                                if (!empty($rec['recommendation_reason_en'])) {
                                    $exploreUrl .= '&career_options=' . urlencode($rec['recommendation_reason_en']);
                                }
                                ?>
                                <a href="<?php echo $exploreUrl; ?>" 
                                   class="btn btn-primary" 
                                   style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-search"></i> 
                                    <?php echo $lang === 'ar' ? 'استكشف المسار المهني' : 'Explore Career Path'; ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
