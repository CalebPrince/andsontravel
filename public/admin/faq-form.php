<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$existing = $id ? getFaqById($id) : null;

if ($id && !$existing) {
    flash('error', 'That FAQ could not be found.');
    redirect('/admin/faqs.php');
}

$errors = [];
$values = [
    'q'          => $existing['q'] ?? '',
    'a'          => $existing['a'] ?? '',
    'sort_order' => $existing['sort_order'] ?? 0,
    'is_active'  => $existing['is_active'] ?? true,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $values['q']          = trim((string) ($_POST['q'] ?? ''));
        $values['a']          = trim((string) ($_POST['a'] ?? ''));
        $values['sort_order'] = (int) ($_POST['sort_order'] ?? 0);
        $values['is_active']  = isset($_POST['is_active']);

        if ($values['q'] === '' || mb_strlen($values['q']) > 300) {
            $errors[] = 'Please enter a question (up to 300 characters).';
        }
        if ($values['a'] === '' || mb_strlen($values['a']) > 2000) {
            $errors[] = 'Please enter an answer (up to 2000 characters).';
        }

        if (!$errors) {
            if ($id) {
                updateFaq($id, $values['q'], $values['a'], $values['sort_order'], $values['is_active']);
                flash('success', 'FAQ updated.');
            } else {
                createFaq($values['q'], $values['a'], $values['sort_order'], $values['is_active']);
                flash('success', 'FAQ created.');
            }
            redirect('/admin/faqs.php');
        }
    }
}

$pageTitle = ($id ? 'Edit FAQ' : 'Add FAQ') . ' (Admin)';
$activeAdminNav = 'faqs';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<a href="/admin/faqs.php" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy-900/50 hover:text-navy-900">
  &larr; Back to FAQs
</a>

<div class="mt-4 max-w-2xl">
  <h1 class="font-display text-2xl font-bold text-navy-900"><?= $id ? 'Edit FAQ' : 'Add FAQ' ?></h1>

  <?php if ($errors): ?>
    <div class="mt-4 rounded-xl border border-red-500/30 bg-red-50 p-4 text-sm text-red-800">
      <ul class="list-inside list-disc space-y-1">
        <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= $id ? '/admin/faq-form.php?id=' . $id : '/admin/faq-form.php' ?>" class="card mt-4 space-y-5 p-6 sm:p-8">
    <?= csrfField() ?>

    <div>
      <label for="q" class="field-label">Question</label>
      <input type="text" id="q" name="q" required maxlength="300" class="field-input" value="<?= e($values['q']) ?>" placeholder="e.g. How long does my passport have to be valid?">
    </div>

    <div>
      <label for="a" class="field-label">Answer</label>
      <textarea id="a" name="a" required maxlength="2000" rows="6" class="field-input resize-none" placeholder="Write the answer in plain, reassuring language."><?= e($values['a']) ?></textarea>
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
      <button type="submit" class="btn-primary"><?= $id ? 'Save Changes' : 'Create FAQ' ?></button>
      <a href="/admin/faqs.php" class="btn-outline-navy">Cancel</a>
    </div>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
