<?php
declare(strict_types=1);

/**
 * Admin header notification bell: a merged, unread feed of new service
 * bookings and new enquiries. Deliberately separate from the `status`
 * column on each table (new/contacted/... is a work pipeline; seen_at is
 * just "has anyone glanced at the bell for this yet").
 */

function countUnreadNotifications(): int
{
    $apps = (int) db()->query('SELECT COUNT(*) FROM applications WHERE seen_at IS NULL')->fetchColumn();
    $msgs = (int) db()->query('SELECT COUNT(*) FROM contact_messages WHERE seen_at IS NULL')->fetchColumn();
    return $apps + $msgs;
}

/** @return array<int, array{type:string, id:int, title:string, subtitle:string, url:string, created_at:string}> */
function getUnreadNotifications(int $limit = 8): array
{
    $stmt = db()->prepare(
        "SELECT id, service_title AS title, full_name, created_at FROM applications
         WHERE seen_at IS NULL ORDER BY created_at DESC LIMIT :limit"
    );
    $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $items = [];
    foreach ($stmt->fetchAll() as $row) {
        $items[] = [
            'type'       => 'booking',
            'id'         => (int) $row['id'],
            'title'      => 'New booking: ' . $row['title'],
            'subtitle'   => $row['full_name'],
            'url'        => '/admin/application.php?id=' . $row['id'],
            'created_at' => $row['created_at'],
        ];
    }

    $stmt = db()->prepare(
        "SELECT id, subject, full_name, created_at FROM contact_messages
         WHERE seen_at IS NULL ORDER BY created_at DESC LIMIT :limit"
    );
    $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    foreach ($stmt->fetchAll() as $row) {
        $items[] = [
            'type'       => 'enquiry',
            'id'         => (int) $row['id'],
            'title'      => 'New enquiry' . ($row['subject'] ? ': ' . $row['subject'] : ''),
            'subtitle'   => $row['full_name'],
            'url'        => '/admin/message.php?id=' . $row['id'],
            'created_at' => $row['created_at'],
        ];
    }

    usort($items, fn ($a, $b) => strcmp($b['created_at'], $a['created_at']));

    return array_slice($items, 0, $limit);
}

function markAllNotificationsRead(): void
{
    $pdo = db();
    $pdo->exec("UPDATE applications SET seen_at = datetime('now') WHERE seen_at IS NULL");
    $pdo->exec("UPDATE contact_messages SET seen_at = datetime('now') WHERE seen_at IS NULL");
}

/** Mark a single notification as read. Returns whether a row was actually unread. */
function markNotificationRead(string $type, int $id): bool
{
    $table = match ($type) {
        'booking' => 'applications',
        'enquiry' => 'contact_messages',
        default => null,
    };
    if ($table === null || $id < 1) {
        return false;
    }
    $stmt = db()->prepare("UPDATE {$table} SET seen_at = datetime('now') WHERE id = :id AND seen_at IS NULL");
    $stmt->execute(['id' => $id]);
    return $stmt->rowCount() > 0;
}
