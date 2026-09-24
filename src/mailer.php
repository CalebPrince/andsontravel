<?php
declare(strict_types=1);

/**
 * Minimal email notifications using PHP's built-in mail() — no SMTP
 * library or API key required. Every send is best-effort: a failure is
 * logged with error_log() and never breaks the page (the database write
 * already succeeded by the time these run, which is what actually matters).
 *
 * This depends entirely on the host having a working mail transport
 * (sendmail/postfix locally configured, or the hosting provider's own MTA).
 * That's true of virtually every real shared/VPS PHP host, but NOT of
 * PHP's built-in `php -S` dev server, which has no mail transport at all —
 * sends made while running locally will fail silently into error_log.
 */

function sendAppEmail(string $to, string $subject, string $bodyHtml): bool
{
    if (!ENABLE_EMAIL_NOTIFICATIONS) {
        return false;
    }

    $headers   = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM_ADDRESS . '>';
    $headers[] = 'Reply-To: ' . CONTACT_EMAIL;

    try {
        $sent = @mail($to, $subject, $bodyHtml, implode("\r\n", $headers));
        if (!$sent) {
            error_log('[mail] send failed to ' . $to . ': ' . $subject);
        }
        return $sent;
    } catch (\Throwable $e) {
        error_log('[mail] send exception: ' . $e->getMessage());
        return false;
    }
}

/** Builds a small, self-contained HTML email body: heading, intro line, and a label/value table. */
function emailTemplate(string $heading, string $introHtml, array $rows): string
{
    $rowsHtml = '';
    foreach ($rows as $label => $value) {
        $display = ($value !== '' && $value !== null)
            ? nl2br(e((string) $value))
            : '<span style="color:#94a3b8;">Not provided</span>';
        $rowsHtml .= '<tr>'
            . '<td style="padding:8px 12px;border-bottom:1px solid #eee;font-size:12px;font-weight:600;color:#64748b;white-space:nowrap;vertical-align:top;">' . e((string) $label) . '</td>'
            . '<td style="padding:8px 12px;border-bottom:1px solid #eee;font-size:14px;color:#0f172a;">' . $display . '</td>'
            . '</tr>';
    }

    return '<div style="font-family:Arial,Helvetica,sans-serif;max-width:600px;margin:0 auto;color:#0f172a;">'
        . '<div style="background:#192841;padding:24px;border-radius:12px 12px 0 0;">'
        . '<h1 style="color:#fff;font-size:18px;margin:0;">' . e(SITE_NAME) . '</h1>'
        . '</div>'
        . '<div style="border:1px solid #e2e8f0;border-top:none;padding:24px;border-radius:0 0 12px 12px;">'
        . '<h2 style="font-size:16px;margin:0 0 12px;">' . e($heading) . '</h2>'
        . '<p style="font-size:14px;color:#475569;margin:0 0 16px;">' . $introHtml . '</p>'
        . '<table style="width:100%;border-collapse:collapse;">' . $rowsHtml . '</table>'
        . '</div>'
        . '<p style="font-size:11px;color:#94a3b8;text-align:center;margin-top:16px;">' . e(SITE_NAME) . ' &middot; ' . e(CONTACT_ADDRESS) . '</p>'
        . '</div>';
}
