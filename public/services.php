<?php
require_once __DIR__ . '/../src/bootstrap.php';

$pageTitle = 'Assistance Services: ' . SITE_NAME;
$pageDescription = 'Every travel and visa assistance service offered by Andson Travel Consult, from visa application help to flight and hotel bookings, airport assistance and student counselling.';
$activeNav = 'services';

$services = services();

require __DIR__ . '/../src/partials/header.php';
?>

<section class="bg-navy-900">
  <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
    <span class="section-eyebrow bg-white/10 text-brand-400">What We Do</span>
    <h1 class="mt-5 font-display text-3xl font-extrabold text-white sm:text-4xl">Assistance Services</h1>
    <p class="mx-auto mt-5 max-w-2xl text-lg text-white/70">
      Every service we offer, in one place. Pick the one that matches your situation and apply directly, our team
      will follow up promptly.
    </p>
  </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
  <div class="mx-auto max-w-3xl rounded-2xl border border-amber-500/20 bg-amber-50 px-5 py-4 text-center text-sm font-medium text-amber-800">
    We are <strong>not</strong> a visa connection agency and do <strong>not</strong> guarantee anyone a visa. We are only
    committed to providing the best support in completing the visa application process.
  </div>

  <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <?php foreach ($services as $slug => $service): ?>
      <div class="card group flex flex-col overflow-hidden">
        <?php if ($service['image']): ?>
          <div class="aspect-[4/3] overflow-hidden bg-sand-100">
            <img src="/assets/images/services/<?= e($service['image']) ?>" alt="" width="1448" height="1086" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
          </div>
        <?php endif; ?>
        <div class="flex flex-1 flex-col p-6">
          <div class="relative flex h-12 w-12 items-center justify-center rounded-xl bg-brand-600/10 text-brand-700 <?= $service['image'] ? '-mt-12 bg-white shadow-lg ring-4 ring-white' : '' ?>">
            <?= icon($service['icon'], 'h-6 w-6') ?>
          </div>
          <h3 class="mt-5 font-display text-lg font-bold text-navy-900"><?= e($service['title']) ?></h3>
          <p class="mt-2 flex-1 text-sm leading-relaxed text-navy-900/60"><?= e($service['summary']) ?></p>
          <a href="/apply.php?service=<?= urlencode($slug) ?>" class="mt-5 inline-flex items-center gap-1.5 text-sm font-bold text-brand-700 hover:text-brand-600">
            Apply Now <?= icon('arrow-right', 'h-4 w-4') ?>
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="bg-ink-900">
  <div class="mx-auto flex max-w-5xl flex-col items-center gap-6 px-4 py-16 text-center sm:px-6 lg:px-8">
    <h2 class="font-display text-2xl font-bold text-white sm:text-3xl">Not sure which service fits your situation?</h2>
    <div class="flex flex-wrap items-center justify-center gap-4">
      <a href="/contact.php" class="btn-primary">Talk to an Agent</a>
      <a href="<?= e(CONTACT_WHATSAPP_URL) ?>" target="_blank" rel="noopener" class="btn-whatsapp">
        <?= icon('whatsapp', 'h-5 w-5 text-white') ?> Chat on WhatsApp
      </a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../src/partials/footer.php'; ?>
