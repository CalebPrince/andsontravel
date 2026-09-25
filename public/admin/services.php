<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck() || isBotSubmission(false)) {
        flash('error', 'Your session expired. Please try again.');
        redirect('/admin/services.php');
    }

    $action = (string) ($_POST['action'] ?? '');
    $id = (int) ($_POST['id'] ?? 0);
    $service = getServiceById($id);

    if (!$service) {
        flash('error', 'That service could not be found.');
        redirect('/admin/services.php');
    }

    if ($action === 'toggle_active') {
        updateService($id, $_POST['slug'] ?? '', $service['title'], $service['summary'], $service['icon'], $service['image'], $service['sort_order'], !$service['is_active']);
        flash('success', 'Service ' . (!$service['is_active'] ? 'activated' : 'deactivated') . '.');
    }

    if ($action === 'delete') {
        deleteService($id);
        flash('success', 'Service deleted.');
    }

    redirect('/admin/services.php');
}

$allServices = services(false);

$pageTitle = 'Services (Admin)';
$activeAdminNav = 'services';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
  <div>
    <h1 class="font-display text-2xl font-bold text-navy-900">Services</h1>
    <p class="mt-1 text-sm text-navy-900/50">Shown on the homepage, the Assistance Services page, and each Apply form. <?= count($allServices) ?> total.</p>
  </div>
  <a href="/admin/service-form.php" class="btn-primary"><?= icon('plus', 'h-4 w-4') ?> Add Service</a>
</div>

<div class="card overflow-x-auto">
  <table class="w-full min-w-[640px] table-fixed text-left text-sm">
    <thead>
      <tr class="border-b border-navy-900/5 text-xs font-semibold uppercase tracking-wider text-navy-900/40">
        <th class="w-[40%] px-5 py-2.5">Service</th>
        <th class="hidden w-[20%] px-5 py-2.5 sm:table-cell">Slug</th>
        <th class="w-[10%] px-5 py-2.5">Order</th>
        <th class="w-[12%] px-5 py-2.5">Status</th>
        <th class="w-[18%] px-5 py-2.5 text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-navy-900/5">
      <?php if (!$allServices): ?>
        <tr><td colspan="5" class="px-5 py-10 text-center text-navy-900/40">No services yet.</td></tr>
      <?php endif; ?>
      <?php foreach ($allServices as $slug => $service): ?>
        <tr class="hover:bg-navy-900/[0.02]">
          <td class="px-5 py-2">
            <div class="flex items-center gap-2.5">
              <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-600/10 text-brand-700">
                <?= icon($service['icon'], 'h-4 w-4') ?>
              </div>
              <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-navy-900"><?= e($service['title']) ?></p>
                <p class="truncate text-xs text-navy-900/40"><?= e($service['summary']) ?></p>
              </div>
            </div>
          </td>
          <td class="hidden truncate px-5 py-2 font-mono text-xs text-navy-900/50 sm:table-cell"><?= e($slug) ?></td>
          <td class="px-5 py-2 text-navy-900/60"><?= $service['sort_order'] ?></td>
          <td class="px-5 py-2">
            <span class="badge <?= $service['is_active'] ? 'bg-emerald-500/10 text-emerald-700' : 'bg-slate-400/10 text-slate-600' ?>">
              <?= $service['is_active'] ? 'Active' : 'Inactive' ?>
            </span>
          </td>
          <td class="px-5 py-2">
            <div class="flex items-center justify-end gap-1">
              <a href="/apply.php?service=<?= urlencode($slug) ?>" target="_blank" class="flex h-7 w-7 items-center justify-center rounded-lg text-navy-900/40 hover:bg-navy-900/5 hover:text-navy-900" title="View apply page">
                <?= icon('link', 'h-3.5 w-3.5') ?>
              </a>
              <a href="/admin/service-form.php?id=<?= $service['id'] ?>" class="flex h-7 w-7 items-center justify-center rounded-lg text-navy-900/40 hover:bg-navy-900/5 hover:text-navy-900" title="Edit">
                <?= icon('pencil', 'h-3.5 w-3.5') ?>
              </a>
              <form method="post" action="/admin/services.php">
                <?= csrfField() ?>
                <?= botTrapFields(false) ?>
                <input type="hidden" name="action" value="toggle_active">
                <input type="hidden" name="id" value="<?= $service['id'] ?>">
                <input type="hidden" name="slug" value="<?= e($slug) ?>">
                <button type="submit" class="flex h-7 w-7 items-center justify-center rounded-lg text-navy-900/40 hover:bg-navy-900/5 hover:text-navy-900" title="<?= $service['is_active'] ? 'Deactivate' : 'Activate' ?>">
                  <?= icon($service['is_active'] ? 'toggle-on' : 'toggle-off', 'h-4 w-4') ?>
                </button>
              </form>
              <form method="post" action="/admin/services.php" onsubmit="return confirm('Delete this service? This cannot be undone.');">
                <?= csrfField() ?>
                <?= botTrapFields(false) ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $service['id'] ?>">
                <button type="submit" class="flex h-7 w-7 items-center justify-center rounded-lg text-red-500/60 hover:bg-red-50 hover:text-red-700" title="Delete">
                  <?= icon('trash', 'h-3.5 w-3.5') ?>
                </button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
