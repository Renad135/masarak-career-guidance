<?php
require_once __DIR__ . '/config/config.php';

if (isLoggedIn()) {
    session_destroy();
}

redirect(SITE_URL . '/index.php');
