<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM applications WHERE id = :id');
$stmt->execute(['id' => $id]);
$app = $stmt->fetch();

if (!$app) {
    flash('error', 'That application could not be found.');
    redirect('/admin/applications.php');
}

// Opening the record clears it from the notification bell.
db()->prepare("UPDATE applications SET seen_at = datetime('now') WHERE id = :id AND seen_at IS NULL")->execute(['id' => $id]);

$validStatuses = ['new', 'contacted', 'in_progress', 'completed', 'closed'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck() || isBotSubmission(false)) {
        flash('error', 'Your session expired. Please try again.');
        redirect('/admin/application.php?id=' . $id);
    }

    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'update_status') {
        $newStatus = (string) ($_POST['status'] ?? '');
        if (in_array($newStatus, $validStatuses, true)) {
            $upd = db()->prepare('UPDATE applications SET status = :status WHERE id = :id');
            $upd->execute(['status' => $newStatus, 'id' => $id]);
            flash('success', 'Status updated.');
        }
        redirect('/admin/application.php?id=' . $id);
    }

    if ($action === 'delete') {
        $del = db()->prepare('DELETE FROM applications WHERE id = :id');
        $del->execute(['id' => $id]);
        flash('success', 'Application deleted.');
        redirect('/admin/applications.php');
    }
}

$pageTitle = $app['full_name'] . ' (Admin)';
$activeAdminNav = 'applications';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<a href="/admin/applications.php" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy-900/50 hover:text-navy-900">
  &larr; Back to Service Bookings
</a>

<div class="mt-4 grid gap-6 lg:grid-cols-3">
  <div class="lg:col-span-2">
    <div class="card p-6 sm:p-8">
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
          <h1 class="font-display text-xl font-bold text-navy-900"><?= e($app['full_name']) ?></h1>
          <p class="mt-1 text-sm text-navy-900/50">Applied for <strong><?= e($app['service_title']) ?></strong> &middot; <?= timeAgo($app['created_at']) ?></p>
        </div>
        <span class="<?= statusBadgeClasses($app['status']) ?>"><?= e(statusLabel($app['status'])) ?></span>
      </div>

      <dl class="mt-6 grid gap-5 sm:grid-cols-2">
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">Email</dt>
          <dd class="mt-1"><a href="mailto:<?= e($app['email']) ?>" class="text-sm font-semibold text-brand-700 hover:text-brand-600"><?= e($app['email']) ?></a></dd>
        </div>
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">Phone / WhatsApp</dt>
          <dd class="mt-1"><a href="tel:<?= e($app['phone']) ?>" class="text-sm font-semibold text-brand-700 hover:text-brand-600"><?= e($app['phone']) ?></a></dd>
        </div>
        <?php if (!empty($app['destination'])): ?>
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">Destination</dt>
          <dd class="mt-1 text-sm text-navy-900"><?= e($app['destination']) ?></dd>
        </div>
        <?php endif; ?>
      </dl>

      <?php if (!empty($app['message'])): ?>
        <div class="mt-6">
          <dt class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">Message</dt>
          <p class="mt-2 whitespace-pre-line rounded-xl bg-sand-100 p-4 text-sm leading-relaxed text-navy-900/75"><?= e($app['message']) ?></p>
        </div>
      <?php endif; ?>

      <?php $extraFields = !empty($app['extra_fields']) ? json_decode($app['extra_fields'], true) : null; ?>
      <?php if ($extraFields): ?>
        <div class="mt-6">
          <dt class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">Full Application Answers</dt>
          <dl class="mt-2 divide-y divide-navy-900/5 rounded-xl border border-navy-900/5">
            <?php foreach ($extraFields as $label => $value): ?>
              <div class="grid gap-1 px-4 py-3 sm:grid-cols-3 sm:gap-4">
                <dt class="text-xs font-semibold text-navy-900/50 sm:col-span-1"><?= e($label) ?></dt>
                <dd class="whitespace-pre-line text-sm text-navy-900/80 sm:col-span-2"><?= $value !== '' ? e($value) : '<span class="text-navy-900/30">Not answered</span>' ?></dd>
              </div>
            <?php endforeach; ?>
          </dl>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="space-y-6">
    <div class="card p-6">
      <h2 class="font-display text-sm font-bold text-navy-900">Update Status</h2>
      <form method="post" action="/admin/application.php?id=<?= $id ?>" class="mt-4 space-y-3">
        <?= csrfField() ?>
        <?= botTrapFields(false) ?>
        <input type="hidden" name="action" value="update_status">
        <select name="status" class="field-input">
          <?php foreach ($validStatuses as $status): ?>
            <option value="<?= e($status) ?>" <?= $app['status'] === $status ? 'selected' : '' ?>><?= e(statusLabel($status)) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-primary w-full">Save Status</button>
      </form>
    </div>

    <div class="card p-6">
      <h2 class="font-display text-sm font-bold text-navy-900">Quick Contact</h2>
      <div class="mt-4 flex flex-col gap-2">
        <a href="mailto:<?= e($app['email']) ?>" class="btn-outline-navy w-full"><?= icon('mail', 'h-4 w-4') ?> Email</a>
        <a href="tel:<?= e($app['phone']) ?>" class="btn-outline-navy w-full"><?= icon('phone', 'h-4 w-4') ?> Call</a>
      </div>
    </div>

    <div class="card border-red-200 p-6">
      <h2 class="font-display text-sm font-bold text-red-700">Danger Zone</h2>
      <p class="mt-2 text-xs text-navy-900/50">Permanently delete this application record.</p>
      <form method="post" action="/admin/application.php?id=<?= $id ?>" class="mt-4" onsubmit="return confirm('Delete this application? This cannot be undone.');">
        <?= csrfField() ?>
        <?= botTrapFields(false) ?>
        <input type="hidden" name="action" value="delete">
        <button type="submit" class="btn w-full border border-red-300 text-red-700 hover:bg-red-50"><?= icon('trash', 'h-4 w-4') ?> Delete</button>
      </form>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
