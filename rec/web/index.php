<?php
/**
 * صفحة استبيان الميول وتوصية التخصصات
 * تقرأ الأسئلة من artifacts/metadata.json وترسل الإجابات لنموذج Python وتُظهر التوصيات
 */
header('Content-Type: text/html; charset=utf-8');

$root = dirname(__DIR__);

// تحميل قائمة الأسئلة (الأعمدة) من ملف الميتاداتا
$metadataPath = $root . DIRECTORY_SEPARATOR . 'artifacts' . DIRECTORY_SEPARATOR . 'metadata.json';
if (!is_file($metadataPath)) {
    $metadataPath = $root . DIRECTORY_SEPARATOR . 'metadata.json';
}
if (!is_file($metadataPath)) {
    die('ملف الميتاداتا غير موجود. قم بتدريب النموذج أولاً (تشغيل الدفتر Recommender.ipynb أو train_model.py) ثم تأكد من وجود artifacts/metadata.json');
}

$meta = json_decode(file_get_contents($metadataPath), true);
$featureCols = $meta['feature_cols'] ?? [];
if (empty($featureCols)) {
    die('قائمة الأسئلة (feature_cols) فارغة في ملف الميتاداتا.');
}

// تحويل اسم العمود إلى نص سؤال للعرض
function questionLabel($col) {
    return str_replace('_', ' ', trim($col));
}

$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';
$recommendations = [];
$errorMessage = null;

if ($submitted) {
    // جمع الإجابات من النموذج (القيمة الافتراضية "No")
    $raw = $_POST['q'] ?? [];
    $answers = [];
    foreach ($featureCols as $col) {
        $v = isset($raw[$col]) ? trim($raw[$col]) : 'No';
        $answers[$col] = ($v === 'Yes' || $v === 'yes') ? 'Yes' : 'No';
    }

    // استدعاء سكربت Python عبر stdin
    $pythonScript = 'app' . DIRECTORY_SEPARATOR . 'predict_api.py';
    $cmd = 'python ' . escapeshellarg($pythonScript);
    $descriptorspec = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];
    $proc = proc_open(
        $cmd,
        $descriptorspec,
        $pipes,
        $root
    );

    if (!is_resource($proc)) {
        $errorMessage = 'تعذر تشغيل سكربت التوصية. تأكد من تثبيت Python وتوفر المكتبات (pandas, scikit-learn, joblib).';
    } else {
        fwrite($pipes[0], json_encode($answers));
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);

        $result = json_decode($stdout, true);
        if ($result && isset($result['recommendations'])) {
            $recommendations = $result['recommendations'];
            if (isset($result['error']) && $result['error']) {
                $errorMessage = $result['error'];
            }
        } else {
            $errorMessage = $result['error'] ?? 'لم يُرجع النموذج نتيجة صالحة.';
            if ($stderr) {
                $errorMessage .= ' ' . trim($stderr);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>استبيان الميول — توصية التخصصات</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; margin: 0; padding: 1rem; background: #f5f5f5; }
        .wrap { max-width: 720px; margin: 0 auto; background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        h1 { margin-top: 0; color: #1a1a2e; font-size: 1.5rem; }
        .intro { color: #555; margin-bottom: 1.5rem; }
        .q-block { margin-bottom: 1rem; padding: 0.75rem; background: #fafafa; border-radius: 6px; }
        .q-block label { display: block; margin-bottom: 0.35rem; font-weight: 600; color: #333; }
        .q-options { display: flex; gap: 1rem; }
        .q-options label { font-weight: normal; cursor: pointer; }
        .btn { background: #2563eb; color: #fff; border: none; padding: 0.6rem 1.2rem; border-radius: 6px; cursor: pointer; font-size: 1rem; }
        .btn:hover { background: #1d4ed8; }
        .results h2 { color: #1a1a2e; margin-top: 0; }
        .rec-item { margin-bottom: 1rem; padding: 1rem; background: #f0f9ff; border-right: 4px solid #2563eb; border-radius: 4px; }
        .rec-item .course { font-weight: 700; color: #1e40af; }
        .rec-item .pct { color: #64748b; font-size: 0.9rem; }
        .rec-item .careers { margin-top: 0.35rem; color: #334155; }
        .err { background: #fef2f2; color: #b91c1c; padding: 1rem; border-radius: 6px; margin-bottom: 1rem; }
        .back-link { display: inline-block; margin-top: 1rem; color: #2563eb; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="wrap">
    <h1>استبيان الميول — توصية التخصصات</h1>
    <p class="intro">اختر لكل سؤال: نعم أو لا. في النهاية سيتم إرسال إجاباتك لنموذج التوصية وعرض أفضل التخصصات والوظائف المناسبة.</p>

<?php if (!$submitted): ?>
    <form method="post" action="">
        <?php foreach ($featureCols as $col): ?>
        <div class="q-block">
            <label for="q-<?php echo htmlspecialchars($col); ?>"><?php echo htmlspecialchars(questionLabel($col)); ?></label>
            <div class="q-options" id="q-<?php echo htmlspecialchars($col); ?>">
                <label><input type="radio" name="q[<?php echo htmlspecialchars($col); ?>]" value="Yes"> نعم</label>
                <label><input type="radio" name="q[<?php echo htmlspecialchars($col); ?>]" value="No" checked> لا</label>
            </div>
        </div>
        <?php endforeach; ?>
        <button type="submit" class="btn">عرض التوصيات</button>
    </form>
<?php else: ?>
    <div class="results">
        <h2>التوصيات</h2>
        <?php if ($errorMessage): ?>
            <p class="err"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
        <?php if (!empty($recommendations)): ?>
            <?php foreach ($recommendations as $i => $r): ?>
            <div class="rec-item">
                <span class="course"><?php echo htmlspecialchars($r['course'] ?? ''); ?></span>
                <?php if (isset($r['probability'])): ?>
                    <span class="pct">(<?php echo number_format((float)$r['probability'] * 100, 1); ?>%)</span>
                <?php endif; ?>
                <?php if (!empty($r['career_options'])): ?>
                    <div class="careers"><?php echo htmlspecialchars($r['career_options']); ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php elseif (!$errorMessage): ?>
            <p>لم يتم إرجاع أي توصيات.</p>
        <?php endif; ?>
        <a href="" class="back-link">← العودة لتعبئة الاستبيان من جديد</a>
    </div>
<?php endif; ?>
</div>
</body>
</html>
