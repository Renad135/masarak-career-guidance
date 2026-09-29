<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/translations.php';

if (isLoggedIn()) {
    redirect(SITE_URL . '/profile.php');
}

$lang = getCurrentLanguage();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = $lang === 'ar' ? 'جميع الحقول مطلوبة' : 'All fields are required';
    } elseif (strlen($password) < 6) {
        $error = t('password_short');
    } elseif ($password !== $confirm_password) {
        $error = t('password_mismatch');
    } else {
        try {
            $pdo = getDBConnection();
            
            // Check username
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = t('username_exists');
            } else {
                // Check email
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = t('email_exists');
                } else {
                    // Create user
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    if ($stmt->execute([$username, $email, $hashed_password])) {
                        $success = t('registration_success');
                        header("refresh:2;url=" . SITE_URL . "/login.php");
                    } else {
                        $error = $lang === 'ar' ? 'حدث خطأ أثناء التسجيل' : 'Registration error occurred';
                    }
                }
            }
        } catch (PDOException $e) {
            $error = $lang === 'ar' ? 'حدث خطأ في الاتصال بقاعدة البيانات' : 'Database connection error';
        }
    }
}

$lang = getCurrentLanguage();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('signup'); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <div class="page-wrapper">
        <div class="page-image">
            <div class="page-image-content">
                <i class="fas fa-user-plus" style="font-size: 4rem; margin-bottom: 1.5rem; color: var(--soft-gold);"></i>
                <h2><?php echo $lang === 'ar' ? 'ابدأ رحلتك معنا' : 'Start Your Journey'; ?></h2>
                <p><?php echo $lang === 'ar' ? 'انضم إلى آلاف الطلاب الذين وجدوا تخصصهم المثالي' : 'Join thousands of students who found their perfect major'; ?></p>
            </div>
        </div>
        
        <div class="page-content">
            <div class="form-container">
                <h2 class="form-title"><?php echo t('signup'); ?></h2>
                <p class="form-subtitle"><?php echo $lang === 'ar' ? 'أنشئ حسابك الآن واحصل على توصيات مخصصة' : 'Create your account and get personalized recommendations'; ?></p>
                
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
                
                <form id="signupForm" method="POST" action="">
                    <div class="form-group">
                        <label class="form-label" for="username"><i class="fas fa-user"></i> <?php echo t('username'); ?></label>
                        <input type="text" id="username" name="username" class="form-input" required>
                        <div class="error-message"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="email"><i class="fas fa-envelope"></i> <?php echo t('email'); ?></label>
                        <input type="email" id="email" name="email" class="form-input" required>
                        <div class="error-message"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="password"><i class="fas fa-lock"></i> <?php echo t('password'); ?></label>
                        <input type="password" id="password" name="password" class="form-input" required minlength="6">
                        <div class="error-message"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="confirm_password"><i class="fas fa-lock"></i> <?php echo t('confirm_password'); ?></label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                        <div class="error-message"></div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-user-plus"></i> <?php echo t('register'); ?>
                    </button>
                </form>
                
                <p style="text-align: center; margin-top: 1.5rem; color: var(--dark-gray);">
                    <?php echo $lang === 'ar' ? 'لديك حساب بالفعل؟' : 'Already have an account?'; ?>
                    <a href="<?php echo SITE_URL; ?>/login.php" style="color: var(--emerald-green); text-decoration: none; font-weight: 600;">
                        <?php echo t('login'); ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
