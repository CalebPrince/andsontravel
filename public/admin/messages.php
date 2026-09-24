<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

$statusFilter = trim((string) ($_GET['status'] ?? ''));
$query        = trim((string) ($_GET['q'] ?? ''));

$validStatuses = ['new', 'read', 'replied'];

$sql = 'SELECT * FROM contact_messages WHERE 1=1';
$params = [];

if ($statusFilter !== '' && in_array($statusFilter, $validStatuses, true)) {
    $sql .= ' AND status = :status';
    $params['status'] = $statusFilter;
}

if ($query !== '') {
    $sql .= ' AND (full_name LIKE :q OR email LIKE :q OR subject LIKE :q)';
    $params['q'] = '%' . $query . '%';
}

$sql .= ' ORDER BY created_at DESC LIMIT 200';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$messages = $stmt->fetchAll();

$pageTitle = 'Enquiries (Admin)';
$activeAdminNav = 'messages';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
  <div>
    <h1 class="font-display text-2xl font-bold text-navy-900">Enquiries</h1>
    <p class="mt-1 text-sm text-navy-900/50"><?= count($messages) ?> result<?= count($messages) === 1 ? '' : 's' ?></p>
  </div>

  <form method="get" action="/admin/messages.php" class="flex flex-wrap items-center gap-2">
    <input type="search" name="q" value="<?= e($query) ?>" placeholder="Search name, email, subject&hellip;" class="field-input w-56">
    <select name="status" class="field-input w-auto">
      <option value="">All statuses</option>
      <?php foreach ($validStatuses as $status): ?>
        <option value="<?= e($status) ?>" <?= $statusFilter === $status ? 'selected' : '' ?>><?= e(statusLabel($status)) ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn-outline-navy">Filter</button>
  </form>
</div>

<div class="card overflow-x-auto">
  <table class="w-full min-w-[680px] text-left text-sm">
    <thead>
      <tr class="border-b border-navy-900/5 text-xs font-semibold uppercase tracking-wider text-navy-900/40">
        <th class="px-5 py-3">From</th>
        <th class="px-5 py-3">Subject</th>
        <th class="px-5 py-3">Status</th>
        <th class="px-5 py-3">Received</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-navy-900/5">
      <?php if (!$messages): ?>
        <tr><td colspan="4" class="px-5 py-10 text-center text-navy-900/40">No messages found.</td></tr>
      <?php endif; ?>
      <?php foreach ($messages as $msg): ?>
        <tr class="cursor-pointer hover:bg-navy-900/[0.02]" onclick="window.location='/admin/message.php?id=<?= (int) $msg['id'] ?>'">
          <td class="px-5 py-3.5">
            <div class="font-semibold text-navy-900"><?= e($msg['full_name']) ?></div>
            <div class="text-xs text-navy-900/40"><?= e($msg['email']) ?></div>
          </td>
          <td class="px-5 py-3.5 text-navy-900/70"><?= e($msg['subject'] ?: 'General enquiry') ?></td>
          <td class="px-5 py-3.5"><span class="<?= statusBadgeClasses($msg['status']) ?>"><?= e(statusLabel($msg['status'])) ?></span></td>
          <td class="px-5 py-3.5 whitespace-nowrap text-navy-900/50"><?= timeAgo($msg['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
