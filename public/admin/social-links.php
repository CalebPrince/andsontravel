<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

$errors = [];
$values = [
    'facebook_url'  => getSetting('facebook_url'),
    'instagram_url' => getSetting('instagram_url'),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $values['facebook_url']  = trim((string) ($_POST['facebook_url'] ?? ''));
        $values['instagram_url'] = trim((string) ($_POST['instagram_url'] ?? ''));

        foreach (['facebook_url', 'instagram_url'] as $field) {
            if ($values[$field] !== '' && !filter_var($values[$field], FILTER_VALIDATE_URL)) {
                $errors[] = 'Please enter a valid URL for ' . str_replace('_url', '', $field) . ' (starting with https://), or leave it blank.';
            }
        }

        if (!$errors) {
            setSetting('facebook_url', $values['facebook_url']);
            setSetting('instagram_url', $values['instagram_url']);
            flash('success', 'Social links updated.');
            redirect('/admin/social-links.php');
        }
    }
}

$pageTitle = 'Social Links (Admin)';
$activeAdminNav = 'social';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<div class="mb-8">
  <h1 class="font-display text-2xl font-bold text-navy-900">Social Links</h1>
  <p class="mt-1 text-sm text-navy-900/50">Shown as icons in the site footer. Leave a field blank to hide that icon.</p>
</div>

<div class="max-w-lg">
  <div class="card p-6 sm:p-8">
    <?php if ($errors): ?>
      <div class="mb-4 rounded-xl border border-red-500/30 bg-red-50 p-4 text-sm text-red-800">
        <ul class="list-inside list-disc space-y-1">
          <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="/admin/social-links.php" class="space-y-5">
      <?= csrfField() ?>

      <div>
        <label for="facebook_url" class="field-label flex items-center gap-1.5"><?= icon('facebook', 'h-4 w-4 text-navy-900/40') ?> Facebook Page URL</label>
        <input type="url" id="facebook_url" name="facebook_url" class="field-input" value="<?= e($values['facebook_url']) ?>" placeholder="https://facebook.com/andsontravelconsult">
      </div>

      <div>
        <label for="instagram_url" class="field-label flex items-center gap-1.5"><?= icon('instagram', 'h-4 w-4 text-navy-900/40') ?> Instagram Profile URL</label>
        <input type="url" id="instagram_url" name="instagram_url" class="field-input" value="<?= e($values['instagram_url']) ?>" placeholder="https://instagram.com/andsontravelconsult">
      </div>

      <button type="submit" class="btn-primary w-full">Save Social Links</button>
    </form>
  </div>

  <p class="mt-4 text-center text-xs text-navy-900/40">
    WhatsApp, phone and email always show and are set in <span class="font-mono">src/config.php</span>.
  </p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
