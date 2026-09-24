<?php
declare(strict_types=1);

/**
 * Central site configuration. Nothing here should need to change between
 * environments except SITE_URL when the site is deployed to its real domain.
 */

const SITE_NAME  = 'Andson Travel Consult';
const SITE_TAGLINE = 'Beyond The Travel Agent';
const SITE_URL   = '';

const CONTACT_PHONE_DISPLAY = '+233 26 254 6032';
const CONTACT_PHONE_TEL     = '+233262546032';
const CONTACT_WHATSAPP_URL  = 'https://wa.me/233262546032';
const CONTACT_EMAIL         = 'andsontravelconsult@gmail.com';
const CONTACT_ADDRESS       = 'Accra, Ghana';

// Email notifications (src/mailer.php) use PHP's built-in mail() — no SMTP
// library/API key needed, but the host must have a working mail transport
// (true of virtually every real PHP host; local `php -S` usually has none,
// so sends will silently fail there — see README).
const ENABLE_EMAIL_NOTIFICATIONS = true;
const MAIL_FROM_NAME    = SITE_NAME;
const MAIL_FROM_ADDRESS = CONTACT_EMAIL;
const ADMIN_NOTIFY_EMAIL = CONTACT_EMAIL;

const DB_PATH     = __DIR__ . '/../database/andson.sqlite';
const DB_SCHEMA   = __DIR__ . '/../database/schema.sql';

// Default admin account, created automatically the first time the database
// is initialized. Change the password immediately from Admin > Settings.
const DEFAULT_ADMIN_USER = 'admin';
const DEFAULT_ADMIN_PASS = 'Andson@2026';

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
]);
