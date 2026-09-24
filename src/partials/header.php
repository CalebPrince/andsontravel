<?php
/** @var string $pageTitle */
/** @var string $pageDescription */
/** @var string $activeNav */
$pageTitle ??= SITE_NAME . ': ' . SITE_TAGLINE;
$pageDescription ??= 'Andson Travel Consult provides expert travel consultancy and visa advisory services for the United States, United Kingdom, Europe and beyond, from Accra, Ghana.';
$activeNav ??= '';

$navLinks = [
    'home'     => ['label' => 'Home', 'href' => '/'],
    'about'    => ['label' => 'About Us', 'href' => '/about.php'],
    'services' => ['label' => 'Assistance Services', 'href' => '/services.php'],
    'faqs'     => ['label' => 'FAQs', 'href' => '/faqs.php'],
    'contact'  => ['label' => 'Contact', 'href' => '/contact.php'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?></title>
<meta name="description" content="<?= e($pageDescription) ?>">
<link rel="icon" type="image/jpeg" href="/assets/images/logo-original.jpg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="font-body text-navy-900 antialiased">

<a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full focus:bg-white focus:px-4 focus:py-2 focus:shadow-lg">Skip to content</a>

<header class="sticky top-0 z-50 bg-white shadow-sm">
  <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
    <a href="/" class="relative z-10 -mb-6 inline-flex items-center">
      <img src="/assets/images/logo-original.jpg" alt="<?= e(SITE_NAME) ?>" class="h-16 w-auto rounded-b-2xl object-contain">
    </a>

    <nav class="hidden items-center gap-0.5 lg:flex" aria-label="Primary">
      <?php foreach ($navLinks as $key => $link): ?>
        <a href="<?= e($link['href']) ?>"
           class="whitespace-nowrap rounded-full px-3 py-2 text-sm font-semibold transition <?= $activeNav === $key ? 'bg-navy-900 text-white' : 'text-navy-900/80 hover:bg-navy-900/5 hover:text-navy-900' ?>">
          <?= e($link['label']) ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="hidden items-center gap-3 lg:flex">
      <a href="<?= e(CONTACT_WHATSAPP_URL) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-semibold text-navy-900/70 hover:text-navy-900">
        <?= icon('whatsapp', 'h-5 w-5 text-whatsapp-500') ?>
        <span class="hidden xl:inline"><?= e(CONTACT_PHONE_DISPLAY) ?></span>
      </a>
      <a href="/contact.php" class="btn-primary">
        Get Assistance
        <?= icon('arrow-right', 'h-4 w-4') ?>
      </a>
    </div>

    <button type="button" id="mobile-menu-btn" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-navy-900/10 text-navy-900 lg:hidden" aria-expanded="false" aria-controls="mobile-menu">
      <span class="sr-only">Open menu</span>
      <?= icon('menu', 'h-6 w-6') ?>
    </button>
  </div>
</header>

<!-- Mobile drawer backdrop -->
<div id="mobile-menu-backdrop" class="fixed inset-0 z-[60] hidden bg-navy-950/60 lg:hidden"></div>

<!-- Mobile drawer panel -->
<div id="mobile-menu"
     class="fixed inset-y-0 right-0 z-[70] flex w-full max-w-xs translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-in-out lg:hidden"
     role="dialog" aria-modal="true" aria-label="Mobile menu">
  <div class="flex items-center justify-between border-b border-navy-900/5 px-4 py-4">
    <img src="/assets/images/logo-original.jpg" alt="<?= e(SITE_NAME) ?>" class="h-10 w-auto object-contain">
    <button type="button" id="mobile-menu-close" class="flex h-10 w-10 items-center justify-center rounded-full text-navy-900 hover:bg-navy-900/5">
      <span class="sr-only">Close menu</span>
      <?= icon('close', 'h-5 w-5') ?>
    </button>
  </div>
  <nav class="flex flex-1 flex-col gap-1 overflow-y-auto px-4 py-4" aria-label="Mobile">
    <?php foreach ($navLinks as $key => $link): ?>
      <a href="<?= e($link['href']) ?>" class="rounded-xl px-4 py-3 text-base font-semibold <?= $activeNav === $key ? 'bg-navy-900 text-white' : 'text-navy-900 hover:bg-navy-900/5' ?>">
        <?= e($link['label']) ?>
      </a>
    <?php endforeach; ?>
    <a href="<?= e(CONTACT_WHATSAPP_URL) ?>" target="_blank" rel="noopener" class="mt-2 flex items-center gap-2 rounded-xl px-4 py-3 text-base font-semibold text-navy-900 hover:bg-navy-900/5">
      <?= icon('whatsapp', 'h-5 w-5 text-whatsapp-500') ?> WhatsApp Us
    </a>
    <a href="/contact.php" class="btn-primary mt-2 w-full">Get Assistance</a>
  </nav>
</div>

<main id="main">
