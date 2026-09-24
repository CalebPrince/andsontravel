<?php
declare(strict_types=1);

/**
 * FAQs are stored in the database and managed from the admin area
 * (Admin > FAQs). Shown on the home page and the dedicated FAQs page.
 *
 * @return array<int, array{id:int, q:string, a:string, sort_order:int, is_active:bool}>
 */
function faqs(bool $onlyActive = true): array
{
    $sql = 'SELECT * FROM faqs';
    if ($onlyActive) {
        $sql .= ' WHERE is_active = 1';
    }
    $sql .= ' ORDER BY sort_order ASC, id ASC';

    $result = [];
    foreach (db()->query($sql)->fetchAll() as $row) {
        $result[] = mapFaqRow($row);
    }
    return $result;
}

function getFaqById(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM faqs WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    return $row ? mapFaqRow($row) : null;
}

function createFaq(string $question, string $answer, int $sortOrder, bool $isActive): void
{
    $stmt = db()->prepare(
        'INSERT INTO faqs (question, answer, sort_order, is_active) VALUES (:q, :a, :sort_order, :is_active)'
    );
    $stmt->execute([
        'q'          => $question,
        'a'          => $answer,
        'sort_order' => $sortOrder,
        'is_active'  => $isActive ? 1 : 0,
    ]);
}

function updateFaq(int $id, string $question, string $answer, int $sortOrder, bool $isActive): void
{
    $stmt = db()->prepare(
        "UPDATE faqs SET question = :q, answer = :a, sort_order = :sort_order, is_active = :is_active,
         updated_at = datetime('now') WHERE id = :id"
    );
    $stmt->execute([
        'q'          => $question,
        'a'          => $answer,
        'sort_order' => $sortOrder,
        'is_active'  => $isActive ? 1 : 0,
        'id'         => $id,
    ]);
}

function deleteFaq(int $id): void
{
    db()->prepare('DELETE FROM faqs WHERE id = :id')->execute(['id' => $id]);
}

function mapFaqRow(array $row): array
{
    return [
        'id'         => (int) $row['id'],
        'q'          => $row['question'],
        'a'          => $row['answer'],
        'sort_order' => (int) $row['sort_order'],
        'is_active'  => (bool) $row['is_active'],
    ];
}
