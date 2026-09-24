<?php
require_once __DIR__ . '/../src/bootstrap.php';

$slug = trim((string) ($_GET['service'] ?? $_POST['service'] ?? ''));
$service = $slug !== '' ? findService($slug) : null;
$schema = $service ? getServiceFormSchema($service['slug']) : null;

$errors = [];

if ($service && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) {
        $errors[] = 'Your session expired. Please try submitting the form again.';
    } elseif ($schema) {
        // Service-specific form (ported from the live site): fields vary per
        // service, so every answer is captured in $answers (stored as JSON in
        // extra_fields) while fields flagged 'core' also populate the
        // dedicated full_name/email/phone/message columns for the admin list.
        $answers = [];
        $core = ['full_name' => null, 'email' => null, 'phone' => null, 'message' => null];

        foreach ($schema['fields'] as $field) {
            $raw = trim((string) ($_POST[$field['key']] ?? ''));

            if ($field['required'] && $raw === '') {
                $errors[] = 'Please fill in "' . $field['label'] . '".';
            }
            if ($raw !== '') {
                if ($field['type'] === 'email' && !filter_var($raw, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Please enter a valid email address for "' . $field['label'] . '".';
                }
                if ($field['type'] === 'select' && !in_array($raw, $field['options'], true)) {
                    $errors[] = 'Please choose a valid option for "' . $field['label'] . '".';
                }
                if (mb_strlen($raw) > 4000) {
                    $errors[] = '"' . $field['label'] . '" is too long.';
                }
            }

            $answers[$field['label']] = $raw;
            if (!empty($field['core'])) {
                $core[$field['core']] = $raw;
            }
        }

        if (!$errors) {
            $stmt = db()->prepare(
                'INSERT INTO applications (service_slug, service_title, full_name, email, phone, message, extra_fields)
                 VALUES (:slug, :title, :full_name, :email, :phone, :message, :extra_fields)'
            );
            $stmt->execute([
                'slug'         => $service['slug'],
                'title'        => $service['title'],
                'full_name'    => (string) $core['full_name'],
                'email'        => (string) $core['email'],
                'phone'        => (string) $core['phone'],
                'message'      => $core['message'] !== '' ? $core['message'] : null,
                'extra_fields' => json_encode($answers),
            ]);

            sendAppEmail(
                ADMIN_NOTIFY_EMAIL,
                'New Booking for ' . $service['title'] . ' from ' . $core['full_name'],
                emailTemplate('New Service Booking: ' . $service['title'], 'A new application came in through the website.', $answers)
            );
            if ($core['email']) {
                sendAppEmail(
                    $core['email'],
                    'Application Received: ' . $service['title'],
                    emailTemplate('Thanks, ' . $core['full_name'] . '!', 'We’ve received your application for <strong>' . e($service['title']) . '</strong> and a member of our team will follow up shortly. Here’s a copy of what you submitted:', $answers)
                );
            }

            clearOldInput();
            flash('success', 'Thanks' . ($core['full_name'] ? ', ' . $core['full_name'] : '') . '! Your application for "' . $service['title'] . '" has been received. We’ll be in touch shortly.');
            redirect('/apply.php?service=' . urlencode($service['slug']));
        }

        $_SESSION['old_input'] = $_POST;
    } else {
        $fullName    = trim((string) ($_POST['full_name'] ?? ''));
        $email       = trim((string) ($_POST['email'] ?? ''));
        $phone       = trim((string) ($_POST['phone'] ?? ''));
        $destination = trim((string) ($_POST['destination'] ?? ''));
        $message     = trim((string) ($_POST['message'] ?? ''));

        if ($fullName === '' || mb_strlen($fullName) > 120) {
            $errors[] = 'Please enter your full name.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        if ($phone === '' || mb_strlen($phone) > 40) {
            $errors[] = 'Please enter a phone or WhatsApp number we can reach you on.';
        }
        if (mb_strlen($message) > 4000) {
            $errors[] = 'Your message is too long. Please shorten it to under 4000 characters.';
        }

        if (!$errors) {
            $stmt = db()->prepare(
                'INSERT INTO applications (service_slug, service_title, full_name, email, phone, destination, message)
                 VALUES (:slug, :title, :full_name, :email, :phone, :destination, :message)'
            );
            $stmt->execute([
                'slug'        => $service['slug'],
                'title'       => $service['title'],
                'full_name'   => $fullName,
                'email'       => $email,
                'phone'       => $phone,
                'destination' => $destination !== '' ? $destination : null,
                'message'     => $message !== '' ? $message : null,
            ]);

            $genericRows = [
                'Full Name'   => $fullName,
                'Email'       => $email,
                'Phone'       => $phone,
                'Destination' => $destination,
                'Message'     => $message,
            ];
            sendAppEmail(
                ADMIN_NOTIFY_EMAIL,
                'New Booking for ' . $service['title'] . ' from ' . $fullName,
                emailTemplate('New Service Booking: ' . $service['title'], 'A new application came in through the website.', $genericRows)
            );
            sendAppEmail(
                $email,
                'Application Received: ' . $service['title'],
                emailTemplate('Thanks, ' . $fullName . '!', 'We’ve received your application for <strong>' . e($service['title']) . '</strong> and a member of our team will follow up shortly.', $genericRows)
            );

            clearOldInput();
            flash('success', 'Thanks, ' . $fullName . '! Your application for "' . $service['title'] . '" has been received. We’ll be in touch shortly.');
            redirect('/apply.php?service=' . urlencode($service['slug']));
        }

        $_SESSION['old_input'] = compact('fullName', 'email', 'phone', 'destination', 'message');
    }
}

$pageTitle = $service ? 'Apply for ' . $service['title'] . ': ' . SITE_NAME : 'Apply for a Service: ' . SITE_NAME;
$pageDescription = $service
    ? 'Apply for ' . $service['title'] . ' with Andson Travel Consult.'
    : 'Choose a service to apply for with Andson Travel Consult.';
$activeNav = 'home';

require __DIR__ . '/../src/partials/header.php';
require __DIR__ . '/../src/partials/flash.php';
?>

<?php if (!$service): ?>

  <section class="bg-navy-900">
    <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
      <span class="section-eyebrow bg-white/10 text-brand-400">Apply for Assistance</span>
      <h1 class="mt-5 font-display text-3xl font-extrabold text-white sm:text-4xl">Which service do you need?</h1>
      <p class="mx-auto mt-5 max-w-2xl text-lg text-white/70">Pick a service below to start your application.</p>
    </div>
  </section>

  <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
    <?php if ($slug !== ''): ?>
      <div class="mb-8 rounded-xl border border-red-500/30 bg-red-50 p-4 text-center text-sm font-medium text-red-800">
        We couldn&rsquo;t find that service. Please choose one from the list below.
      </div>
    <?php endif; ?>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach (services() as $svcSlug => $svc): ?>
        <a href="/apply.php?service=<?= urlencode($svcSlug) ?>" class="card flex flex-col p-6">
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-600/10 text-brand-700">
            <?= icon($svc['icon'], 'h-6 w-6') ?>
          </div>
          <h3 class="mt-5 font-display text-lg font-bold text-navy-900"><?= e($svc['title']) ?></h3>
          <p class="mt-2 flex-1 text-sm leading-relaxed text-navy-900/60"><?= e($svc['summary']) ?></p>
          <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-bold text-brand-700">Apply Now <?= icon('arrow-right', 'h-4 w-4') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

<?php else: ?>

  <section class="bg-navy-900">
    <div class="mx-auto max-w-3xl px-4 py-16 text-center sm:px-6 lg:px-8">
      <span class="section-eyebrow bg-white/10 text-brand-400"><?= icon($service['icon'], 'h-3.5 w-3.5') ?> Service Application</span>
      <h1 class="mt-5 font-display text-3xl font-extrabold text-white sm:text-4xl"><?= e($service['title']) ?></h1>
      <p class="mx-auto mt-5 max-w-xl text-lg text-white/70"><?= e($service['summary']) ?></p>
    </div>
  </section>

  <section class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-6 rounded-xl border border-amber-500/20 bg-amber-50 px-5 py-4 text-center text-sm text-amber-800">
      We are not a visa connection agency and do not guarantee visa outcomes. We&rsquo;ll do everything we can to help
      you apply correctly and confidently.
    </div>

    <form method="post" action="/apply.php?service=<?= urlencode($service['slug']) ?>" data-guard class="card space-y-5 p-6 sm:p-8">
      <?= csrfField() ?>
      <input type="hidden" name="service" value="<?= e($service['slug']) ?>">

      <?php if ($errors): ?>
        <div class="rounded-xl border border-red-500/30 bg-red-50 p-4 text-sm text-red-800">
          <ul class="list-inside list-disc space-y-1">
            <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($schema): ?>

        <?php foreach ($schema['fields'] as $field): $fid = 'f_' . $field['key']; ?>
          <div>
            <label for="<?= e($fid) ?>" class="field-label">
              <?= e($field['label']) ?><?php if (!$field['required']): ?><span class="font-normal text-navy-900/40"> (optional)</span><?php endif; ?>
            </label>

            <?php if ($field['type'] === 'select'): ?>
              <select id="<?= e($fid) ?>" name="<?= e($field['key']) ?>" <?= $field['required'] ? 'required' : '' ?> class="field-input">
                <option value="">Select One</option>
                <?php foreach ($field['options'] as $opt): ?>
                  <option value="<?= e($opt) ?>" <?= oldInput($field['key']) === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
                <?php endforeach; ?>
              </select>
            <?php elseif ($field['type'] === 'textarea'): ?>
              <textarea id="<?= e($fid) ?>" name="<?= e($field['key']) ?>" <?= $field['required'] ? 'required' : '' ?> maxlength="4000" rows="4" class="field-input resize-none"><?= oldInput($field['key']) ?></textarea>
            <?php else: ?>
              <input type="<?= e($field['type']) ?>" id="<?= e($fid) ?>" name="<?= e($field['key']) ?>" <?= $field['required'] ? 'required' : '' ?> maxlength="500" class="field-input" value="<?= oldInput($field['key']) ?>">
            <?php endif; ?>

            <?php if (!empty($field['help'])): ?>
              <p class="mt-1.5 text-xs text-navy-900/40"><?= e($field['help']) ?></p>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>

        <button type="submit" class="btn-primary w-full">
          <?= e($schema['submit_label']) ?>
          <?= icon('arrow-right', 'h-4 w-4') ?>
        </button>

      <?php else: ?>

        <div>
          <label for="full_name" class="field-label">Full name</label>
          <input type="text" id="full_name" name="full_name" required maxlength="120" class="field-input" value="<?= oldInput('fullName') ?>" placeholder="Jane Mensah">
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
          <div>
            <label for="email" class="field-label">Email address</label>
            <input type="email" id="email" name="email" required maxlength="180" class="field-input" value="<?= oldInput('email') ?>" placeholder="jane@example.com">
          </div>
          <div>
            <label for="phone" class="field-label">Phone / WhatsApp number</label>
            <input type="tel" id="phone" name="phone" required maxlength="40" class="field-input" value="<?= oldInput('phone') ?>" placeholder="0XX XXX XXXX">
          </div>
        </div>

        <div>
          <label for="destination" class="field-label">Destination country <span class="font-normal text-navy-900/40">(optional)</span></label>
          <input type="text" id="destination" name="destination" maxlength="120" class="field-input" value="<?= oldInput('destination') ?>" placeholder="e.g. United States">
        </div>

        <div>
          <label for="message" class="field-label">Anything else we should know? <span class="font-normal text-navy-900/40">(optional)</span></label>
          <textarea id="message" name="message" maxlength="4000" rows="5" class="field-input resize-none" placeholder="Travel dates, current status, questions..."><?= oldInput('message') ?></textarea>
        </div>

        <button type="submit" class="btn-primary w-full">
          Submit Application
          <?= icon('arrow-right', 'h-4 w-4') ?>
        </button>

      <?php endif; ?>

      <p class="text-center text-xs text-navy-900/40">
        By submitting, you agree to our <a href="/privacy-policy.php" class="underline hover:text-brand-700">Privacy Policy</a>.
      </p>
    </form>
  </section>

<?php endif; ?>

<?php require __DIR__ . '/../src/partials/footer.php'; ?>
