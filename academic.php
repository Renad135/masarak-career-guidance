<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/translations.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . '/login.php');
}

$user_id = getCurrentUserId();
$lang = getCurrentLanguage();
$error = '';
$success = '';

// Get existing academic data
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM academic_data WHERE user_id = ?");
$stmt->execute([$user_id]);
$academic = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qiyas = isset($_POST['qiyas_score']) ? floatval($_POST['qiyas_score']) : null;
    $tahsili = isset($_POST['tahsili_score']) ? floatval($_POST['tahsili_score']) : null;
    $gpa = isset($_POST['high_school_gpa']) ? floatval($_POST['high_school_gpa']) : null;
    
    // Calculate weighted score
    $weighted = null;
    if ($qiyas !== null && $tahsili !== null && $gpa !== null) {
        $weighted = ($tahsili * 0.4) + ($qiyas * 0.3) + ($gpa * 0.3);
    }
    
    try {
        if ($academic) {
            $stmt = $pdo->prepare("UPDATE academic_data SET qiyas_score = ?, tahsili_score = ?, high_school_gpa = ?, weighted_score = ? WHERE user_id = ?");
            $stmt->execute([$qiyas, $tahsili, $gpa, $weighted, $user_id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO academic_data (user_id, qiyas_score, tahsili_score, high_school_gpa, weighted_score) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $qiyas, $tahsili, $gpa, $weighted]);
        }
        $success = $lang === 'ar' ? 'تم حفظ البيانات بنجاح' : 'Data saved successfully';
        header("refresh:1;url=" . $_SERVER['PHP_SELF']);
    } catch (PDOException $e) {
        $error = $lang === 'ar' ? 'حدث خطأ في حفظ البيانات' : 'Error saving data';
    }
}

// Refresh academic data
$stmt = $pdo->prepare("SELECT * FROM academic_data WHERE user_id = ?");
$stmt->execute([$user_id]);
$academic = $stmt->fetch();

$lang = getCurrentLanguage();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('academic_data'); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <div class="page-wrapper">
        <div class="page-image">
            <div class="page-image-content">
                <i class="fas fa-graduation-cap" style="font-size: 4rem; margin-bottom: 1.5rem; color: var(--soft-gold);"></i>
                <h2><?php echo $lang === 'ar' ? 'البيانات الأكاديمية' : 'Academic Data'; ?></h2>
                <p><?php echo $lang === 'ar' ? 'أدخل درجاتك الأكاديمية لحساب الدرجة الموزونة' : 'Enter your academic scores to calculate weighted score'; ?></p>
            </div>
        </div>
        
        <div class="page-content">
            <div class="form-container">
                <h2 class="form-title"><?php echo t('academic_data'); ?></h2>
                <p class="form-subtitle"><?php echo $lang === 'ar' ? '40% تحصيلي + 30% قدرات + 30% ثانوي' : '40% Tahsili + 30% Qiyas + 30% High School'; ?></p>
            
                <?php if ($error): ?>
                    <div class="error-message show" style="background-color: rgba(231, 76, 60, 0.1); color: #C0392B; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; border-left: 4px solid var(--error-color);">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <form id="academicForm" method="POST" action="">
                    <div class="form-group">
                        <label class="form-label" for="qiyas_score"><i class="fas fa-chart-line"></i> <?php echo t('qiyas_score'); ?></label>
                        <input type="number" id="qiyas_score" name="qiyas_score" class="form-input" step="0.01" min="0" max="100" value="<?php echo htmlspecialchars($academic['qiyas_score'] ?? ''); ?>">
                        <div class="error-message"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="tahsili_score"><i class="fas fa-chart-bar"></i> <?php echo t('tahsili_score'); ?></label>
                        <input type="number" id="tahsili_score" name="tahsili_score" class="form-input" step="0.01" min="0" max="100" value="<?php echo htmlspecialchars($academic['tahsili_score'] ?? ''); ?>">
                        <div class="error-message"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="high_school_gpa"><i class="fas fa-star"></i> <?php echo t('high_school_gpa'); ?></label>
                        <input type="number" id="high_school_gpa" name="high_school_gpa" class="form-input" step="0.01" min="0" max="100" value="<?php echo htmlspecialchars($academic['high_school_gpa'] ?? ''); ?>">
                        <div class="error-message"></div>
                    </div>
                
                <div class="form-group">
                    <label class="form-label"><?php echo t('weighted_score'); ?></label>
                    <div id="weighted_score_result" style="padding: 1.5rem; background: linear-gradient(135deg, var(--emerald-green) 0%, #27AE60 100%); border-radius: 12px; font-size: 2rem; font-weight: 800; color: var(--white); text-align: center; box-shadow: var(--shadow-md);">
                        <?php echo isset($academic['weighted_score']) ? number_format($academic['weighted_score'], 2) : '0.00'; ?>
                    </div>
                    <small style="color: var(--dark-gray); display: block; margin-top: 0.5rem; text-align: center;">
                        <?php echo $lang === 'ar' ? '40% تحصيلي + 30% قدرات + 30% ثانوي' : '40% Tahsili + 30% Qiyas + 30% High School'; ?>
                    </small>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> <?php echo t('save'); ?>
                </button>
            </form>
            
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid var(--light-gray); text-align: center;">
                <a href="<?php echo SITE_URL; ?>/survey.php" class="btn" style="background: var(--navy-blue); color: var(--white);">
                    <i class="fas fa-arrow-left"></i> <?php echo $lang === 'ar' ? 'التالي: استبيان الميول' : 'Next: Interests Survey'; ?> <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
