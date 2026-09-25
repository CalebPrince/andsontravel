<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck() || isBotSubmission(false)) {
        flash('error', 'Your session expired. Please try again.');
        redirect('/admin/faqs.php');
    }

    $action = (string) ($_POST['action'] ?? '');
    $id = (int) ($_POST['id'] ?? 0);
    $faq = getFaqById($id);

    if (!$faq) {
        flash('error', 'That FAQ could not be found.');
        redirect('/admin/faqs.php');
    }

    if ($action === 'toggle_active') {
        updateFaq($id, $faq['q'], $faq['a'], $faq['sort_order'], !$faq['is_active']);
        flash('success', 'FAQ ' . (!$faq['is_active'] ? 'activated' : 'deactivated') . '.');
    }

    if ($action === 'delete') {
        deleteFaq($id);
        flash('success', 'FAQ deleted.');
    }

    redirect('/admin/faqs.php');
}

$allFaqs = faqs(false);

$pageTitle = 'FAQs (Admin)';
$activeAdminNav = 'faqs';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
  <div>
    <h1 class="font-display text-2xl font-bold text-navy-900">FAQs</h1>
    <p class="mt-1 text-sm text-navy-900/50">Shown on the homepage preview and the full FAQs page. <?= count($allFaqs) ?> total.</p>
  </div>
  <a href="/admin/faq-form.php" class="btn-primary"><?= icon('plus', 'h-4 w-4') ?> Add FAQ</a>
</div>

<div class="card divide-y divide-navy-900/5">
  <?php if (!$allFaqs): ?>
    <p class="p-5 text-sm text-navy-900/40">No FAQs yet.</p>
  <?php endif; ?>
  <?php foreach ($allFaqs as $faq): ?>
    <div class="flex items-start justify-between gap-4 p-5">
      <div class="min-w-0">
        <div class="flex items-center gap-2">
          <span class="badge <?= $faq['is_active'] ? 'bg-emerald-500/10 text-emerald-700' : 'bg-slate-400/10 text-slate-600' ?>">
            <?= $faq['is_active'] ? 'Active' : 'Inactive' ?>
          </span>
          <span class="text-xs text-navy-900/40">Order <?= $faq['sort_order'] ?></span>
        </div>
        <p class="mt-2 font-semibold text-navy-900"><?= e($faq['q']) ?></p>
        <p class="mt-1 line-clamp-2 text-sm text-navy-900/50"><?= e($faq['a']) ?></p>
      </div>
      <div class="flex shrink-0 items-center gap-1.5">
        <a href="/admin/faq-form.php?id=<?= $faq['id'] ?>" class="flex h-8 w-8 items-center justify-center rounded-lg text-navy-900/40 hover:bg-navy-900/5 hover:text-navy-900" title="Edit">
          <?= icon('pencil', 'h-4 w-4') ?>
        </a>
        <form method="post" action="/admin/faqs.php">
          <?= csrfField() ?>
          <?= botTrapFields(false) ?>
          <input type="hidden" name="action" value="toggle_active">
          <input type="hidden" name="id" value="<?= $faq['id'] ?>">
          <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg text-navy-900/40 hover:bg-navy-900/5 hover:text-navy-900" title="<?= $faq['is_active'] ? 'Deactivate' : 'Activate' ?>">
            <?= icon($faq['is_active'] ? 'toggle-on' : 'toggle-off', 'h-5 w-5') ?>
          </button>
        </form>
        <form method="post" action="/admin/faqs.php" onsubmit="return confirm('Delete this FAQ? This cannot be undone.');">
          <?= csrfField() ?>
          <?= botTrapFields(false) ?>
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= $faq['id'] ?>">
          <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg text-red-500/60 hover:bg-red-50 hover:text-red-700" title="Delete">
            <?= icon('trash', 'h-4 w-4') ?>
          </button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
