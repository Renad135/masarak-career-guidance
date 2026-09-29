<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/translations.php';

if (isLoggedIn()) {
    redirect(SITE_URL . '/profile.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = t('invalid_credentials');
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $success = t('login_success');
                header("refresh:1;url=" . SITE_URL . "/profile.php");
            } else {
                $error = t('invalid_credentials');
            }
        } catch (PDOException $e) {
            $error = t('login_error');
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
    <title><?php echo t('login_title'); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <div class="page-wrapper">
        <div class="page-image">
            <div class="page-image-content">
                <i class="fas fa-graduation-cap" style="font-size: 4rem; margin-bottom: 1.5rem; color: var(--soft-gold);"></i>
                <h2><?php echo $lang === 'ar' ? 'مرحباً بعودتك' : 'Welcome Back'; ?></h2>
                <p><?php echo $lang === 'ar' ? 'سجل دخولك للوصول إلى توصياتك الشخصية' : 'Sign in to access your personalized recommendations'; ?></p>
            </div>
        </div>
        
        <div class="page-content">
            <div class="form-container">
                <h2 class="form-title"><?php echo t('login_title'); ?></h2>
                <p class="form-subtitle"><?php echo $lang === 'ar' ? 'أدخل بياناتك للدخول إلى حسابك' : 'Enter your credentials to access your account'; ?></p>
                
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
                
                <form id="loginForm" method="POST" action="">
                    <div class="form-group">
                        <label class="form-label" for="email"><i class="fas fa-envelope"></i> <?php echo t('email'); ?></label>
                        <input type="email" id="email" name="email" class="form-input" required>
                        <div class="error-message"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="password"><i class="fas fa-lock"></i> <?php echo t('password'); ?></label>
                        <input type="password" id="password" name="password" class="form-input" required>
                        <div class="error-message"></div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-sign-in-alt"></i> <?php echo t('login_title'); ?>
                    </button>
                </form>
                
                <p style="text-align: center; margin-top: 1.5rem; color: var(--dark-gray);">
                    <?php echo $lang === 'ar' ? 'ليس لديك حساب؟' : "Don't have an account?"; ?>
                    <a href="<?php echo SITE_URL; ?>/signup.php" style="color: var(--emerald-green); text-decoration: none; font-weight: 600;">
                        <?php echo t('signup'); ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
