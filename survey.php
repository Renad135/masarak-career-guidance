<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/translations.php';
require_once __DIR__ . '/includes/survey_questions.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . '/login.php');
}

$user_id = getCurrentUserId();
$lang = getCurrentLanguage();
$error = '';
$success = '';

// Load rec survey metadata (questions from feature_cols)
$recRoot = __DIR__ . DIRECTORY_SEPARATOR . 'rec';
$metadataPaths = [
    $recRoot . DIRECTORY_SEPARATOR . 'artifacts' . DIRECTORY_SEPARATOR . 'metadata.json',
    $recRoot . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'artifacts' . DIRECTORY_SEPARATOR . 'metadata.json',
    $recRoot . DIRECTORY_SEPARATOR . 'metadata.json',
];
$meta = null;
$featureCols = [];
foreach ($metadataPaths as $path) {
    if (is_file($path)) {
        $meta = json_decode(file_get_contents($path), true);
        if ($meta && !empty($meta['feature_cols'])) {
            $featureCols = $meta['feature_cols'];
            break;
        }
    }
}

if (empty($featureCols)) {
    $error = $lang === 'ar' ? 'لم يتم العثور على أسئلة الاستبيان. تأكد من وجود مجلد rec وملف metadata.json (بعد تدريب النموذج).' : 'Survey questions not found. Ensure rec folder and metadata.json exist (after training the model).';
}


$pdo = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($featureCols)) {
    $raw = $_POST['q'] ?? [];
    $answers = [];
    foreach ($featureCols as $col) {
        $v = isset($raw[$col]) ? trim($raw[$col]) : 'No';
        $answers[$col] = ($v === 'Yes' || $v === 'yes') ? 'Yes' : 'No';
    }

    $recommendations = [];
    $pythonScript = 'app' . DIRECTORY_SEPARATOR . 'predict_api.py';
    $scriptPath = $recRoot . DIRECTORY_SEPARATOR . $pythonScript;
    if (!is_file($scriptPath)) {
        $pythonScript = 'predict_api.py';
        $scriptPath = $recRoot . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $pythonScript;
    }

    $descriptorspec = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];
    $cmd = 'python ' . escapeshellarg($pythonScript);
    $proc = @proc_open(
        $cmd,
        $descriptorspec,
        $pipes,
        $recRoot
    );

    if (is_resource($proc)) {
        fwrite($pipes[0], json_encode($answers));
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);

        $result = json_decode($stdout, true);
        if ($result && isset($result['recommendations']) && is_array($result['recommendations'])) {
            $recommendations = $result['recommendations'];
            if (!empty($result['error'])) {
                $error = $result['error'];
            }
        } else {
            $error = $result['error'] ?? ($lang === 'ar' ? 'لم يُرجع النموذج نتيجة صالحة.' : 'Model did not return valid results.');
            if (!empty($stderr)) {
                $error .= ' ' . trim($stderr);
            }
        }
    } else {
        $error = $lang === 'ar' ? 'تعذر تشغيل نموذج التوصية. تأكد من تثبيت Python والمكتبات (pandas, scikit-learn, joblib).' : 'Could not run recommendation model. Ensure Python and libraries (pandas, scikit-learn, joblib) are installed.';
    }

    if (empty($error) && !empty($recommendations)) {
        try {
            $stmt = $pdo->prepare("DELETE FROM recommendations WHERE user_id = ?");
            $stmt->execute([$user_id]);

            $insertStmt = $pdo->prepare("
                INSERT INTO recommendations (user_id, major_name_ar, major_name_en, college_ar, college_en, recommendation_reason_ar, recommendation_reason_en, acceptance_probability, match_score)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $collegeAr = $lang === 'ar' ? 'توصية النموذج' : 'Career Recommendation';
            $collegeEn = 'Career Recommendation';

            foreach ($recommendations as $r) {
                $course = $r['course'] ?? '';
                $probability = (float)($r['probability'] ?? 0);
                $careerOptions = $r['career_options'] ?? '';
                $matchScore = round($probability * 100, 2);
                if ($probability >= 0.25) {
                    $acceptance = 'high';
                } elseif ($probability >= 0.15) {
                    $acceptance = 'medium';
                } else {
                    $acceptance = 'low';
                }
                $insertStmt->execute([
                    $user_id,
                    $course,
                    $course,
                    $collegeAr,
                    $collegeEn,
                    $careerOptions,
                    $careerOptions,
                    $acceptance,
                    $matchScore
                ]);
            }
            $success = $lang === 'ar' ? 'تم حفظ الاستبيان بنجاح' : 'Survey saved successfully';
            header("refresh:2;url=" . SITE_URL . "/results.php");
        } catch (PDOException $e) {
            $error = $lang === 'ar' ? 'حدث خطأ في حفظ التوصيات' : 'Error saving recommendations';
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
    <title><?php echo t('interests_survey'); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <div class="page-wrapper page-wrapper-survey">
        <div class="page-image page-image-survey">
            <div class="page-image-content">
                <i class="fas fa-clipboard-list" style="font-size: 5 rem; margin-bottom: 0.5rem; color: var(--soft-gold);"></i>
                <h2><?php echo $lang === 'ar' ? 'استبيان الميول' : 'Interests Survey'; ?></h2>
                <p><?php echo $lang === 'ar' ? 'أجب بنعم أو لا لكل سؤال للحصول على توصيات تخصصات مناسبة لك' : 'Answer Yes or No for each question to get personalized recommendations'; ?></p>
            </div>
        </div>
        
        <div class="page-content">
            <div class="form-container form-container-survey">
                <h2 class="form-title form-title-survey"><?php echo t('interests_survey'); ?></h2>
                <p class="form-subtitle form-subtitle-survey"><?php echo $lang === 'ar' ? 'اختر لكل سؤال: نعم أو لا' : 'For each question choose: Yes or No'; ?></p>
                
                <?php if ($error): ?>
                    <div class="error-message show" style="background-color: rgba(231, 76, 60, 0.1); color: #C0392B; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; border-left: 4px solid var(--error-color);">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($featureCols)): ?>
                    <p class="form-subtitle"><?php echo $lang === 'ar' ? 'يرجى التأكد من وجود مجلد rec وملف metadata.json بعد تدريب النموذج.' : 'Please ensure the rec folder and metadata.json exist after training the model.'; ?></p>
                <?php else: ?>
                <form id="surveyForm" method="POST" action="">
                    <div class="survey-grid">
                    <?php foreach ($featureCols as $col): ?>
                    <div class="survey-question survey-question-cell">
                        <label for="q-<?php echo htmlspecialchars($col); ?>"><?php echo htmlspecialchars(getSurveyQuestionLabel($col, $lang)); ?></label>
                        <div class="radio-group survey-radio-inline" id="q-<?php echo htmlspecialchars($col); ?>">
                            <div class="radio-item">
                                <input type="radio" id="q-<?php echo htmlspecialchars($col); ?>-yes" name="q[<?php echo htmlspecialchars($col); ?>]" value="Yes">
                                <label for="q-<?php echo htmlspecialchars($col); ?>-yes"><?php echo $lang === 'ar' ? 'نعم' : 'Yes'; ?></label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="q-<?php echo htmlspecialchars($col); ?>-no" name="q[<?php echo htmlspecialchars($col); ?>]" value="No" checked>
                                <label for="q-<?php echo htmlspecialchars($col); ?>-no"><?php echo $lang === 'ar' ? 'لا' : 'No'; ?></label>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-primary survey-submit-btn" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i> <?php echo $lang === 'ar' ? 'عرض التوصيات' : 'Get Recommendations'; ?>
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
</body>
</html>
