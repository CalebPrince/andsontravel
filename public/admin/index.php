<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

$pdo = db();

$totalApplications = (int) $pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn();
$newApplications    = (int) $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'new'")->fetchColumn();
$totalMessages       = (int) $pdo->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
$newMessages          = (int) $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'")->fetchColumn();
$activeServices        = (int) $pdo->query('SELECT COUNT(*) FROM services WHERE is_active = 1')->fetchColumn();
$totalServices          = (int) $pdo->query('SELECT COUNT(*) FROM services')->fetchColumn();
$activeFaqs               = (int) $pdo->query('SELECT COUNT(*) FROM faqs WHERE is_active = 1')->fetchColumn();
$totalFaqs                 = (int) $pdo->query('SELECT COUNT(*) FROM faqs')->fetchColumn();

$recentApplications = $pdo->query('SELECT * FROM applications ORDER BY created_at DESC LIMIT 6')->fetchAll();
$recentMessages      = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 6')->fetchAll();

$socialConfigured = getSetting('facebook_url') !== '' || getSetting('instagram_url') !== '';

$pageTitle = 'Dashboard (Admin)';
$activeAdminNav = 'dashboard';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
  <div>
    <h1 class="font-display text-2xl font-bold text-navy-900">Dashboard</h1>
    <p class="mt-1 text-sm text-navy-900/50">A quick look at what&rsquo;s come in, and quick links to edit the site.</p>
  </div>
  <div class="flex flex-wrap items-center gap-2">
    <a href="/admin/service-form.php" class="btn-outline-navy"><?= icon('plus', 'h-4 w-4') ?> Add Service</a>
    <a href="/admin/faq-form.php" class="btn-outline-navy"><?= icon('plus', 'h-4 w-4') ?> Add FAQ</a>
  </div>
</div>

<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
  <div class="card p-5">
    <p class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">Total Service Bookings</p>
    <p class="mt-2 font-display text-3xl font-extrabold text-navy-900"><?= $totalApplications ?></p>
  </div>
  <div class="card p-5">
    <p class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">New Service Bookings</p>
    <p class="mt-2 font-display text-3xl font-extrabold text-brand-600"><?= $newApplications ?></p>
  </div>
  <div class="card p-5">
    <p class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">Total Enquiries</p>
    <p class="mt-2 font-display text-3xl font-extrabold text-navy-900"><?= $totalMessages ?></p>
  </div>
  <div class="card p-5">
    <p class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">New Enquiries</p>
    <p class="mt-2 font-display text-3xl font-extrabold text-brand-600"><?= $newMessages ?></p>
  </div>
</div>

<div class="mt-5 grid gap-5 sm:grid-cols-3">
  <a href="/admin/services.php" class="card flex items-center justify-between p-5 hover:-translate-y-0.5">
    <div>
      <p class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">Services Live</p>
      <p class="mt-2 font-display text-2xl font-extrabold text-navy-900"><?= $activeServices ?> <span class="text-base font-semibold text-navy-900/40">/ <?= $totalServices ?></span></p>
    </div>
    <?= icon('sparkles', 'h-8 w-8 text-brand-600/30') ?>
  </a>
  <a href="/admin/faqs.php" class="card flex items-center justify-between p-5 hover:-translate-y-0.5">
    <div>
      <p class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">FAQs Live</p>
      <p class="mt-2 font-display text-2xl font-extrabold text-navy-900"><?= $activeFaqs ?> <span class="text-base font-semibold text-navy-900/40">/ <?= $totalFaqs ?></span></p>
    </div>
    <?= icon('document', 'h-8 w-8 text-brand-600/30') ?>
  </a>
  <a href="/admin/social-links.php" class="card flex items-center justify-between p-5 hover:-translate-y-0.5">
    <div>
      <p class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">Social Links</p>
      <p class="mt-2 font-display text-2xl font-extrabold text-navy-900"><?= $socialConfigured ? 'Configured' : 'Not Set' ?></p>
    </div>
    <?= icon('link', 'h-8 w-8 text-brand-600/30') ?>
  </a>
</div>

<div class="mt-10 grid gap-8 lg:grid-cols-2">
  <div>
    <div class="mb-4 flex items-center justify-between">
      <h2 class="font-display text-base font-bold text-navy-900">Recent Service Bookings</h2>
      <a href="/admin/applications.php" class="text-sm font-semibold text-brand-700 hover:text-brand-600">View all</a>
    </div>
    <div class="card divide-y divide-navy-900/5">
      <?php if (!$recentApplications): ?>
        <p class="p-5 text-sm text-navy-900/50">No service bookings yet.</p>
      <?php endif; ?>
      <?php foreach ($recentApplications as $app): ?>
        <a href="/admin/application.php?id=<?= (int) $app['id'] ?>" class="flex items-center justify-between gap-4 p-4 hover:bg-navy-900/[0.02]">
          <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-navy-900"><?= e($app['full_name']) ?></p>
            <p class="truncate text-xs text-navy-900/50"><?= e($app['service_title']) ?> &middot; <?= timeAgo($app['created_at']) ?></p>
          </div>
          <span class="<?= statusBadgeClasses($app['status']) ?> shrink-0"><?= e(statusLabel($app['status'])) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>

  <div>
    <div class="mb-4 flex items-center justify-between">
      <h2 class="font-display text-base font-bold text-navy-900">Recent Enquiries</h2>
      <a href="/admin/messages.php" class="text-sm font-semibold text-brand-700 hover:text-brand-600">View all</a>
    </div>
    <div class="card divide-y divide-navy-900/5">
      <?php if (!$recentMessages): ?>
        <p class="p-5 text-sm text-navy-900/50">No enquiries yet.</p>
      <?php endif; ?>
      <?php foreach ($recentMessages as $msg): ?>
        <a href="/admin/message.php?id=<?= (int) $msg['id'] ?>" class="flex items-center justify-between gap-4 p-4 hover:bg-navy-900/[0.02]">
          <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-navy-900"><?= e($msg['full_name']) ?></p>
            <p class="truncate text-xs text-navy-900/50"><?= e($msg['subject'] ?: 'General enquiry') ?> &middot; <?= timeAgo($msg['created_at']) ?></p>
          </div>
          <span class="<?= statusBadgeClasses($msg['status']) ?> shrink-0"><?= e(statusLabel($msg['status'])) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
