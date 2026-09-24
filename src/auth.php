<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function isAdminLoggedIn(): bool
{
    return !empty($_SESSION['admin_id']);
}

function requireAdmin(): void
{
    if (!isAdminLoggedIn()) {
        redirect('/admin/login.php');
    }
}

/** Gate a page to super_admin only; other logged-in admins are bounced with a flash. */
function requireSuperAdmin(): void
{
    requireAdmin();
    if (!isSuperAdmin()) {
        flash('error', 'Only super admins can access that page.');
        redirect('/admin/index.php');
    }
}

function attemptAdminLogin(string $username, string $password): bool
{
    $stmt = db()->prepare('SELECT * FROM admins WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_role'] = $admin['role'];

    return true;
}

function currentAdminUsername(): string
{
    return $_SESSION['admin_username'] ?? '';
}

function currentAdminRole(): string
{
    return $_SESSION['admin_role'] ?? 'admin';
}

function isSuperAdmin(): bool
{
    return currentAdminRole() === 'super_admin';
}

function adminLogout(): void
{
    $_SESSION = [];
    session_regenerate_id(true);
}
