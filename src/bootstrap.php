<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/mailer.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/data/services.php';
require_once __DIR__ . '/data/faqs.php';
require_once __DIR__ . '/data/admins.php';
require_once __DIR__ . '/data/service_forms.php';
require_once __DIR__ . '/data/notifications.php';

// Ensure the database (and default admin) exist as soon as anything boots.
db();
