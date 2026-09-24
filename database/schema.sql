-- Andson Travel Consult: SQLite schema
-- Applied automatically by src/db.php on every request (idempotent).

-- role: 'super_admin' | 'admin' (more roles may be added later; both currently
-- carry the same permissions except managing other admin users, which is
-- restricted to super_admin so there's already a place for future roles to
-- plug into without redesigning this table).
CREATE TABLE IF NOT EXISTS admins (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    username      TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    role          TEXT NOT NULL DEFAULT 'admin',
    created_at    TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS applications (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    service_slug  TEXT NOT NULL,
    service_title TEXT NOT NULL,
    full_name     TEXT NOT NULL,
    email         TEXT NOT NULL,
    phone         TEXT NOT NULL,
    destination   TEXT,
    message       TEXT,
    extra_fields  TEXT, -- JSON {label: answer} for service-specific form questions beyond the core columns
    status        TEXT NOT NULL DEFAULT 'new', -- new | contacted | in_progress | completed | closed
    seen_at       TEXT, -- set once cleared from the admin notification bell (separate from `status`)
    created_at    TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE INDEX IF NOT EXISTS idx_applications_status ON applications(status);
CREATE INDEX IF NOT EXISTS idx_applications_created ON applications(created_at);

CREATE TABLE IF NOT EXISTS contact_messages (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    full_name   TEXT NOT NULL,
    email       TEXT NOT NULL,
    phone       TEXT,
    subject     TEXT,
    message     TEXT NOT NULL,
    status      TEXT NOT NULL DEFAULT 'new', -- new | read | replied
    seen_at     TEXT, -- set once cleared from the admin notification bell (separate from `status`)
    created_at  TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE INDEX IF NOT EXISTS idx_contact_status ON contact_messages(status);

-- Content managed from the admin area, rendered on the public site.

CREATE TABLE IF NOT EXISTS services (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    slug        TEXT NOT NULL UNIQUE,
    title       TEXT NOT NULL,
    summary     TEXT NOT NULL,
    icon        TEXT NOT NULL DEFAULT 'sparkles',
    image       TEXT,
    sort_order  INTEGER NOT NULL DEFAULT 0,
    is_active   INTEGER NOT NULL DEFAULT 1,
    created_at  TEXT NOT NULL DEFAULT (datetime('now')),
    updated_at  TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE INDEX IF NOT EXISTS idx_services_order ON services(sort_order);

CREATE TABLE IF NOT EXISTS faqs (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    question    TEXT NOT NULL,
    answer      TEXT NOT NULL,
    sort_order  INTEGER NOT NULL DEFAULT 0,
    is_active   INTEGER NOT NULL DEFAULT 1,
    created_at  TEXT NOT NULL DEFAULT (datetime('now')),
    updated_at  TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE INDEX IF NOT EXISTS idx_faqs_order ON faqs(sort_order);

-- Simple key/value store for small editable bits (social links, etc.)
CREATE TABLE IF NOT EXISTS settings (
    setting_key   TEXT PRIMARY KEY,
    setting_value TEXT
);
