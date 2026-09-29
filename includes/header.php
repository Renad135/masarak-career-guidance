<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/translations.php';

$lang = getCurrentLanguage();
$isRTL = $lang === 'ar';

// Get current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $isRTL ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        window.siteUrl = '<?php echo SITE_URL; ?>';
    </script>
</head>
<body class="<?php echo $isRTL ? 'rtl' : 'ltr'; ?>">
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="<?php echo SITE_URL; ?>/index.php"><?php echo SITE_NAME; ?></a>
            </div>
            <div class="nav-menu">
                <a href="<?php echo SITE_URL; ?>/about.php" class="nav-link <?php echo $current_page === 'about.php' ? 'active' : ''; ?>"><?php echo $lang === 'ar' ? 'من نحن' : 'About'; ?></a>
                <a href="<?php echo SITE_URL; ?>/contact.php" class="nav-link <?php echo $current_page === 'contact.php' ? 'active' : ''; ?>"><?php echo $lang === 'ar' ? 'اتصل بنا' : 'Contact'; ?></a>
                <?php if (isLoggedIn()): ?>
                    <a href="<?php echo SITE_URL; ?>/profile.php" class="nav-link <?php echo $current_page === 'profile.php' ? 'active' : ''; ?>"><?php echo t('profile'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/results.php" class="nav-link <?php echo $current_page === 'results.php' ? 'active' : ''; ?>"><?php echo t('recommendations'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/logout.php" class="nav-link"><?php echo t('logout'); ?></a>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>/login.php" class="nav-link <?php echo $current_page === 'login.php' ? 'active' : ''; ?>"><?php echo t('login'); ?></a>
                    <a href="<?php echo SITE_URL; ?>/signup.php" class="nav-link btn-primary <?php echo $current_page === 'signup.php' ? 'active' : ''; ?>"><?php echo t('signup'); ?></a>
                <?php endif; ?>
                <div class="language-switcher">
                    <button class="lang-btn" onclick="switchLanguage('<?php echo $lang === 'ar' ? 'en' : 'ar'; ?>')">
                        <?php echo $lang === 'ar' ? 'EN' : 'AR'; ?>
                    </button>
                </div>
            </div>
        </div>
    </nav>
