<?php
declare(strict_types=1);

/**
 * Services are stored in the database and managed from the admin area
 * (Admin > Services). Used to render the services grid, generate one
 * /apply.php?service=slug page per service, and validate incoming
 * application form submissions.
 *
 * @return array<string, array{id:int, title:string, summary:string, icon:string, sort_order:int, is_active:bool}>
 */
function services(bool $onlyActive = true): array
{
    $sql = 'SELECT * FROM services';
    if ($onlyActive) {
        $sql .= ' WHERE is_active = 1';
    }
    $sql .= ' ORDER BY sort_order ASC, id ASC';

    $result = [];
    foreach (db()->query($sql)->fetchAll() as $row) {
        $result[$row['slug']] = mapServiceRow($row);
    }
    return $result;
}

/** Find an active service by slug, for the public apply flow. */
function findService(string $slug): ?array
{
    $stmt = db()->prepare('SELECT * FROM services WHERE slug = :slug AND is_active = 1');
    $stmt->execute(['slug' => $slug]);
    $row = $stmt->fetch();
    if (!$row) {
        return null;
    }
    return mapServiceRow($row) + ['slug' => $row['slug']];
}

/** Find a service by id regardless of active status, for the admin edit form. */
function getServiceById(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM services WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    if (!$row) {
        return null;
    }
    return mapServiceRow($row) + ['slug' => $row['slug']];
}

function slugExists(string $slug, ?int $exceptId = null): bool
{
    $sql = 'SELECT COUNT(*) FROM services WHERE slug = :slug';
    $params = ['slug' => $slug];
    if ($exceptId !== null) {
        $sql .= ' AND id != :id';
        $params['id'] = $exceptId;
    }
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn() > 0;
}

function createService(string $slug, string $title, string $summary, string $icon, int $sortOrder, bool $isActive): void
{
    $stmt = db()->prepare(
        'INSERT INTO services (slug, title, summary, icon, sort_order, is_active)
         VALUES (:slug, :title, :summary, :icon, :sort_order, :is_active)'
    );
    $stmt->execute([
        'slug'       => $slug,
        'title'      => $title,
        'summary'    => $summary,
        'icon'       => $icon,
        'sort_order' => $sortOrder,
        'is_active'  => $isActive ? 1 : 0,
    ]);
}

function updateService(int $id, string $slug, string $title, string $summary, string $icon, int $sortOrder, bool $isActive): void
{
    $stmt = db()->prepare(
        "UPDATE services SET slug = :slug, title = :title, summary = :summary, icon = :icon,
         sort_order = :sort_order, is_active = :is_active, updated_at = datetime('now')
         WHERE id = :id"
    );
    $stmt->execute([
        'slug'       => $slug,
        'title'      => $title,
        'summary'    => $summary,
        'icon'       => $icon,
        'sort_order' => $sortOrder,
        'is_active'  => $isActive ? 1 : 0,
        'id'         => $id,
    ]);
}

function deleteService(int $id): void
{
    db()->prepare('DELETE FROM services WHERE id = :id')->execute(['id' => $id]);
}

function mapServiceRow(array $row): array
{
    return [
        'id'         => (int) $row['id'],
        'title'      => $row['title'],
        'summary'    => $row['summary'],
        'icon'       => $row['icon'],
        'sort_order' => (int) $row['sort_order'],
        'is_active'  => (bool) $row['is_active'],
    ];
}
