<?php
require_once __DIR__ . '/../src/bootstrap.php';

$pageTitle = 'About Us: ' . SITE_NAME;
$pageDescription = 'Learn about Andson Travel Consult, a travel consultancy and advisory service based in Accra, Ghana, helping clients prepare for travel to the US, UK, Europe and beyond.';
$activeNav = 'about';

$process = [
    ['step' => '01', 'title' => 'Tell us your plans', 'desc' => 'Reach out with your destination and travel goals. We listen first, then map out exactly what you’ll need.'],
    ['step' => '02', 'title' => 'We prepare your documents', 'desc' => 'From passport forms to visa applications, we guide you through every field so nothing gets rejected on a technicality.'],
    ['step' => '03', 'title' => 'We support your appointment', 'desc' => 'Interview prep, fee payment guidance, and pick-up service so the logistics never get in your way.'],
    ['step' => '04', 'title' => 'You travel with confidence', 'desc' => 'Bookings, airport assistance and insurance guidance mean the trip itself is as smooth as the paperwork.'],
];

$values = [
    ['icon' => 'shield', 'title' => 'Honesty over hype', 'desc' => 'We never promise a visa outcome. We promise a correctly and thoroughly prepared application.'],
    ['icon' => 'users', 'title' => 'One client at a time', 'desc' => 'Every case gets one-on-one attention, not a generic template.'],
    ['icon' => 'sparkles', 'title' => 'Prompt, plain-language help', 'desc' => 'We explain the process in everyday language and respond quickly when you have questions.'],
];

require __DIR__ . '/../src/partials/header.php';
?>

<section class="bg-navy-900">
  <div class="mx-auto max-w-5xl px-4 py-16 text-center sm:px-6 lg:px-8">
    <span class="section-eyebrow bg-white/10 text-brand-400">About Andson Travel Consult</span>
    <h1 class="mt-5 font-display text-3xl font-extrabold text-white sm:text-4xl">Beyond The Travel Agent</h1>
    <p class="mx-auto mt-5 max-w-3xl text-lg text-white/70">
      We are a travel consultancy and advisory service based in Accra, Ghana, focused on helping people prepare
      confidently for travel to the United States, United Kingdom, and Europe (with Canada, Australia, China,
      Japan and Turkey next on our roadmap).
    </p>
  </div>
</section>

<section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
  <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
    <div>
      <span class="section-eyebrow">Our Story</span>
      <h2 class="mt-4 font-display text-2xl font-bold text-navy-900 sm:text-3xl">Travel paperwork shouldn&rsquo;t be the hardest part of the trip</h2>
      <p class="mt-5 text-base leading-relaxed text-navy-900/70">
        Andson Travel Consult exists because visa applications, passport forms, and travel logistics are confusing
        by design: full of fine print, shifting requirements, and easy places to make a costly mistake. We sit
        with clients through document collection, form filling, fee payment, and appointment prep so the process
        stops feeling like a maze.
      </p>
      <p class="mt-4 text-base leading-relaxed text-navy-900/70">
        We also help with the parts that come after the interview: overseas hotel and flight bookings, airport
        assistance, tourism packages, and counselling for prospective students heading abroad to study.
      </p>
    </div>
    <div class="rounded-2xl border border-amber-500/20 bg-amber-50 p-6">
      <h3 class="font-display text-sm font-bold uppercase tracking-widest text-amber-800">Important to know</h3>
      <p class="mt-3 text-sm leading-relaxed text-amber-900/80">
        We are <strong>not</strong> a visa connection agency, and we do <strong>not</strong> guarantee anyone a visa.
        No legitimate consultancy can. What we guarantee is diligence: complete documentation, accurate forms, and
        honest advice about your chances before you spend a single fee.
      </p>
    </div>
  </div>
</section>

<section class="bg-sand-100">
  <div class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-2xl text-center">
      <span class="section-eyebrow">How It Works</span>
      <h2 class="mt-4 font-display text-3xl font-bold text-navy-900">From First Message to Boarding Gate</h2>
    </div>
    <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($process as $item): ?>
        <div class="relative rounded-2xl border border-navy-900/5 bg-white p-6">
          <span class="font-display text-3xl font-extrabold text-brand-600/20"><?= e($item['step']) ?></span>
          <h3 class="mt-3 font-display text-base font-bold text-navy-900"><?= e($item['title']) ?></h3>
          <p class="mt-2 text-sm leading-relaxed text-navy-900/60"><?= e($item['desc']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
  <div class="mx-auto max-w-2xl text-center">
    <span class="section-eyebrow">What We Stand For</span>
    <h2 class="mt-4 font-display text-3xl font-bold text-navy-900">Our Values</h2>
  </div>
  <div class="mt-12 grid gap-8 sm:grid-cols-3">
    <?php foreach ($values as $value): ?>
      <div class="text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-600/10 text-brand-700">
          <?= icon($value['icon'], 'h-7 w-7') ?>
        </div>
        <h3 class="mt-4 font-display text-base font-bold text-navy-900"><?= e($value['title']) ?></h3>
        <p class="mt-2 text-sm leading-relaxed text-navy-900/60"><?= e($value['desc']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="bg-ink-900">
  <div class="mx-auto flex max-w-5xl flex-col items-center gap-6 px-4 py-16 text-center sm:px-6 lg:px-8">
    <h2 class="font-display text-2xl font-bold text-white sm:text-3xl">Ready to start your application the right way?</h2>
    <div class="flex flex-wrap items-center justify-center gap-4">
      <a href="/#services" class="btn bg-brand-600 text-white hover:bg-brand-700">Browse Our Services</a>
      <a href="/contact.php" class="btn-outline">Talk to an Agent</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../src/partials/footer.php'; ?>
