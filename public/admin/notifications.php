<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) {
        flash('error', 'Your session expired. Please try again.');
    } else {
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'mark_all_read') {
            markAllNotificationsRead();
        }
    }
}

// Only ever redirect back to our own admin area, never to an arbitrary external referer.
$back = '/admin/index.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $refHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    $refPath = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? '';
    if ($refHost === $_SERVER['HTTP_HOST'] && str_starts_with($refPath, '/admin')) {
        $back = $_SERVER['HTTP_REFERER'];
    }
}
redirect($back);
