<?php
require_once __DIR__ . '/../src/bootstrap.php';

$pageTitle = 'Terms & Conditions: ' . SITE_NAME;
$pageDescription = 'The terms and conditions that apply when you use Andson Travel Consult\'s website and advisory services.';
$activeNav = '';

require __DIR__ . '/../src/partials/header.php';
?>

<section class="bg-navy-900">
  <div class="mx-auto max-w-3xl px-4 py-14 text-center sm:px-6 lg:px-8">
    <span class="section-eyebrow bg-white/10 text-brand-400">Legal</span>
    <h1 class="mt-5 font-display text-3xl font-extrabold text-white sm:text-4xl">Terms &amp; Conditions</h1>
    <p class="mt-4 text-sm text-white/50">Last updated: <?= date('F Y') ?></p>
  </div>
</section>

<section class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
  <div class="space-y-8 text-sm leading-relaxed text-navy-900/75">

    <p>
      These terms govern your use of this website and of the advisory services offered by Andson Travel Consult
      ("we", "us", "our"). By using this website or applying for a service, you agree to the terms below. This is a
      general terms page for a small consultancy and is not a substitute for tailored legal advice.
    </p>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">1. Who We Are</h2>
      <p class="mt-2">
        Andson Travel Consult is a travel consultancy and advisory service based in Accra, Ghana, providing guidance
        on travel preparation, visa applications, bookings, and related services described on this website.
      </p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">2. Nature of Our Services</h2>
      <p class="mt-2">
        <strong>We are not a visa connection agency, government body, or embassy, and we do not guarantee that any
        applicant will be issued a visa, passport, or other travel document.</strong> Visa, immigration, and travel
        authorization decisions are made solely by the relevant government or embassy, based on their own criteria.
      </p>
      <p class="mt-2">
        Our role is to help you understand requirements, collect the correct documents, complete application forms
        accurately, and prepare for interviews or appointments. Any fees you pay us are for this advisory and
        preparation assistance, not for a guaranteed outcome.
      </p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">3. Government &amp; Third-Party Fees</h2>
      <p class="mt-2">
        Visa, passport, and other official application fees are set and collected by the relevant government or
        embassy, and are separate from any consultancy fee you agree with us. We may assist you with the payment
        process, but we do not control or influence embassy decisions, processing times, or fee amounts.
      </p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">4. Accuracy of Information You Provide</h2>
      <p class="mt-2">
        You are responsible for providing accurate, complete, and truthful information and documents. We are not
        liable for delays, refusals, or other consequences arising from information you provide that is inaccurate,
        incomplete, or misleading.
      </p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">5. Limitation of Liability</h2>
      <p class="mt-2">
        To the fullest extent permitted by law, Andson Travel Consult is not liable for indirect or consequential
        losses, including but not limited to lost bookings, missed travel dates, or visa refusals, except where such
        liability cannot be excluded under applicable law.
      </p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">6. Website Use</h2>
      <p class="mt-2">
        You agree not to misuse this website, including attempting to submit false information through our forms,
        interfere with its normal operation, or attempt to access areas of the site (such as the administration area)
        without authorization.
      </p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">7. Changes to These Terms</h2>
      <p class="mt-2">
        We may update these terms from time to time to reflect changes in our services or applicable law. The
        version published on this page is the version in effect.
      </p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">8. Governing Law</h2>
      <p class="mt-2">These terms are governed by the laws of Ghana.</p>
    </div>

    <div>
      <h2 class="font-display text-lg font-bold text-navy-900">9. Contact Us</h2>
      <p class="mt-2">
        Questions about these terms can be sent to
        <a href="mailto:<?= e(CONTACT_EMAIL) ?>" class="font-semibold text-brand-700 underline"><?= e(CONTACT_EMAIL) ?></a>
        or to our office at <?= e(CONTACT_ADDRESS) ?>.
      </p>
    </div>

  </div>
</section>

<?php require __DIR__ . '/../src/partials/footer.php'; ?>
