<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$existing = $id ? getServiceById($id) : null;

if ($id && !$existing) {
    flash('error', 'That service could not be found.');
    redirect('/admin/services.php');
}

$errors = [];
$values = [
    'title'      => $existing['title'] ?? '',
    'slug'       => $existing['slug'] ?? '',
    'summary'    => $existing['summary'] ?? '',
    'icon'       => $existing['icon'] ?? 'sparkles',
    'sort_order' => $existing['sort_order'] ?? 0,
    'is_active'  => $existing['is_active'] ?? true,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $values['title']      = trim((string) ($_POST['title'] ?? ''));
        $values['slug']       = slugify((string) ($_POST['slug'] ?? '') ?: $values['title']);
        $values['summary']    = trim((string) ($_POST['summary'] ?? ''));
        $values['icon']       = (string) ($_POST['icon'] ?? 'sparkles');
        $values['sort_order'] = (int) ($_POST['sort_order'] ?? 0);
        $values['is_active']  = isset($_POST['is_active']);

        if ($values['title'] === '' || mb_strlen($values['title']) > 150) {
            $errors[] = 'Please enter a title (up to 150 characters).';
        }
        if ($values['slug'] === '') {
            $errors[] = 'Please enter a title that can produce a URL slug (letters or numbers).';
        }
        if ($values['summary'] === '' || mb_strlen($values['summary']) > 300) {
            $errors[] = 'Please enter a one-line summary (up to 300 characters).';
        }
        if (!in_array($values['icon'], availableServiceIcons(), true)) {
            $errors[] = 'Please choose an icon.';
        }
        if (slugExists($values['slug'], $id)) {
            $errors[] = 'That slug is already used by another service. Try a different title or edit the slug.';
        }

        if (!$errors) {
            if ($id) {
                updateService($id, $values['slug'], $values['title'], $values['summary'], $values['icon'], $values['sort_order'], $values['is_active']);
                flash('success', 'Service updated.');
            } else {
                createService($values['slug'], $values['title'], $values['summary'], $values['icon'], $values['sort_order'], $values['is_active']);
                flash('success', 'Service created.');
            }
            redirect('/admin/services.php');
        }
    }
}

$pageTitle = ($id ? 'Edit Service' : 'Add Service') . ' (Admin)';
$activeAdminNav = 'services';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<a href="/admin/services.php" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy-900/50 hover:text-navy-900">
  &larr; Back to Services
</a>

<div class="mt-4 max-w-2xl">
  <h1 class="font-display text-2xl font-bold text-navy-900"><?= $id ? 'Edit Service' : 'Add Service' ?></h1>

  <?php if ($errors): ?>
    <div class="mt-4 rounded-xl border border-red-500/30 bg-red-50 p-4 text-sm text-red-800">
      <ul class="list-inside list-disc space-y-1">
        <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= $id ? '/admin/service-form.php?id=' . $id : '/admin/service-form.php' ?>" class="card mt-4 space-y-5 p-6 sm:p-8">
    <?= csrfField() ?>

    <div>
      <label for="title" class="field-label">Title</label>
      <input type="text" id="title" name="title" required maxlength="150" class="field-input" value="<?= e($values['title']) ?>" placeholder="e.g. Visa Application Assistance">
    </div>

    <div>
      <label for="slug" class="field-label">URL slug <span class="font-normal text-navy-900/40">(used in /apply.php?service=&hellip;)</span></label>
      <input type="text" id="slug" name="slug" maxlength="150" class="field-input font-mono text-sm" value="<?= e($values['slug']) ?>" placeholder="auto-generated from the title if left blank">
    </div>

    <div>
      <label for="summary" class="field-label">One-line summary</label>
      <textarea id="summary" name="summary" required maxlength="300" rows="2" class="field-input resize-none" placeholder="Shown on the service card"><?= e($values['summary']) ?></textarea>
    </div>

    <div>
      <span class="field-label">Icon</span>
      <div class="grid grid-cols-5 gap-2 sm:grid-cols-7">
        <?php foreach (availableServiceIcons() as $iconKey): ?>
          <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-navy-900/10 py-3 text-navy-900/50 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-600/10 has-[:checked]:text-brand-700">
            <input type="radio" name="icon" value="<?= e($iconKey) ?>" class="sr-only" <?= $values['icon'] === $iconKey ? 'checked' : '' ?>>
            <?= icon($iconKey, 'h-5 w-5') ?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
      <div>
        <label for="sort_order" class="field-label">Sort order <span class="font-normal text-navy-900/40">(lower shows first)</span></label>
        <input type="number" id="sort_order" name="sort_order" class="field-input" value="<?= (int) $values['sort_order'] ?>">
      </div>
      <div class="flex items-end pb-3">
        <label class="inline-flex items-center gap-2 text-sm font-semibold text-navy-900">
          <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-navy-900/20 text-brand-600 focus:ring-brand-500" <?= $values['is_active'] ? 'checked' : '' ?>>
          Visible on the site
        </label>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <button type="submit" class="btn-primary"><?= $id ? 'Save Changes' : 'Create Service' ?></button>
      <a href="/admin/services.php" class="btn-outline-navy">Cancel</a>
    </div>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
