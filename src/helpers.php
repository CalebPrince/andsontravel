<?php
declare(strict_types=1);

/** Escape a string for safe HTML output. */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Turn a title into a URL-safe slug, e.g. "Visa Help!" -> "visa-help". */
function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

function csrfCheck(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Hidden anti-bot fields for the public forms: an off-screen "website" text
 * input that humans never see but naive bots blindly autofill, plus (when
 * enabled) a timestamp used to reject submissions quicker than a human could
 * type one. Rendered with inline styles so no Tailwind rebuild is needed.
 */
function botTrapFields(bool $withTimeTrap = true): string
{
    $html = '<input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" '
        . 'style="position:absolute;left:-9999px;top:-9999px;width:0;height:0;border:0;padding:0;opacity:0;">';
    if ($withTimeTrap) {
        $html .= '<input type="hidden" name="form_time" value="' . time() . '">';
    }
    return $html;
}

/**
 * True when a submission looks automated: the honeypot field was filled, or
 * (with the time trap enabled) the form was submitted too fast to have been
 * typed by a person. The time trap is skipped on short forms (login) where a
 * password manager could legitimately complete and submit them in under 3s.
 */
function isBotSubmission(bool $withTimeTrap = true): bool
{
    if (trim((string) ($_POST['website'] ?? '')) !== '') {
        return true;
    }
    if ($withTimeTrap) {
        $rendered = (int) ($_POST['form_time'] ?? 0);
        if ($rendered > 0 && time() - $rendered < 3) {
            return true;
        }
    }
    return false;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

/** Pull and clear any queued flash messages. */
function takeFlashes(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

function oldInput(string $key, string $default = ''): string
{
    return e($_SESSION['old_input'][$key] ?? $default);
}

function clearOldInput(): void
{
    unset($_SESSION['old_input']);
}

function timeAgo(string $datetime): string
{
    $diff = time() - strtotime($datetime . ' UTC');
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return date('M j, Y', strtotime($datetime . ' UTC'));
}

function statusLabel(string $status): string
{
    return match ($status) {
        'new' => 'New',
        'contacted' => 'Contacted',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'closed' => 'Closed',
        'read' => 'Read',
        'replied' => 'Replied',
        default => ucfirst($status),
    };
}

/** Read a small editable setting (e.g. a social link) with a fallback. */
function getSetting(string $key, string $default = ''): string
{
    $stmt = db()->prepare('SELECT setting_value FROM settings WHERE setting_key = :key');
    $stmt->execute(['key' => $key]);
    $value = $stmt->fetchColumn();
    return $value !== false && $value !== null && $value !== '' ? $value : $default;
}

function setSetting(string $key, string $value): void
{
    $stmt = db()->prepare(
        'INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)
         ON CONFLICT(setting_key) DO UPDATE SET setting_value = excluded.setting_value'
    );
    $stmt->execute(['key' => $key, 'value' => $value]);
}

/** Email address that receives new booking and enquiry notifications. */
function notificationEmail(): string
{
    $email = getSetting('notification_email', ADMIN_NOTIFY_EMAIL);
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : ADMIN_NOTIFY_EMAIL;
}

function statusBadgeClasses(string $status): string
{
    return match ($status) {
        'new' => 'badge bg-brand-600/10 text-brand-700',
        'contacted', 'read' => 'badge bg-amber-500/10 text-amber-700',
        'in_progress' => 'badge bg-amber-500/10 text-amber-700',
        'completed', 'replied' => 'badge bg-emerald-500/10 text-emerald-700',
        'closed' => 'badge bg-slate-400/10 text-slate-600',
        default => 'badge bg-slate-400/10 text-slate-600',
    };
}
