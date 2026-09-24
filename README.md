# Andson Travel Consult: PHP + SQLite + Tailwind

A rebuild of [andsontravelconsult.com](https://andsontravelconsult.com/) as a plain-PHP site with a
SQLite-backed application/contact system and a Tailwind CSS v4 front end. No framework, no Composer
dependencies: just PHP's built-in PDO SQLite driver and a Tailwind CLI build step for CSS.

## Requirements

- PHP 8.1+ with the `pdo_sqlite` extension (bundled with PHP by default)
- Node.js (only needed to build the Tailwind CSS bundle, not required at runtime)

## Local development

```bash
npm install          # once, to pull in the Tailwind CLI
npm run build:css    # compiles src/input.css -> public/assets/css/app.css
npm run serve        # starts php -S 127.0.0.1:8000 -t public
```

Then visit `http://127.0.0.1:8000`. While actively changing styles, run `npm run watch:css` in a
second terminal instead of `build:css`.

The SQLite database (`database/andson.sqlite`) and a default admin account are created
automatically the first time any page runs; there is no manual migration step. Schema changes are
applied idempotently on every request, so pulling a newer version of this repo onto an existing
database (with real bookings/enquiries already in it) is always safe.

## Admin area

Visit `/admin/login.php` (also linked as "Staff Login" at the bottom of the site footer).

Two accounts exist by default:

| Username | Password      | Role        |
|----------|---------------|-------------|
| `admin`  | `Andson@2026` | Super Admin |
| `staff`  | `Staff@2026`  | Admin       |

**Change both passwords immediately** from Admin > Settings (each user changes their own) after
first login; these are published defaults, not secrets.

Super Admin and Admin currently have identical access, except that only Super Admin can reach the
Users page. That split exists so more roles with narrower permissions can be added later without
reworking anything.

The admin area has a sidebar with:

- **Dashboard**: stats (bookings, enquiries, live services/FAQs) and recent activity
- **Service Bookings**: view, filter, search, and update the status of service applications
  submitted from `/apply.php`
- **Enquiries**: view, filter, search, and update the status of general contact messages
  submitted from `/contact.php`
- **Services**: add, edit, reorder, activate/deactivate, or delete the services shown on the
  homepage, `/services.php`, and each `/apply.php?service=...` page (this replaces editing PHP
  code to change service content)
- **FAQs**: add, edit, reorder, activate/deactivate, or delete the FAQs shown on the homepage and
  `/faqs.php`
- **Social Links**: set the Facebook and Instagram URLs shown as icons in the site footer (left
  blank by default, so no icon shows until a real URL is set)
- **Users** (Super Admin only): add new admin users, change a user's role or reset their
  password, or remove a user (you can't delete your own account or the last remaining Super Admin)
- **Settings**: change your own password

A notification bell in the top-right of every admin page shows a badge count and dropdown of
unread service bookings and enquiries (newest first), with a "Mark all as read" link. This read
state (`seen_at` on each row) is separate from the `status` field used for the bookings/enquiries
work pipeline, so clearing notifications never changes an item's actual status.

## Service application forms

Each `/apply.php?service=<slug>` page renders one of two things:

- **A form ported field-for-field from the live site.** Seven of the ten services
  (One-on-One Travel Advice, Online Passport Form Filling, US Visa Form Filling Assistance,
  UK Visa Form Filling Assistance, Visa Application Fee Payment, Visa Pick Up Service, and
  Counselling for Prospective Students) had a real, distinct form on andsontravelconsult.com with
  its own fields, labels, and option lists. Those were captured directly from the live pages and
  are defined in `src/data/service_forms.php`, one schema per slug.
- **A generic fallback form** (name, email, phone, destination, message) for the remaining three
  services (Airbnb/Booking.com Booking, Visa Application Assistance, Travel Insurance Assistance),
  which did not have a working form on the live site to copy.

Every submission is saved to the `applications` table. For schema-based forms, every answer is
also stored as JSON in `applications.extra_fields` and rendered as a full label/answer list on the
booking's admin detail page, in addition to populating the `full_name`/`email`/`phone` columns
used by the admin list view.

## Email notifications

`src/mailer.php` sends two emails on every successful submission (service booking or contact
enquiry): one to the business inbox (`ADMIN_NOTIFY_EMAIL` in `src/config.php`, defaults to the
site's contact email) with the full submission, and one confirmation to the person who submitted
the form.

This uses PHP's built-in `mail()` function: no SMTP library, API key, or Composer dependency.
That means it depends entirely on the host having a working mail transport:

- **Real shared/VPS hosting** (cPanel, etc.) almost always has this configured already; email
  should work as soon as the site is deployed.
- **PHP's built-in dev server** (`php -S`, used for local development) has no mail transport at
  all. Sends will fail locally, every time, this is expected. Failures are caught and logged via
  `error_log()`; they never break the page or block a submission from being saved.
- If your eventual host also can't send mail reliably (some block outbound port 25, or you'd
  rather use a transactional email API), swap the internals of `sendAppEmail()` in
  `src/mailer.php` for an SMTP/API-based sender; nothing else needs to change since every caller
  just uses `sendAppEmail($to, $subject, $htmlBody)`.

## Project layout

```
public/            Web root — point your server here (Apache/Nginx/`php -S`)
  index.php, about.php, faqs.php, contact.php, apply.php, privacy-policy.php, terms.php
  admin/            Password-protected admin area
  assets/           Compiled CSS, JS, and the site logo
src/                PHP includes (never web-accessible directly)
  config.php, db.php, helpers.php, mailer.php, auth.php, icons.php, bootstrap.php
  data/             Services, FAQs, admin users, and service-form schemas (content itself
                    lives in the database, edited from Admin > Services / Admin > FAQs,
                    not in these files, except the form schemas which are code since
                    they were ported directly from the live site)
  partials/         Shared header/footer/flash-message templates
database/           SQLite database + schema.sql + seed_data.php (default services/FAQs
                    inserted only the first time each table is empty; the .sqlite file
                    itself is gitignored)
```

## Deploying to a real host (Apache/shared hosting)

1. Point the web server's document root at `public/`, **not** the project root. `src/` and
   `database/` must not be web-accessible; `.htaccess` files inside them deny direct access as a
   second line of defense even if the document root is ever misconfigured.
2. Upload everything except `node_modules/`. Run `npm run build:css` locally first and upload the
   resulting `public/assets/css/app.css` (Node isn't needed on the server itself).
3. Make sure `database/` is writable by the PHP process (it creates `andson.sqlite` there on
   first request).
4. Log in to `/admin/login.php` and change both default admin passwords right away.
5. Send a test booking and a test enquiry, then check the business inbox (and its spam folder) to
   confirm outbound mail actually works on that host.

## What's intentionally not built

- **Full page-content editing.** The CMS covers Services, FAQs, and Social Links, the pieces
  named for admin editing, plus the two forms that receive bookings/enquiries. It does not cover
  free-form page prose (About page story, Privacy Policy/Terms text, hero taglines) or the phone
  number/email/address in `src/config.php`; editing those still means editing the PHP/config
  files directly. Turning those into editable fields too is a larger undertaking (a rich text
  editor, versioning, etc.) that wasn't part of what was asked for.
- **Fine-grained roles.** Multi-user accounts with roles now exist (Admin > Users, Super Admin
  only), but Super Admin and Admin currently grant identical access. Nothing today is restricted
  to Super Admin except the Users page itself. Give Admin a narrower permission set once it's
  clear what staff accounts should and shouldn't be able to do.
- **The 3 planned marketing graphics** (hero world-map banner, an About-page passport/boarding-pass
  flat-lay, and a Contact-page map-pin graphic) were not generated; Higgsfield (the required image
  generator for this project) was out of credits at build time. The site currently uses gradients
  and the inline SVG icon set in their place; these can be generated and dropped in later.
