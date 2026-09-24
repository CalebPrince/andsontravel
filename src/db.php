<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../database/seed_data.php';

/**
 * Returns a shared PDO connection to the SQLite database, creating the
 * database file, applying the schema, and seeding default content as
 * needed. Schema application and seeding are both idempotent, so this is
 * safe to run on every request.
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dbDir = dirname(DB_PATH);
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0775, true);
    }

    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec(file_get_contents(DB_SCHEMA));

    addColumnIfMissing($pdo, 'admins', 'role', "TEXT NOT NULL DEFAULT 'admin'", function (PDO $pdo) {
        $pdo->exec("UPDATE admins SET role = 'super_admin'");
    });
    addColumnIfMissing($pdo, 'applications', 'extra_fields', 'TEXT');
    addColumnIfMissing($pdo, 'applications', 'seen_at', 'TEXT');
    addColumnIfMissing($pdo, 'contact_messages', 'seen_at', 'TEXT');

    seedDefaultAdmin($pdo);
    seedServices($pdo);
    seedFaqs($pdo);
    seedSettings($pdo);

    return $pdo;
}

/**
 * Adds a column to an existing table if it's not already there. SQLite's
 * ADD COLUMN errors out if the column already exists, so this checks first
 * via PRAGMA table_info. An optional $afterAdd callback runs once, only when
 * the column was actually just added, for one-time backfills.
 */
function addColumnIfMissing(PDO $pdo, string $table, string $column, string $definition, ?callable $afterAdd = null): void
{
    $columns = $pdo->query('PRAGMA table_info(' . $table . ')')->fetchAll();
    foreach ($columns as $existing) {
        if ($existing['name'] === $column) {
            return;
        }
    }

    $pdo->exec("ALTER TABLE $table ADD COLUMN $column $definition");

    if ($afterAdd) {
        $afterAdd($pdo);
    }
}

function seedDefaultAdmin(PDO $pdo): void
{
    $count = (int) $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash, role) VALUES (:username, :hash, :role)');
    $stmt->execute([
        'username' => DEFAULT_ADMIN_USER,
        'hash'     => password_hash(DEFAULT_ADMIN_PASS, PASSWORD_DEFAULT),
        'role'     => 'super_admin',
    ]);
}

function seedServices(PDO $pdo): void
{
    $count = (int) $pdo->query('SELECT COUNT(*) FROM services')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO services (slug, title, summary, icon, sort_order) VALUES (:slug, :title, :summary, :icon, :sort_order)'
    );
    foreach (seedServicesData() as $i => $service) {
        $stmt->execute([
            'slug'       => $service['slug'],
            'title'      => $service['title'],
            'summary'    => $service['summary'],
            'icon'       => $service['icon'],
            'sort_order' => $i * 10,
        ]);
    }
}

function seedFaqs(PDO $pdo): void
{
    $count = (int) $pdo->query('SELECT COUNT(*) FROM faqs')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO faqs (question, answer, sort_order) VALUES (:q, :a, :sort_order)'
    );
    foreach (seedFaqsData() as $i => $faq) {
        $stmt->execute([
            'q'          => $faq['q'],
            'a'          => $faq['a'],
            'sort_order' => $i * 10,
        ]);
    }
}

function seedSettings(PDO $pdo): void
{
    $stmt = $pdo->prepare(
        'INSERT OR IGNORE INTO settings (setting_key, setting_value) VALUES (:key, :value)'
    );
    foreach (seedSettingsData() as $key => $value) {
        $stmt->execute(['key' => $key, 'value' => $value]);
    }
}
