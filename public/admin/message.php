<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM contact_messages WHERE id = :id');
$stmt->execute(['id' => $id]);
$msg = $stmt->fetch();

if (!$msg) {
    flash('error', 'That message could not be found.');
    redirect('/admin/messages.php');
}

$validStatuses = ['new', 'read', 'replied'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) {
        flash('error', 'Your session expired. Please try again.');
        redirect('/admin/message.php?id=' . $id);
    }

    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'update_status') {
        $newStatus = (string) ($_POST['status'] ?? '');
        if (in_array($newStatus, $validStatuses, true)) {
            $upd = db()->prepare('UPDATE contact_messages SET status = :status WHERE id = :id');
            $upd->execute(['status' => $newStatus, 'id' => $id]);
            flash('success', 'Status updated.');
        }
        redirect('/admin/message.php?id=' . $id);
    }

    if ($action === 'delete') {
        $del = db()->prepare('DELETE FROM contact_messages WHERE id = :id');
        $del->execute(['id' => $id]);
        flash('success', 'Enquiry deleted.');
        redirect('/admin/messages.php');
    }
}

// Mark as read automatically when an admin opens a new message.
if ($msg['status'] === 'new') {
    db()->prepare("UPDATE contact_messages SET status = 'read' WHERE id = :id")->execute(['id' => $id]);
    $msg['status'] = 'read';
}

// Opening the record clears it from the notification bell.
db()->prepare("UPDATE contact_messages SET seen_at = datetime('now') WHERE id = :id AND seen_at IS NULL")->execute(['id' => $id]);

$pageTitle = $msg['full_name'] . ' (Admin)';
$activeAdminNav = 'messages';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<a href="/admin/messages.php" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy-900/50 hover:text-navy-900">
  &larr; Back to Enquiries
</a>

<div class="mt-4 grid gap-6 lg:grid-cols-3">
  <div class="lg:col-span-2">
    <div class="card p-6 sm:p-8">
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
          <h1 class="font-display text-xl font-bold text-navy-900"><?= e($msg['subject'] ?: 'General Enquiry') ?></h1>
          <p class="mt-1 text-sm text-navy-900/50">From <strong><?= e($msg['full_name']) ?></strong> &middot; <?= timeAgo($msg['created_at']) ?></p>
        </div>
        <span class="<?= statusBadgeClasses($msg['status']) ?>"><?= e(statusLabel($msg['status'])) ?></span>
      </div>

      <p class="mt-6 whitespace-pre-line rounded-xl bg-sand-100 p-4 text-sm leading-relaxed text-navy-900/75"><?= e($msg['message']) ?></p>
    </div>
  </div>

  <div class="space-y-6">
    <div class="card p-6">
      <h2 class="font-display text-sm font-bold text-navy-900">Contact Details</h2>
      <dl class="mt-4 space-y-3 text-sm">
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">Email</dt>
          <dd class="mt-1"><a href="mailto:<?= e($msg['email']) ?>" class="font-semibold text-brand-700 hover:text-brand-600"><?= e($msg['email']) ?></a></dd>
        </div>
        <?php if (!empty($msg['phone'])): ?>
        <div>
          <dt class="text-xs font-semibold uppercase tracking-wider text-navy-900/40">Phone</dt>
          <dd class="mt-1"><a href="tel:<?= e($msg['phone']) ?>" class="font-semibold text-brand-700 hover:text-brand-600"><?= e($msg['phone']) ?></a></dd>
        </div>
        <?php endif; ?>
      </dl>
    </div>

    <div class="card p-6">
      <h2 class="font-display text-sm font-bold text-navy-900">Update Status</h2>
      <form method="post" action="/admin/message.php?id=<?= $id ?>" class="mt-4 space-y-3">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="update_status">
        <select name="status" class="field-input">
          <?php foreach ($validStatuses as $status): ?>
            <option value="<?= e($status) ?>" <?= $msg['status'] === $status ? 'selected' : '' ?>><?= e(statusLabel($status)) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-primary w-full">Save Status</button>
      </form>
    </div>

    <div class="card border-red-200 p-6">
      <h2 class="font-display text-sm font-bold text-red-700">Danger Zone</h2>
      <p class="mt-2 text-xs text-navy-900/50">Permanently delete this message.</p>
      <form method="post" action="/admin/message.php?id=<?= $id ?>" class="mt-4" onsubmit="return confirm('Delete this message? This cannot be undone.');">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="delete">
        <button type="submit" class="btn w-full border border-red-300 text-red-700 hover:bg-red-50"><?= icon('trash', 'h-4 w-4') ?> Delete</button>
      </form>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
