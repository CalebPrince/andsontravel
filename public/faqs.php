<?php
require_once __DIR__ . '/../src/bootstrap.php';

$pageTitle = 'FAQs: ' . SITE_NAME;
$pageDescription = 'Answers to the most common questions about visa applications, passport validity, appointments and travel authorization.';
$activeNav = 'faqs';

require __DIR__ . '/../src/partials/header.php';
?>

<section class="bg-navy-900">
  <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
    <span class="section-eyebrow bg-white/10 text-brand-400">General FAQs</span>
    <h1 class="mt-5 font-display text-3xl font-extrabold text-white sm:text-4xl">Frequently Asked Questions</h1>
    <p class="mx-auto mt-5 max-w-2xl text-lg text-white/70">
      Common questions and answers related to visa applications and travel procedures. Requirements change and vary
      by embassy, so treat this as general guidance and confirm specifics with us or the relevant embassy.
    </p>
    <p class="mt-3 text-xs text-white/40">Source: US Embassy in Ghana Website (<a href="https://ustraveldocs.com/gh/en/general-information#faqs" target="_blank" rel="noopener" class="font-semibold text-brand-400 underline hover:text-white">USTravelDocs</a>)</p>
  </div>
</section>

<section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
  <div class="divide-y divide-navy-900/10 rounded-2xl border border-navy-900/10 bg-white">
    <?php foreach (faqs() as $faq): ?>
      <details class="group p-6">
        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-display text-base font-bold text-navy-900">
          <?= e($faq['q']) ?>
          <span class="shrink-0 text-navy-900/40 transition group-open:rotate-180"><?= icon('chevron-down', 'h-5 w-5') ?></span>
        </summary>
        <p class="mt-3 text-sm leading-relaxed text-navy-900/65"><?= e($faq['a']) ?></p>
      </details>
    <?php endforeach; ?>
  </div>

  <div class="mt-10 rounded-2xl border border-navy-900/10 bg-sand-100 p-6 text-center">
    <h2 class="font-display text-base font-bold text-navy-900">Still have questions?</h2>
    <p class="mt-2 text-sm text-navy-900/60">Our team can walk you through your specific situation.</p>
    <div class="mt-5 flex flex-wrap items-center justify-center gap-3">
      <a href="/contact.php" class="btn-primary">Contact Us</a>
      <a href="<?= e(CONTACT_WHATSAPP_URL) ?>" target="_blank" rel="noopener" class="btn-whatsapp">
        <?= icon('whatsapp', 'h-5 w-5 text-white') ?> Chat on WhatsApp
      </a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../src/partials/footer.php'; ?>
