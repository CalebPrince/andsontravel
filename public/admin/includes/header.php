<?php
/** @var string $pageTitle */
/** @var string $activeAdminNav */
$pageTitle ??= 'Admin: ' . SITE_NAME;
$activeAdminNav ??= '';

$adminNav = [
    'dashboard'    => ['label' => 'Dashboard', 'href' => '/admin/index.php', 'icon' => 'grid'],
    'applications' => ['label' => 'Service Bookings', 'href' => '/admin/applications.php', 'icon' => 'briefcase'],
    'messages'     => ['label' => 'Enquiries', 'href' => '/admin/messages.php', 'icon' => 'inbox'],
    'services'     => ['label' => 'Services', 'href' => '/admin/services.php', 'icon' => 'sparkles'],
    'faqs'         => ['label' => 'FAQs', 'href' => '/admin/faqs.php', 'icon' => 'document'],
    'social'       => ['label' => 'Social Links', 'href' => '/admin/social-links.php', 'icon' => 'link'],
];

if (isSuperAdmin()) {
    $adminNav['users'] = ['label' => 'Users', 'href' => '/admin/users.php', 'icon' => 'users'];
}

$adminNav['settings'] = ['label' => 'Settings', 'href' => '/admin/settings.php', 'icon' => 'lock'];

$unreadNotifCount = countUnreadNotifications();
$notifications = getUnreadNotifications();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title><?= e($pageTitle) ?></title>
<link rel="icon" type="image/jpeg" href="/assets/images/logo-original.jpg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="font-body text-navy-900 antialiased bg-sand-50">

<div class="flex min-h-screen">
  <aside class="hidden w-64 shrink-0 flex-col bg-navy-950 text-white lg:flex">
    <a href="/admin/index.php" class="flex items-center gap-3 px-6 py-6">
      <span class="inline-flex items-center rounded-lg bg-white px-2.5 py-1.5">
        <img src="/assets/images/logo-original.jpg" alt="<?= e(SITE_NAME) ?>" class="h-7 w-auto object-contain">
      </span>
      <span class="text-[10px] font-semibold uppercase tracking-widest text-brand-400">Admin</span>
    </a>
    <nav class="flex-1 space-y-1 px-3">
      <?php foreach ($adminNav as $key => $link): ?>
        <a href="<?= e($link['href']) ?>" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition <?= $activeAdminNav === $key ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' ?>">
          <?= icon($link['icon'], 'h-5 w-5') ?>
          <?= e($link['label']) ?>
        </a>
      <?php endforeach; ?>
    </nav>
    <div class="border-t border-white/10 px-3 py-4">
      <a href="/" target="_blank" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-white/60 hover:bg-white/5 hover:text-white">
        <?= icon('globe', 'h-5 w-5') ?> View Site
      </a>
      <a href="/admin/logout.php" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-white/60 hover:bg-white/5 hover:text-white">
        <?= icon('logout', 'h-5 w-5') ?> Log Out
      </a>
    </div>
  </aside>

  <!-- Mobile admin drawer -->
  <div id="mobile-menu-backdrop" class="fixed inset-0 z-[60] hidden bg-navy-950/70 backdrop-blur-sm lg:hidden"></div>
  <aside id="mobile-menu"
         class="fixed inset-y-0 right-0 z-[70] flex w-[min(88vw,22rem)] translate-x-full flex-col bg-navy-950 text-white shadow-2xl transition-transform duration-300 ease-out lg:hidden"
         role="dialog" aria-modal="true" aria-label="Admin navigation">
    <div class="flex items-center justify-between border-b border-white/10 px-5 py-5">
      <a href="/admin/index.php" class="flex items-center gap-3">
        <span class="inline-flex items-center rounded-lg bg-white px-2.5 py-1.5">
          <img src="/assets/images/logo-original.jpg" alt="<?= e(SITE_NAME) ?>" class="h-7 w-auto object-contain">
        </span>
        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-brand-400">Admin</span>
      </a>
      <button type="button" id="mobile-menu-close" class="flex h-10 w-10 items-center justify-center rounded-full text-white/70 transition hover:bg-white/10 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400">
        <span class="sr-only">Close navigation</span>
        <?= icon('close', 'h-6 w-6') ?>
      </button>
    </div>

    <div class="border-b border-white/10 px-5 py-4">
      <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/35">Signed in as</p>
      <div class="mt-1 flex items-center gap-2">
        <span class="text-sm font-semibold text-white"><?= e(currentAdminUsername()) ?></span>
        <span class="rounded-full bg-brand-400/15 px-2.5 py-1 text-[10px] font-bold text-brand-400"><?= e(adminRoleLabel(currentAdminRole())) ?></span>
      </div>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4" aria-label="Admin mobile navigation">
      <?php foreach ($adminNav as $key => $link): ?>
        <a href="<?= e($link['href']) ?>" <?= $activeAdminNav === $key ? 'aria-current="page"' : '' ?> class="group flex items-center gap-3 rounded-xl border-l-2 px-4 py-3 text-sm font-semibold transition <?= $activeAdminNav === $key ? 'border-brand-400 bg-white/10 text-white' : 'border-transparent text-white/60 hover:bg-white/5 hover:text-white' ?>">
          <span class="<?= $activeAdminNav === $key ? 'text-brand-400' : 'text-white/40 group-hover:text-white/70' ?>"><?= icon($link['icon'], 'h-5 w-5') ?></span>
          <?= e($link['label']) ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="border-t border-white/10 px-3 py-4">
      <a href="/" target="_blank" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white/60 transition hover:bg-white/5 hover:text-white">
        <?= icon('globe', 'h-5 w-5') ?> View Site
      </a>
      <a href="/admin/logout.php" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white/60 transition hover:bg-white/5 hover:text-white">
        <?= icon('logout', 'h-5 w-5') ?> Log Out
      </a>
    </div>
  </aside>

  <div class="flex min-w-0 flex-1 flex-col">
    <header class="flex items-center justify-between gap-4 border-b border-navy-900/5 bg-white px-4 py-4 sm:px-6 lg:px-8">
      <div class="flex min-w-0 items-center gap-3 lg:hidden">
        <button type="button" id="mobile-menu-btn" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-navy-900/10 text-navy-900 transition hover:bg-navy-900/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500" aria-expanded="false" aria-controls="mobile-menu">
          <span class="sr-only">Open admin navigation</span>
          <?= icon('menu', 'h-5 w-5') ?>
        </button>
        <div class="min-w-0">
          <p class="text-[9px] font-bold uppercase tracking-[0.18em] text-brand-700">Admin</p>
          <p class="truncate text-sm font-bold text-navy-900"><?= e($adminNav[$activeAdminNav]['label'] ?? 'Dashboard') ?></p>
        </div>
      </div>
      <div class="ml-auto flex items-center gap-3 text-sm">
        <div class="relative">
          <button type="button" id="notif-bell-btn" class="relative flex h-10 w-10 items-center justify-center rounded-full text-navy-900/60 transition hover:bg-navy-900/5 hover:text-navy-900" aria-expanded="false" aria-controls="notif-dropdown">
            <span class="sr-only">Notifications</span>
            <?= icon('bell', 'h-5 w-5') ?>
            <span id="notif-count" data-count="<?= $unreadNotifCount ?>" class="absolute right-0.5 top-0.5 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold leading-none text-white <?= $unreadNotifCount > 0 ? '' : 'hidden' ?>">
              <?= $unreadNotifCount > 9 ? '9+' : $unreadNotifCount ?>
            </span>
          </button>

          <div id="notif-dropdown" data-csrf="<?= e(csrfToken()) ?>" class="hidden absolute right-0 z-50 mt-2 w-80 max-w-[90vw] overflow-hidden rounded-2xl border border-navy-900/10 bg-white text-left shadow-xl">
            <div class="flex items-center justify-between border-b border-navy-900/5 px-4 py-3">
              <h3 class="font-display text-sm font-bold text-navy-900">Notifications</h3>
              <form id="notif-mark-all" method="post" action="/admin/notifications.php" class="<?= $unreadNotifCount > 0 ? '' : 'hidden' ?>">
                <?= csrfField() ?>
                <input type="hidden" name="action" value="mark_all_read">
                <button type="submit" class="text-xs font-semibold text-brand-700 hover:text-brand-600">Mark all as read</button>
              </form>
            </div>
            <div id="notif-list" class="max-h-80 overflow-y-auto">
              <p id="notif-empty" class="px-4 py-8 text-center text-sm text-navy-900/40 <?= $notifications ? 'hidden' : '' ?>">You&rsquo;re all caught up.</p>
              <?php foreach ($notifications as $notif): ?>
                <a href="<?= e($notif['url']) ?>" data-notif-type="<?= e($notif['type']) ?>" data-notif-id="<?= $notif['id'] ?>" class="flex items-start gap-3 border-b border-navy-900/5 px-4 py-3 last:border-0 hover:bg-navy-900/[0.02]">
                  <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-brand-600"></span>
                  <span class="min-w-0">
                    <span class="block truncate text-sm font-semibold text-navy-900"><?= e($notif['title']) ?></span>
                    <span class="block truncate text-xs text-navy-900/50"><?= e($notif['subtitle']) ?> &middot; <?= timeAgo($notif['created_at']) ?></span>
                  </span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <span class="hidden text-navy-900/50 lg:inline">Signed in as</span>
        <span class="hidden font-semibold sm:inline"><?= e(currentAdminUsername()) ?></span>
        <span class="hidden badge bg-brand-600/10 text-brand-700 md:inline-flex"><?= e(adminRoleLabel(currentAdminRole())) ?></span>
      </div>
    </header>

    <main class="flex-1 px-4 py-8 sm:px-6 lg:px-8">
