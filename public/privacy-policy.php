<?php
require_once __DIR__ . '/../src/bootstrap.php';

$pageTitle = 'Privacy Policy: ' . SITE_NAME;
$pageDescription = 'How Andson Travel Consult collects, uses, and protects the personal information you share with us.';
$activeNav = '';

require __DIR__ . '/../src/partials/header.php';
?>

<section class="bg-navy-900">
  <div class="mx-auto max-w-3xl px-4 py-14 text-center sm:px-6 lg:px-8">
    <span class="section-eyebrow bg-white/10 text-brand-400">Legal</span>
    <h1 class="mt-5 font-display text-3xl font-extrabold text-white sm:text-4xl">Privacy Policy</h1>
    <p class="mt-4 text-sm text-white/50">Last updated: <?= date('F Y') ?></p>
  </div>
</section>

<section class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
  <div class="space-y-8 text-sm leading-relaxed text-navy-900/75">

    <p>
      This policy explains what information Andson Travel Consult ("we", "us", "our") collects when you use this
      website or apply for our services, how we use it, and the choices you have. This is a general policy for a
      small consultancy website and is not a substitute for tailored legal advice.
    </p>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">1. Information We Collect</h2>
      <p class="mt-2">When you submit a contact form or apply for a service, we collect the details you provide directly, which typically include:</p>
      <ul class="mt-2 list-disc space-y-1 pl-5">
        <li>Your full name, email address, and phone/WhatsApp number.</li>
        <li>The service you are applying for and, where relevant, your destination country.</li>
        <li>Any additional details or questions you choose to include in your message.</li>
      </ul>
      <p class="mt-2">
        We do not ask for passport numbers, financial account details, or other sensitive identity documents through
        this website. If document collection is required for your case, that happens separately and directly with
        our team, not through the online form.
      </p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">2. How We Use Your Information</h2>
      <ul class="mt-2 list-disc space-y-1 pl-5">
        <li>To respond to your enquiry or application and provide the service you requested.</li>
        <li>To follow up by phone, WhatsApp, or email regarding the status of your request.</li>
        <li>To keep an internal record of applications and communications for quality and continuity of service.</li>
      </ul>
      <p class="mt-2">We do not sell, rent, or trade your personal information to third parties.</p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">3. How We Store Your Information</h2>
      <p class="mt-2">
        Submissions are stored in a private database that only authorized Andson Travel Consult staff can access
        through a password-protected administration area. We retain application and message records for as long as
        reasonably necessary to provide our services and maintain business records, after which they may be deleted.
      </p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">4. Cookies</h2>
      <p class="mt-2">
        This website uses only a minimal session cookie required for basic functionality (such as keeping a form
        submission secure and remembering an administrator&rsquo;s login session). We do not use third-party
        advertising or tracking cookies.
      </p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">5. Your Rights</h2>
      <p class="mt-2">
        You may ask us at any time to tell you what information we hold about you, to correct inaccurate information,
        or to delete your information from our records, subject to any legitimate business or legal reason we may
        need to retain it. To make a request, email us at
        <a href="mailto:<?= e(CONTACT_EMAIL) ?>" class="font-semibold text-brand-700 underline"><?= e(CONTACT_EMAIL) ?></a>.
      </p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">6. Contact Us</h2>
      <p class="mt-2">
        Questions about this policy can be sent to
        <a href="mailto:<?= e(CONTACT_EMAIL) ?>" class="font-semibold text-brand-700 underline"><?= e(CONTACT_EMAIL) ?></a>
        or to our office at <?= e(CONTACT_ADDRESS) ?>.
      </p>
    </div>

  </div>
</section>

<?php require __DIR__ . '/../src/partials/footer.php'; ?>
