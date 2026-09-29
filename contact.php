<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/translations.php';

$lang = getCurrentLanguage();
$message_sent = false;
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? sanitizeInput($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : '';
    
    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = $lang === 'ar' ? 'يرجى ملء جميع الحقول' : 'Please fill in all fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = $lang === 'ar' ? 'البريد الإلكتروني غير صحيح' : 'Invalid email address';
    } else {
        // In a real application, you would save this to database or send email
        // For now, we'll just show success message
        $message_sent = true;
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang === 'ar' ? 'اتصل بنا' : 'Contact Us'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <div class="container" style="margin-top: 3rem;">
        <!-- Hero Section -->
        <div class="contact-hero">
            <div class="contact-hero-content">
                <h1 class="contact-main-title">
                    <i class="fas fa-envelope-open-text"></i>
                    <?php echo $lang === 'ar' ? 'اتصل بنا' : 'Contact Us'; ?>
                </h1>
                <p class="contact-hero-text">
                    <?php echo $lang === 'ar' 
                        ? 'نحن هنا لمساعدتك! إذا كان لديك أي استفسار أو اقتراح، لا تتردد في التواصل معنا'
                        : 'We are here to help! If you have any questions or suggestions, feel free to contact us'; ?>
                </p>
            </div>
        </div>

        <!-- Contact Content -->
        <div class="contact-content-wrapper">
            <!-- Contact Form -->
            <div class="contact-form-section">
                <div class="contact-form-card">
                    <h2 class="form-title">
                        <i class="fas fa-paper-plane"></i>
                        <?php echo $lang === 'ar' ? 'أرسل لنا رسالة' : 'Send Us a Message'; ?>
                    </h2>
                    
                    <?php if ($message_sent): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?php echo $lang === 'ar' ? 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.' : 'Your message has been sent successfully! We will contact you soon.'; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" class="contact-form" id="contactForm">
                        <div class="form-group">
                            <label for="name">
                                <i class="fas fa-user"></i>
                                <?php echo $lang === 'ar' ? 'الاسم الكامل' : 'Full Name'; ?>
                                <span class="required">*</span>
                            </label>
                            <input type="text" id="name" name="name" required 
                                   placeholder="<?php echo $lang === 'ar' ? 'أدخل اسمك الكامل' : 'Enter your full name'; ?>"
                                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i>
                                <?php echo $lang === 'ar' ? 'البريد الإلكتروني' : 'Email Address'; ?>
                                <span class="required">*</span>
                            </label>
                            <input type="email" id="email" name="email" required 
                                   placeholder="<?php echo $lang === 'ar' ? 'example@email.com' : 'example@email.com'; ?>"
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">
                                <i class="fas fa-tag"></i>
                                <?php echo $lang === 'ar' ? 'الموضوع' : 'Subject'; ?>
                                <span class="required">*</span>
                            </label>
                            <input type="text" id="subject" name="subject" required 
                                   placeholder="<?php echo $lang === 'ar' ? 'موضوع الرسالة' : 'Message subject'; ?>"
                                   value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">
                                <i class="fas fa-comment-alt"></i>
                                <?php echo $lang === 'ar' ? 'الرسالة' : 'Message'; ?>
                                <span class="required">*</span>
                            </label>
                            <textarea id="message" name="message" rows="6" required 
                                      placeholder="<?php echo $lang === 'ar' ? 'اكتب رسالتك هنا...' : 'Write your message here...'; ?>"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            <?php echo $lang === 'ar' ? 'إرسال الرسالة' : 'Send Message'; ?>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="contact-info-section">
                <div class="contact-info-card">
                    <h2 class="info-title">
                        <i class="fas fa-info-circle"></i>
                        <?php echo $lang === 'ar' ? 'معلومات الاتصال' : 'Contact Information'; ?>
                    </h2>
                    
                    <div class="info-items">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="info-content">
                                <h3><?php echo $lang === 'ar' ? 'الجامعة' : 'University'; ?></h3>
                                <p>King Khalid University</p>
                                <p><?php echo $lang === 'ar' ? 'جامعة الملك خالد' : 'King Khalid University'; ?></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <h3><?php echo $lang === 'ar' ? 'الموقع' : 'Location'; ?></h3>
                                <p><?php echo $lang === 'ar' ? 'أبها، المملكة العربية السعودية' : 'Abha, Saudi Arabia'; ?></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h3><?php echo $lang === 'ar' ? 'البريد الإلكتروني' : 'Email'; ?></h3>
                                <p><a href="mailto:info@masarak.com">info@masarak.com</a></p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h3><?php echo $lang === 'ar' ? 'ساعات العمل' : 'Working Hours'; ?></h3>
                                <p><?php echo $lang === 'ar' ? 'الأحد - الخميس: 8:00 ص - 5:00 م' : 'Sunday - Thursday: 8:00 AM - 5:00 PM'; ?></p>
                                <p><?php echo $lang === 'ar' ? 'الجمعة - السبت: مغلق' : 'Friday - Saturday: Closed'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="social-contact-card">
                    <h3><?php echo $lang === 'ar' ? 'تابعنا على' : 'Follow Us'; ?></h3>
                    <div class="social-contact-links">
                        <a href="https://x.com/" target="_blank" class="social-contact-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                            <span>Twitter</span>
                        </a>
                        <a href="https://www.linkedin.com/" target="_blank" class="social-contact-link" aria-label="LinkedIn">
                            <i class="fab fa-linkedin"></i>
                            <span>LinkedIn</span>
                        </a>
                        <a href="https://www.facebook.com/" target="_blank" class="social-contact-link" aria-label="Facebook">
                            <i class="fab fa-facebook"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="https://www.instagram.com/" target="_blank" class="social-contact-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                            <span>Instagram</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
    <script>
        // Form validation
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value.trim();
            const message = document.getElementById('message').value.trim();
            
            if (!name || !email || !subject || !message) {
                e.preventDefault();
                alert('<?php echo $lang === 'ar' ? 'يرجى ملء جميع الحقول' : 'Please fill in all fields'; ?>');
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('<?php echo $lang === 'ar' ? 'البريد الإلكتروني غير صحيح' : 'Invalid email address'; ?>');
                return false;
            }
        });
    </script>
</body>
</html>
