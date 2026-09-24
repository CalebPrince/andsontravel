<?php
require_once __DIR__ . '/../src/bootstrap.php';

$pageTitle = SITE_NAME . ': ' . SITE_TAGLINE;
$pageDescription = 'Expert travel consultancy and visa advisory services for the US, UK, Europe and beyond. Document prep, visa form filling, bookings, airport assistance and more, from Accra, Ghana.';
$activeNav = 'home';

$heroSlides = [
    ['eyebrow' => 'Trusted Travel Advisory', 'title' => 'Expert Travel Consultancy & Advisory Services', 'sub' => 'We provide expert guidance for your travel needs.'],
    ['eyebrow' => 'Hassle-free From Start to Finish', 'title' => 'Your Gateway to Hassle-free Travel', 'sub' => 'From document prep to airport pickup, we handle the details.'],
    ['eyebrow' => 'US · UK · Europe · Beyond', 'title' => 'Comprehensive Visa Assistance & Travel Solutions', 'sub' => 'Personalized support for every step of your journey.'],
];

$allServices = services();
$services = array_slice($allServices, 0, 6, true);
$faqPreview = array_slice(faqs(), 0, 6);

$whyTrustUs = [
    ['icon' => 'globe', 'title' => 'Comprehensive Services', 'desc' => 'Full travel consultancy and advisory support, specializing in preparation for the United States, United Kingdom, and Europe.'],
    ['icon' => 'document', 'title' => 'Expert Assistance', 'desc' => 'Document collection for visa interviews, step-by-step application guidance, and renewal support from people who do this daily.'],
    ['icon' => 'briefcase', 'title' => 'Wide Range of Services', 'desc' => 'Overseas hotel and flight bookings, airport assistance, and tourism packages, with Canada, Australia, China, Japan and Turkey coming soon.'],
    ['icon' => 'users', 'title' => 'Personalized Support', 'desc' => 'One-on-one immigration advice, form filling, fee payment, pick-up services, and counselling for prospective students.'],
    ['icon' => 'shield', 'title' => 'Commitment to Excellence', 'desc' => 'We are not a visa connection agency and never guarantee outcomes: only honest, thorough help completing your application.'],
    ['icon' => 'sparkles', 'title' => 'Prompt Assistance', 'desc' => 'Apply through our online form and a member of our team will follow up promptly, usually within one business day.'],
];

require __DIR__ . '/../src/partials/header.php';
?>

<!-- HERO -->
<section class="relative overflow-hidden bg-navy-900">
  <div class="pointer-events-none absolute inset-0 opacity-70" style="background: radial-gradient(60% 60% at 15% 20%, color-mix(in oklab, var(--color-brand-500) 35%, transparent) 0%, transparent 60%), radial-gradient(50% 50% at 90% 80%, color-mix(in oklab, var(--color-brand-400) 25%, transparent) 0%, transparent 60%);"></div>
  <svg class="pointer-events-none absolute inset-0 h-full w-full opacity-[0.07]" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <pattern id="grid" width="42" height="42" patternUnits="userSpaceOnUse">
        <path d="M42 0H0V42" fill="none" stroke="white" stroke-width="1"/>
      </pattern>
    </defs>
    <rect width="100%" height="100%" fill="url(#grid)"/>
  </svg>

  <div class="relative mx-auto grid max-w-7xl items-center gap-10 px-4 py-12 sm:px-6 sm:py-14 lg:grid-cols-[1.05fr_0.95fr] lg:px-8 lg:py-16">
    <div class="max-w-xl">
      <?php foreach ($heroSlides as $i => $slide): ?>
        <div data-hero-slide class="<?= $i === 0 ? '' : 'hidden' ?>">
          <span class="section-eyebrow bg-white/10 text-brand-400"><?= icon('sparkles', 'h-3.5 w-3.5') ?> <?= e($slide['eyebrow']) ?></span>
          <h1 class="mt-5 font-display text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-5xl">
            <?= e($slide['title']) ?>
          </h1>
          <p class="mt-4 max-w-lg text-base text-white/70 sm:text-lg">
            <?= e($slide['sub']) ?>
          </p>
        </div>
      <?php endforeach; ?>

      <div class="mt-8 flex flex-wrap items-center gap-4">
        <a href="#services" class="btn-primary">
          Explore Our Services
          <?= icon('arrow-right', 'h-4 w-4') ?>
        </a>
        <a href="<?= e(CONTACT_WHATSAPP_URL) ?>" target="_blank" rel="noopener" class="btn-whatsapp">
          <?= icon('whatsapp', 'h-5 w-5 text-white') ?>
          Chat on WhatsApp
        </a>
      </div>

      <dl class="mt-10 grid max-w-lg grid-cols-3 gap-6 border-t border-white/10 pt-6">
        <div>
          <dt class="text-xs font-semibold uppercase tracking-widest text-white/40">Services</dt>
          <dd class="mt-1 font-display text-xl font-bold text-white"><?= count($allServices) ?>+</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold uppercase tracking-widest text-white/40">Destinations</dt>
          <dd class="mt-1 font-display text-xl font-bold text-white">8+</dd>
        </div>
        <div>
          <dt class="text-xs font-semibold uppercase tracking-widest text-white/40">Based In</dt>
          <dd class="mt-1 font-display text-xl font-bold text-white">Accra, GH</dd>
        </div>
      </dl>
    </div>

    <!-- Hero graphic: floating, non-photographic cards -->
    <div class="relative hidden h-[360px] lg:block" aria-hidden="true">
      <div class="absolute left-1/2 top-1/2 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-brand-500/20 blur-3xl"></div>

      <div class="absolute left-1/2 top-1/2 flex h-56 w-56 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full border border-dashed border-white/20">
        <div class="flex h-36 w-36 items-center justify-center rounded-full bg-white/10 text-brand-400 ring-1 ring-white/10 backdrop-blur">
          <?= icon('globe', 'h-16 w-16') ?>
        </div>
      </div>

      <div class="absolute left-2 top-6 flex items-center gap-3 rounded-2xl bg-white px-4 py-3 shadow-xl shadow-navy-950/30 -rotate-6">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600/10 text-brand-700">
          <?= icon('passport', 'h-5 w-5') ?>
        </div>
        <div>
          <p class="text-xs font-semibold text-navy-900">Passport &amp; Visa Forms</p>
          <p class="text-[11px] text-navy-900/50">Reviewed line by line</p>
        </div>
      </div>

      <div class="absolute right-0 top-24 flex items-center gap-2 rounded-full bg-white px-4 py-2.5 shadow-xl shadow-navy-950/30 rotate-3">
        <?= icon('whatsapp', 'h-4 w-4 text-whatsapp-500') ?>
        <span class="text-xs font-bold text-navy-900">Chat with an Agent</span>
      </div>

      <div class="absolute bottom-6 left-8 flex items-center gap-3 rounded-2xl bg-white px-4 py-3 shadow-xl shadow-navy-950/30 rotate-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600/10 text-brand-700">
          <?= icon('plane', 'h-5 w-5') ?>
        </div>
        <div>
          <p class="text-xs font-semibold text-navy-900">Flight &amp; Hotel Booking</p>
          <p class="text-[11px] text-navy-900/50">Handled end to end</p>
        </div>
      </div>

      <div class="absolute bottom-0 right-4 flex items-center gap-2 rounded-full bg-brand-600 px-4 py-2 shadow-xl shadow-brand-600/30">
        <?= icon('check-circle', 'h-4 w-4 text-white') ?>
        <span class="text-xs font-bold text-white">Honest, No-Guarantee Advice</span>
      </div>
    </div>
  </div>
</section>

<!-- ABOUT BLURB -->
<section class="bg-sand-100">
  <div class="mx-auto max-w-5xl px-4 py-16 text-center sm:px-6 lg:px-8">
    <span class="section-eyebrow">Who We Are</span>
    <h2 class="mt-4 font-display text-2xl font-bold text-navy-900 sm:text-3xl">Andson Travel Consultancy &amp; Advisory Services</h2>
    <p class="mx-auto mt-5 max-w-3xl text-base leading-relaxed text-navy-900/70">
      Andson Travel Consult provides comprehensive travel consultancy and advisory services, specializing in travel
      preparation for the United States, United Kingdom, and Europe. Our expert assistance covers document collection
      for visa interviews, step-by-step guidance through visa applications (including form filling) and
      visa renewal support. We also facilitate overseas hotel and flight bookings, airport assistance, and tourism
      packages, with services extending to upcoming destinations like Canada, Australia, China, Japan, and Turkey.
    </p>
    <p class="mx-auto mt-4 max-w-3xl text-sm font-semibold text-navy-900/80">
      We are not a visa connection agency and do not guarantee anyone a visa. We are committed to giving you the best
      possible support in completing your application, honestly and thoroughly.
    </p>
    <a href="/about.php" class="mt-6 inline-flex items-center gap-1.5 text-sm font-bold text-brand-700 hover:text-brand-600">
      More about us <?= icon('chevron-right', 'h-4 w-4') ?>
    </a>
  </div>
</section>

<!-- SERVICES -->
<section id="services" class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
  <div class="mx-auto max-w-2xl text-center">
    <span class="section-eyebrow">What We Do</span>
    <h2 class="mt-4 font-display text-3xl font-bold text-navy-900 sm:text-4xl">Our Assistance Services</h2>
    <p class="mt-4 text-navy-900/60">Apply for any of the services below to make your travel process hassle-free.</p>
  </div>

  <div class="mx-auto mt-6 max-w-3xl rounded-2xl border border-amber-500/20 bg-amber-50 px-5 py-4 text-center text-sm font-medium text-amber-800">
    We are <strong>not</strong> a visa connection agency and do <strong>not</strong> guarantee anyone a visa. We are only
    committed to providing the best support in completing the visa application process.
  </div>

  <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <?php foreach ($services as $slug => $service): ?>
      <div class="card flex flex-col p-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-600/10 text-brand-700">
          <?= icon($service['icon'], 'h-6 w-6') ?>
        </div>
        <h3 class="mt-5 font-display text-lg font-bold text-navy-900"><?= e($service['title']) ?></h3>
        <p class="mt-2 flex-1 text-sm leading-relaxed text-navy-900/60"><?= e($service['summary']) ?></p>
        <a href="/apply.php?service=<?= urlencode($slug) ?>" class="mt-5 inline-flex items-center gap-1.5 text-sm font-bold text-brand-700 hover:text-brand-600">
          Apply Now <?= icon('arrow-right', 'h-4 w-4') ?>
        </a>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="mt-10 text-center">
    <a href="/services.php" class="btn-outline-navy">
      View All Services
      <?= icon('arrow-right', 'h-4 w-4') ?>
    </a>
  </div>
</section>

<!-- WHY TRUST US -->
<section class="bg-white">
  <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-2xl text-center">
      <span class="section-eyebrow">Why Trust Us</span>
      <h2 class="mt-4 font-display text-3xl font-bold text-navy-900 sm:text-4xl">The Trusted Choice for Your Travel Needs</h2>
    </div>

    <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($whyTrustUs as $item): ?>
        <div class="rounded-2xl border border-navy-900/5 bg-sand-100/60 p-6">
          <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-600/10 text-brand-700">
            <?= icon($item['icon'], 'h-5 w-5') ?>
          </div>
          <h3 class="mt-4 font-display text-base font-bold text-navy-900"><?= e($item['title']) ?></h3>
          <p class="mt-2 text-sm leading-relaxed text-navy-900/60"><?= e($item['desc']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FAQ PREVIEW -->
<section class="mx-auto max-w-4xl px-4 py-20 sm:px-6 lg:px-8">
  <div class="text-center">
    <span class="section-eyebrow">General FAQs</span>
    <h2 class="mt-4 font-display text-3xl font-bold text-navy-900 sm:text-4xl">Common Questions</h2>
    <p class="mt-4 text-navy-900/60">Answers to the questions we hear most about visa applications and travel procedures.</p>
    <p class="mt-2 text-xs text-navy-900/40">Source: US Embassy in Ghana Website (<a href="https://ustraveldocs.com/gh/en/general-information#faqs" target="_blank" rel="noopener" class="font-semibold text-brand-700 underline hover:text-brand-600">USTravelDocs</a>)</p>
  </div>

  <div class="mt-10 divide-y divide-navy-900/10 rounded-2xl border border-navy-900/10 bg-white">
    <?php foreach ($faqPreview as $faq): ?>
      <details class="group p-6">
        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-display text-base font-bold text-navy-900">
          <?= e($faq['q']) ?>
          <span class="shrink-0 text-navy-900/40 transition group-open:rotate-180"><?= icon('chevron-down', 'h-5 w-5') ?></span>
        </summary>
        <p class="mt-3 text-sm leading-relaxed text-navy-900/65"><?= e($faq['a']) ?></p>
      </details>
    <?php endforeach; ?>
  </div>

  <div class="mt-8 text-center">
    <a href="/faqs.php" class="btn-outline-navy">
      View All FAQs
      <?= icon('arrow-right', 'h-4 w-4') ?>
    </a>
  </div>
</section>

<!-- CTA -->
<section class="bg-ink-900">
  <div class="mx-auto flex max-w-5xl flex-col items-center gap-6 px-4 py-16 text-center sm:px-6 lg:px-8">
    <h2 class="font-display text-2xl font-bold text-white sm:text-3xl">Need help and assistance with your travel?</h2>
    <div class="flex flex-wrap items-center justify-center gap-4">
      <a href="tel:<?= e(CONTACT_PHONE_TEL) ?>" class="btn bg-brand-600 text-white hover:bg-brand-700">
        <?= icon('phone', 'h-5 w-5') ?> Call <?= e(CONTACT_PHONE_DISPLAY) ?>
      </a>
      <a href="<?= e(CONTACT_WHATSAPP_URL) ?>" target="_blank" rel="noopener" class="btn-whatsapp">
        <?= icon('whatsapp', 'h-5 w-5 text-white') ?> Chat on WhatsApp
      </a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../src/partials/footer.php'; ?>
