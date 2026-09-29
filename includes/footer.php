    <?php
    $lang = getCurrentLanguage();
    ?>
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php echo SITE_NAME; ?></h3>
                    <p><?php echo $lang === 'ar' ? 'منصة إرشادية ذكية لمساعدة طلاب الثانوية على اختيار التخصص الجامعي الأنسب' : 'AI-powered platform to help high school students choose the perfect university major'; ?></p>
                    <div class="social-links">
                        <a href="https://x.com/" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        <a href="https://www.facebook.com/" target="_blank" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.instagram.com/" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4><?php echo $lang === 'ar' ? 'روابط سريعة' : 'Quick Links'; ?></h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/index.php"><?php echo $lang === 'ar' ? 'الرئيسية' : 'Home'; ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about.php"><?php echo $lang === 'ar' ? 'من نحن' : 'About Us'; ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php"><?php echo $lang === 'ar' ? 'اتصل بنا' : 'Contact Us'; ?></a></li>
                        <?php if (!isLoggedIn()): ?>
                            <li><a href="<?php echo SITE_URL; ?>/login.php"><?php echo t('login'); ?></a></li>
                            <li><a href="<?php echo SITE_URL; ?>/signup.php"><?php echo t('signup'); ?></a></li>
                        <?php else: ?>
                            <li><a href="<?php echo SITE_URL; ?>/profile.php"><?php echo t('profile'); ?></a></li>
                            <li><a href="<?php echo SITE_URL; ?>/results.php"><?php echo t('recommendations'); ?></a></li>
                            <li><a href="<?php echo SITE_URL; ?>/logout.php"><?php echo t('logout'); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4><?php echo $lang === 'ar' ? 'التخصصات' : 'Majors'; ?></h4>
                    <ul>
                        <li><?php echo $lang === 'ar' ? 'كلية التقنية' : 'College of Technology'; ?></li>
                        <li><?php echo $lang === 'ar' ? 'كلية الصحة' : 'College of Health'; ?></li>
                        <li><?php echo $lang === 'ar' ? 'كلية الهندسة' : 'College of Engineering'; ?></li>
                        <li><?php echo $lang === 'ar' ? 'كلية الفنون' : 'College of Arts'; ?></li>
                        <li><?php echo $lang === 'ar' ? 'كلية السياحة' : 'College of Tourism'; ?></li>
                        <li><?php echo $lang === 'ar' ? 'كلية الحقوق' : 'College of Law'; ?></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. <?php echo $lang === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved'; ?></p>
            </div>
        </div>
    </footer>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
