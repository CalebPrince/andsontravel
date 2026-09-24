<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

$statusFilter = trim((string) ($_GET['status'] ?? ''));
$query        = trim((string) ($_GET['q'] ?? ''));

$validStatuses = ['new', 'contacted', 'in_progress', 'completed', 'closed'];

$sql = 'SELECT * FROM applications WHERE 1=1';
$params = [];

if ($statusFilter !== '' && in_array($statusFilter, $validStatuses, true)) {
    $sql .= ' AND status = :status';
    $params['status'] = $statusFilter;
}

if ($query !== '') {
    $sql .= ' AND (full_name LIKE :q OR email LIKE :q OR phone LIKE :q OR service_title LIKE :q)';
    $params['q'] = '%' . $query . '%';
}

$sql .= ' ORDER BY created_at DESC LIMIT 200';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$applications = $stmt->fetchAll();

$pageTitle = 'Service Bookings (Admin)';
$activeAdminNav = 'applications';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
  <div>
    <h1 class="font-display text-2xl font-bold text-navy-900">Service Bookings</h1>
    <p class="mt-1 text-sm text-navy-900/50"><?= count($applications) ?> result<?= count($applications) === 1 ? '' : 's' ?></p>
  </div>

  <form method="get" action="/admin/applications.php" class="flex flex-wrap items-center gap-2">
    <input type="search" name="q" value="<?= e($query) ?>" placeholder="Search name, email, phone&hellip;" class="field-input w-56">
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
  <table class="w-full min-w-[720px] text-left text-sm">
    <thead>
      <tr class="border-b border-navy-900/5 text-xs font-semibold uppercase tracking-wider text-navy-900/40">
        <th class="px-5 py-3">Applicant</th>
        <th class="px-5 py-3">Service</th>
        <th class="px-5 py-3">Contact</th>
        <th class="px-5 py-3">Status</th>
        <th class="px-5 py-3">Submitted</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-navy-900/5">
      <?php if (!$applications): ?>
        <tr><td colspan="5" class="px-5 py-10 text-center text-navy-900/40">No applications found.</td></tr>
      <?php endif; ?>
      <?php foreach ($applications as $app): ?>
        <tr class="cursor-pointer hover:bg-navy-900/[0.02]" onclick="window.location='/admin/application.php?id=<?= (int) $app['id'] ?>'">
          <td class="px-5 py-3.5 font-semibold text-navy-900"><?= e($app['full_name']) ?></td>
          <td class="px-5 py-3.5 text-navy-900/70"><?= e($app['service_title']) ?></td>
          <td class="px-5 py-3.5 text-navy-900/70">
            <div><?= e($app['email']) ?></div>
            <div class="text-xs text-navy-900/40"><?= e($app['phone']) ?></div>
          </td>
          <td class="px-5 py-3.5"><span class="<?= statusBadgeClasses($app['status']) ?>"><?= e(statusLabel($app['status'])) ?></span></td>
          <td class="px-5 py-3.5 whitespace-nowrap text-navy-900/50"><?= timeAgo($app['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
