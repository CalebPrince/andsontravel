<?php
require_once __DIR__ . '/../../src/bootstrap.php';

if (isAdminLoggedIn()) {
    redirect('/admin/index.php');
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) {
        $error = 'Your session expired. Please try again.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        // Reject automated submissions (honeypot field) without revealing why.
        if (!isBotSubmission(false) && attemptAdminLogin($username, $password)) {
            redirect('/admin/index.php');
        }

        usleep(400000); // slow down brute-force guessing
        $error = 'Incorrect username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Admin Login: <?= e(SITE_NAME) ?></title>
<link rel="icon" type="image/jpeg" href="/assets/images/logo-original.jpg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="flex min-h-screen items-center justify-center bg-navy-950 px-4 font-body">

<div class="w-full max-w-sm">
  <div class="mb-8 flex flex-col items-center text-center">
    <span class="inline-flex items-center rounded-xl bg-white px-4 py-3 shadow-lg">
      <img src="/assets/images/logo-original.jpg" alt="<?= e(SITE_NAME) ?>" class="h-10 w-auto object-contain">
    </span>
    <h1 class="mt-4 font-display text-lg font-bold text-white">Admin Sign In</h1>
    <p class="mt-1 text-sm text-white/50"><?= e(SITE_NAME) ?></p>
  </div>

  <form method="post" action="/admin/login.php" class="rounded-2xl bg-white p-6 shadow-xl">
    <?= csrfField() ?>
    <?= botTrapFields(false) ?>

    <?php if ($error): ?>
      <div class="mb-4 rounded-xl border border-red-500/30 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
        <?= e($error) ?>
      </div>
    <?php endif; ?>

    <div class="mb-4">
      <label for="username" class="field-label">Username</label>
      <input type="text" id="username" name="username" required autofocus class="field-input" value="<?= e($_POST['username'] ?? '') ?>">
    </div>
    <div class="mb-6">
      <label for="password" class="field-label">Password</label>
      <input type="password" id="password" name="password" required class="field-input">
    </div>

    <button type="submit" class="btn-primary w-full">Sign In</button>
  </form>

  <p class="mt-6 text-center text-xs text-white/30">
    <a href="/" class="hover:text-white/60">&larr; Back to website</a>
  </p>
</div>

</body>
</html>
