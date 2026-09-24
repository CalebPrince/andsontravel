<?php
require_once __DIR__ . '/../src/bootstrap.php';

http_response_code(404);

$pageTitle = 'Page Not Found: ' . SITE_NAME;
$activeNav = '';

require __DIR__ . '/../src/partials/header.php';
?>

<section class="mx-auto flex max-w-2xl flex-col items-center px-4 py-28 text-center sm:px-6 lg:px-8">
  <span class="font-display text-6xl font-extrabold text-brand-600/20">404</span>
  <h1 class="mt-4 font-display text-2xl font-bold text-navy-900">We couldn&rsquo;t find that page</h1>
  <p class="mt-3 text-navy-900/60">The page you&rsquo;re looking for may have moved. Let&rsquo;s get you back on track.</p>
  <a href="/" class="btn-primary mt-8">Back to Home</a>
</section>

<?php require __DIR__ . '/../src/partials/footer.php'; ?>
