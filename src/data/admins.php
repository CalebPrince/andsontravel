<?php
declare(strict_types=1);

/**
 * Admin user accounts and roles, managed from Admin > Users (super_admin
 * only). More roles beyond 'super_admin' and 'admin' may be added later.
 */

function availableAdminRoles(): array
{
    return ['super_admin', 'admin'];
}

function adminRoleLabel(string $role): string
{
    return match ($role) {
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        default => ucfirst(str_replace('_', ' ', $role)),
    };
}

function allAdmins(): array
{
    return db()->query('SELECT id, username, role, created_at FROM admins ORDER BY id ASC')->fetchAll();
}

function getAdminById(int $id): ?array
{
    $stmt = db()->prepare('SELECT id, username, role, created_at FROM admins WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function adminUsernameExists(string $username, ?int $exceptId = null): bool
{
    $sql = 'SELECT COUNT(*) FROM admins WHERE username = :username';
    $params = ['username' => $username];
    if ($exceptId !== null) {
        $sql .= ' AND id != :id';
        $params['id'] = $exceptId;
    }
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn() > 0;
}

function createAdmin(string $username, string $password, string $role): void
{
    $stmt = db()->prepare(
        'INSERT INTO admins (username, password_hash, role) VALUES (:username, :hash, :role)'
    );
    $stmt->execute([
        'username' => $username,
        'hash'     => password_hash($password, PASSWORD_DEFAULT),
        'role'     => $role,
    ]);
}

function updateAdminRole(int $id, string $role): void
{
    db()->prepare('UPDATE admins SET role = :role WHERE id = :id')->execute(['role' => $role, 'id' => $id]);
}

function updateAdminPassword(int $id, string $password): void
{
    $stmt = db()->prepare('UPDATE admins SET password_hash = :hash WHERE id = :id');
    $stmt->execute(['hash' => password_hash($password, PASSWORD_DEFAULT), 'id' => $id]);
}

function deleteAdmin(int $id): void
{
    db()->prepare('DELETE FROM admins WHERE id = :id')->execute(['id' => $id]);
}

function countSuperAdmins(): int
{
    return (int) db()->query("SELECT COUNT(*) FROM admins WHERE role = 'super_admin'")->fetchColumn();
}
