<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/translations.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lang = isset($_POST['language']) ? sanitizeInput($_POST['language']) : 'ar';
    setLanguage($lang);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request method']);
?>
