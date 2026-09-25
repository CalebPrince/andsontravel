<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

function jsonRespond(array $payload): never
{
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

// Live feed for the bell: polled by JS to refresh the count and dropdown without a reload.
if (($_GET['action'] ?? '') === 'feed') {
    jsonRespond([
        'count' => countUnreadNotifications(),
        'items' => array_map(static function (array $n): array {
            return [
                'type'     => $n['type'],
                'id'       => $n['id'],
                'title'    => $n['title'],
                'subtitle' => $n['subtitle'],
                'url'      => $n['url'],
                'time_ago' => timeAgo($n['created_at']),
            ];
        }, getUnreadNotifications()),
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch-driven requests (notification item clicks) expect a JSON reply.
    $wantsJson = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch'
        || str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');

    if (!csrfCheck()) {
        if ($wantsJson) jsonRespond(['ok' => false, 'error' => 'csrf']);
        flash('error', 'Your session expired. Please try again.');
    } else {
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'mark_all_read') {
            markAllNotificationsRead();
            if ($wantsJson) jsonRespond(['ok' => true]);
        } elseif ($action === 'mark_read') {
            $type = (string) ($_POST['type'] ?? '');
            $id   = (int) ($_POST['id'] ?? 0);
            if ($wantsJson) jsonRespond(['ok' => markNotificationRead($type, $id)]);
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
