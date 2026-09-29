<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/translations.php';

$lang = getCurrentLanguage();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang === 'ar' ? 'من نحن' : 'About Us'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <div class="container" style="margin-top: 3rem;">
        <!-- Hero Section -->
        <div class="about-hero">
            <div class="about-hero-content">
                <h1 class="about-main-title"><?php echo $lang === 'ar' ? 'مرحباً بك في MASARAK' : 'Welcome to MASARAK'; ?></h1>
                <p class="about-hero-text">
                    <?php echo $lang === 'ar' 
                        ? 'منصة إرشادية ذكية تعتمد على الذكاء الاصطناعي لمساعدة طلاب الثانوية على اختيار التخصص الجامعي الأنسب بناءً على درجاتهم الأكاديمية وميولهم الشخصية'
                        : 'An intelligent AI-powered platform designed to help high school students choose the most suitable university major based on their academic scores and personal interests'; ?>
                </p>
            </div>
        </div>

        <!-- About Section -->
        <div class="about-section">
            <div class="about-content-card">
                <div class="about-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h2><?php echo $lang === 'ar' ? 'عن المشروع' : 'About the Project'; ?></h2>
                <p>
                    <?php echo $lang === 'ar' 
                        ? 'MASARAK هو مشروع لجامعة الملك خالد يهدف إلى حل مشكلة حقيقية يواجهها خريجو الثانوية العامة عند اختيار التخصص الجامعي. يعتمد المشروع على تقنيات الذكاء الاصطناعي لتقديم توصيات مخصصة ودقيقة لكل طالب.'
                        : 'MASARAK is a project from King Khalid University aimed at solving a real problem faced by high school graduates when choosing their university major. The project uses artificial intelligence technologies to provide personalized and accurate recommendations for each student.'; ?>
                </p>
            </div>
        </div>

        <!-- How It Works -->
        <div class="about-section">
            <h2 class="section-title">
                <i class="fas fa-cogs"></i>
                <?php echo $lang === 'ar' ? 'كيف يعمل الموقع' : 'How It Works'; ?>
            </h2>
            
            <div class="steps-container">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3><?php echo $lang === 'ar' ? 'التسجيل' : 'Sign Up'; ?></h3>
                    <p><?php echo $lang === 'ar' ? 'أنشئ حسابك الخاص في الموقع' : 'Create your personal account on the platform'; ?></p>
                </div>

                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h3><?php echo $lang === 'ar' ? 'الملف الشخصي' : 'Profile'; ?></h3>
                    <p><?php echo $lang === 'ar' ? 'أكمل معلوماتك الشخصية الأساسية' : 'Complete your basic personal information'; ?></p>
                </div>

                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3><?php echo $lang === 'ar' ? 'البيانات الأكاديمية' : 'Academic Data'; ?></h3>
                    <p><?php echo $lang === 'ar' ? 'أدخل درجاتك في اختبارات القدرات والتحصيلي ومعدل الثانوية' : 'Enter your scores in Qiyas, Tahsili tests and high school GPA'; ?></p>
                </div>

                <div class="step-card">
                    <div class="step-number">4</div>
                    <div class="step-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3><?php echo $lang === 'ar' ? 'استبيان الميول' : 'Interests Survey'; ?></h3>
                    <p><?php echo $lang === 'ar' ? 'أجب على أسئلة الاستبيان حول ميولك واهتماماتك' : 'Answer survey questions about your interests and preferences'; ?></p>
                </div>

                <div class="step-card">
                    <div class="step-number">5</div>
                    <div class="step-icon">
                        <i class="fas fa-magic"></i>
                    </div>
                    <h3><?php echo $lang === 'ar' ? 'التوصيات الذكية' : 'Smart Recommendations'; ?></h3>
                    <p><?php echo $lang === 'ar' ? 'احصل على توصيات مخصصة من 3-5 تخصصات مناسبة لك' : 'Get personalized recommendations for 3-5 suitable majors'; ?></p>
                </div>

                <div class="step-card">
                    <div class="step-number">6</div>
                    <div class="step-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3><?php echo $lang === 'ar' ? 'استكشف المسارات' : 'Explore Paths'; ?></h3>
                    <p><?php echo $lang === 'ar' ? 'استكشف المسارات المهنية والوظائف المتاحة لكل تخصص' : 'Explore career paths and available jobs for each major'; ?></p>
                </div>
            </div>
        </div>

    
        <!-- Important Notes -->
        <div class="about-section">
            <div class="important-notes-card">
                <div class="notes-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h2><?php echo $lang === 'ar' ? 'ملاحظات مهمة' : 'Important Notes'; ?></h2>
                <ul class="notes-list">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo $lang === 'ar' ? 'الموقع إرشادي وليس نظام قبول رسمي' : 'The website is advisory and not an official admission system'; ?></span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo $lang === 'ar' ? 'النتائج قابلة للتحديث والتعديل' : 'Results are updatable and modifiable'; ?></span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo $lang === 'ar' ? 'يجب إكمال جميع البيانات للحصول على توصيات دقيقة' : 'All data must be completed to get accurate recommendations'; ?></span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo $lang === 'ar' ? 'التوصيات تعتمد على خوارزميات الذكاء الاصطناعي' : 'Recommendations are based on AI algorithms'; ?></span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="about-cta-section">
            <div class="cta-content">
                <h2><?php echo $lang === 'ar' ? 'ابدأ رحلتك الآن' : 'Start Your Journey Now'; ?></h2>
                <p><?php echo $lang === 'ar' ? 'سجل في الموقع واحصل على توصيات مخصصة لتخصصك الجامعي المثالي' : 'Sign up and get personalized recommendations for your perfect university major'; ?></p>
                <?php if (!isLoggedIn()): ?>
                    <div class="cta-buttons">
                        <a href="<?php echo SITE_URL; ?>/signup.php" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> <?php echo $lang === 'ar' ? 'سجل الآن' : 'Sign Up Now'; ?>
                        </a>
                        <a href="<?php echo SITE_URL; ?>/login.php" class="btn" style="background: var(--white); color: var(--navy-blue); border: 2px solid var(--navy-blue);">
                            <i class="fas fa-sign-in-alt"></i> <?php echo $lang === 'ar' ? 'تسجيل الدخول' : 'Login'; ?>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="cta-buttons">
                        <a href="<?php echo SITE_URL; ?>/results.php" class="btn btn-primary">
                            <i class="fas fa-chart-line"></i> <?php echo $lang === 'ar' ? 'شاهد توصياتك' : 'View Your Recommendations'; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <br> <br>
    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
