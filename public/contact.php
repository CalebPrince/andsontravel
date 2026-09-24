<?php
require_once __DIR__ . '/../src/bootstrap.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) {
        $errors[] = 'Your session expired. Please try submitting the form again.';
    } else {
        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $email    = trim((string) ($_POST['email'] ?? ''));
        $phone    = trim((string) ($_POST['phone'] ?? ''));
        $subject  = trim((string) ($_POST['subject'] ?? ''));
        $message  = trim((string) ($_POST['message'] ?? ''));

        if ($fullName === '' || mb_strlen($fullName) > 120) {
            $errors[] = 'Please enter your full name.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        if ($message === '' || mb_strlen($message) < 10) {
            $errors[] = 'Please tell us a little more (at least a sentence or two).';
        }
        if (mb_strlen($message) > 4000) {
            $errors[] = 'Your message is too long. Please shorten it to under 4000 characters.';
        }

        if (!$errors) {
            $stmt = db()->prepare(
                'INSERT INTO contact_messages (full_name, email, phone, subject, message) VALUES (:full_name, :email, :phone, :subject, :message)'
            );
            $stmt->execute([
                'full_name' => $fullName,
                'email'     => $email,
                'phone'     => $phone !== '' ? $phone : null,
                'subject'   => $subject !== '' ? $subject : null,
                'message'   => $message,
            ]);

            sendAppEmail(
                notificationEmail(),
                'New Enquiry from ' . $fullName . ': ' . ($subject !== '' ? $subject : 'General Enquiry'),
                emailTemplate('New Website Enquiry', 'A new message came in through the contact form.', [
                    'Name'    => $fullName,
                    'Email'   => $email,
                    'Phone'   => $phone,
                    'Subject' => $subject,
                    'Message' => $message,
                ])
            );
            sendAppEmail(
                $email,
                'We’ve received your message: ' . SITE_NAME,
                emailTemplate('Thanks for reaching out, ' . $fullName . '!', 'We’ve received your message and a member of our team will get back to you shortly. Here’s a copy of what you sent:', [
                    'Subject' => $subject,
                    'Message' => $message,
                ])
            );

            clearOldInput();
            flash('success', 'Thanks, ' . $fullName . '! Your message has been sent. We’ll get back to you shortly.');
            redirect('/contact.php');
        }

        $_SESSION['old_input'] = compact('fullName', 'email', 'phone', 'subject', 'message');
    }
}

$pageTitle = 'Contact Us: ' . SITE_NAME;
$pageDescription = 'Get in touch with Andson Travel Consult in Accra, Ghana: by phone, WhatsApp, email, or our online contact form.';
$activeNav = 'contact';

require __DIR__ . '/../src/partials/header.php';
require __DIR__ . '/../src/partials/flash.php';
?>

<section class="bg-navy-900">
  <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
    <span class="section-eyebrow bg-white/10 text-brand-400">Get In Touch</span>
    <h1 class="mt-5 font-display text-3xl font-extrabold text-white sm:text-4xl">We&rsquo;d Love to Help</h1>
    <p class="mx-auto mt-5 max-w-2xl text-lg text-white/70">
      Have a general question, or not sure which service fits your situation? Send us a message and an agent will
      follow up promptly.
    </p>
  </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
  <div class="grid gap-10 lg:grid-cols-5">
    <div class="lg:col-span-2">
      <div class="space-y-4">
        <div class="card flex items-start gap-4 p-5">
          <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-600/10 text-brand-700"><?= icon('location', 'h-5 w-5') ?></div>
          <div>
            <h3 class="font-display text-sm font-bold text-navy-900">Office</h3>
            <p class="mt-1 text-sm text-navy-900/60"><?= e(CONTACT_ADDRESS) ?></p>
          </div>
        </div>
        <div class="card flex items-start gap-4 p-5">
          <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-600/10 text-brand-700"><?= icon('phone', 'h-5 w-5') ?></div>
          <div>
            <h3 class="font-display text-sm font-bold text-navy-900">Call or WhatsApp</h3>
            <a href="tel:<?= e(CONTACT_PHONE_TEL) ?>" class="mt-1 block text-sm text-navy-900/60 hover:text-brand-700"><?= e(CONTACT_PHONE_DISPLAY) ?></a>
            <a href="<?= e(CONTACT_WHATSAPP_URL) ?>" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-1.5 text-sm font-bold text-whatsapp-600 hover:text-whatsapp-500">
              <?= icon('whatsapp', 'h-4 w-4') ?> Message on WhatsApp
            </a>
          </div>
        </div>
        <div class="card flex items-start gap-4 p-5">
          <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-600/10 text-brand-700"><?= icon('mail', 'h-5 w-5') ?></div>
          <div>
            <h3 class="font-display text-sm font-bold text-navy-900">Email</h3>
            <a href="mailto:<?= e(CONTACT_EMAIL) ?>" class="mt-1 block break-all text-sm text-navy-900/60 hover:text-brand-700"><?= e(CONTACT_EMAIL) ?></a>
          </div>
        </div>
      </div>
    </div>

    <div class="lg:col-span-3">
      <form method="post" action="/contact.php" data-guard class="card space-y-5 p-6 sm:p-8">
        <?= csrfField() ?>

        <?php if ($errors): ?>
          <div class="rounded-xl border border-red-500/30 bg-red-50 p-4 text-sm text-red-800">
            <ul class="list-inside list-disc space-y-1">
              <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <div class="grid gap-5 sm:grid-cols-2">
          <div>
            <label for="full_name" class="field-label">Full name</label>
            <input type="text" id="full_name" name="full_name" required maxlength="120" class="field-input" value="<?= oldInput('fullName') ?>" placeholder="Jane Mensah">
          </div>
          <div>
            <label for="email" class="field-label">Email address</label>
            <input type="email" id="email" name="email" required maxlength="180" class="field-input" value="<?= oldInput('email') ?>" placeholder="jane@example.com">
          </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
          <div>
            <label for="phone" class="field-label">Phone / WhatsApp <span class="font-normal text-navy-900/40">(optional)</span></label>
            <input type="tel" id="phone" name="phone" maxlength="40" class="field-input" value="<?= oldInput('phone') ?>" placeholder="0XX XXX XXXX">
          </div>
          <div>
            <label for="subject" class="field-label">Subject <span class="font-normal text-navy-900/40">(optional)</span></label>
            <input type="text" id="subject" name="subject" maxlength="150" class="field-input" value="<?= oldInput('subject') ?>" placeholder="What can we help with?">
          </div>
        </div>

        <div>
          <label for="message" class="field-label">Message</label>
          <textarea id="message" name="message" required minlength="10" maxlength="4000" rows="5" class="field-input resize-none" placeholder="Tell us about your travel plans..."><?= oldInput('message') ?></textarea>
        </div>

        <button type="submit" class="btn-primary w-full sm:w-auto">
          Send Message
          <?= icon('arrow-right', 'h-4 w-4') ?>
        </button>
      </form>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../src/partials/footer.php'; ?>
