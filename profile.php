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

// Get existing profile
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitizeInput($_POST['first_name'] ?? '');
    $last_name = sanitizeInput($_POST['last_name'] ?? '');
    $city = sanitizeInput($_POST['city'] ?? '');
    $graduation_year = sanitizeInput($_POST['graduation_year'] ?? '');
    $gender = sanitizeInput($_POST['gender'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $age = sanitizeInput($_POST['age'] ?? '');
    
    // Validation
    if (empty($first_name) || empty($last_name) || empty($gender)) {
        $error = $lang === 'ar' ? 'الحقول المطلوبة يجب ملؤها' : 'Required fields must be filled';
    } elseif ($graduation_year && !preg_match('/^\d{4}$/', $graduation_year)) {
        $error = t('invalid_year');
    } elseif ($phone && strlen(preg_replace('/\D/', '', $phone)) < 10) {
        $error = t('invalid_phone');
    } elseif ($age && ($age < 18 || $age > 100)) {
        $error = t('invalid_age');
    } else {
        try {
            if ($profile) {
                $stmt = $pdo->prepare("UPDATE user_profiles SET first_name = ?, last_name = ?, city = ?, graduation_year = ?, gender = ?, phone = ?, age = ? WHERE user_id = ?");
                $stmt->execute([$first_name, $last_name, $city, $graduation_year ?: null, $gender, $phone, $age ?: null, $user_id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO user_profiles (user_id, first_name, last_name, city, graduation_year, gender, phone, age) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $first_name, $last_name, $city, $graduation_year ?: null, $gender, $phone, $age ?: null]);
            }
            $success = $lang === 'ar' ? 'تم حفظ البيانات بنجاح' : 'Data saved successfully';
            header("refresh:1;url=" . $_SERVER['PHP_SELF']);
        } catch (PDOException $e) {
            $error = $lang === 'ar' ? 'حدث خطأ في حفظ البيانات' : 'Error saving data';
        }
    }
}

// Refresh profile data
$stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

$lang = getCurrentLanguage();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('profile'); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <div class="page-wrapper">
        <div class="page-image">
            <div class="page-image-content">
                <i class="fas fa-user-circle" style="font-size: 4rem; margin-bottom: 1.5rem; color: var(--soft-gold);"></i>
                <h2><?php echo $lang === 'ar' ? 'الملف الشخصي' : 'Your Profile'; ?></h2>
                <p><?php echo $lang === 'ar' ? 'أكمل معلوماتك الشخصية للحصول على توصيات أفضل' : 'Complete your profile for better recommendations'; ?></p>
            </div>
        </div>
        
        <div class="page-content">
            <div class="form-container">
                <h2 class="form-title"><?php echo t('profile'); ?></h2>
                <p class="form-subtitle"><?php echo $lang === 'ar' ? 'أدخل معلوماتك الشخصية' : 'Enter your personal information'; ?></p>
            
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
            
            <form id="profileForm" method="POST" action="">
                <div class="form-group">
                    <label class="form-label" for="first_name"><?php echo t('first_name'); ?> *</label>
                    <input type="text" id="first_name" name="first_name" class="form-input" required value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>">
                    <div class="error-message"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="last_name"><?php echo t('last_name'); ?> *</label>
                    <input type="text" id="last_name" name="last_name" class="form-input" required value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>">
                    <div class="error-message"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="city"><?php echo t('city'); ?></label>
                    <input type="text" id="city" name="city" class="form-input" value="<?php echo htmlspecialchars($profile['city'] ?? ''); ?>">
                    <div class="error-message"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="graduation_year"><?php echo t('graduation_year'); ?></label>
                    <input type="text" id="graduation_year" name="graduation_year" class="form-input" pattern="\d{4}" maxlength="4" value="<?php echo htmlspecialchars($profile['graduation_year'] ?? ''); ?>">
                    <div class="error-message"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="gender"><?php echo t('gender'); ?> *</label>
                    <select id="gender" name="gender" class="form-select" required>
                        <option value=""><?php echo $lang === 'ar' ? 'اختر الجنس' : 'Select Gender'; ?></option>
                        <option value="male" <?php echo (isset($profile['gender']) && $profile['gender'] === 'male') ? 'selected' : ''; ?>><?php echo t('male'); ?></option>
                        <option value="female" <?php echo (isset($profile['gender']) && $profile['gender'] === 'female') ? 'selected' : ''; ?>><?php echo t('female'); ?></option>
                    </select>
                    <div class="error-message"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="phone"><?php echo t('phone'); ?></label>
                    <input type="tel" id="phone" name="phone" class="form-input" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>">
                    <div class="error-message"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="age"><?php echo t('age'); ?></label>
                    <input type="number" id="age" name="age" class="form-input" min="18" max="100" value="<?php echo htmlspecialchars($profile['age'] ?? ''); ?>">
                    <div class="error-message"></div>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> <?php echo t('save'); ?>
                </button>
                
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid var(--light-gray); text-align: center;">
                    <a href="<?php echo SITE_URL; ?>/academic.php" class="btn" style="background: var(--navy-blue); color: var(--white);">
                        <i class="fas fa-arrow-left"></i> <?php echo $lang === 'ar' ? 'التالي: البيانات الأكاديمية' : 'Next: Academic Data'; ?> <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </form>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
