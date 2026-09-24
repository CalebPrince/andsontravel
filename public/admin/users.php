<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireSuperAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck()) {
        flash('error', 'Your session expired. Please try again.');
        redirect('/admin/users.php');
    }

    $action = (string) ($_POST['action'] ?? '');
    $id = (int) ($_POST['id'] ?? 0);
    $target = getAdminById($id);

    if (!$target) {
        flash('error', 'That user could not be found.');
        redirect('/admin/users.php');
    }

    if ($action === 'delete') {
        if ($id === (int) $_SESSION['admin_id']) {
            flash('error', 'You can&rsquo;t delete your own account while signed in as it.');
        } elseif ($target['role'] === 'super_admin' && countSuperAdmins() <= 1) {
            flash('error', 'You can&rsquo;t delete the last super admin.');
        } else {
            deleteAdmin($id);
            flash('success', 'User deleted.');
        }
    }

    redirect('/admin/users.php');
}

$admins = allAdmins();

$pageTitle = 'Users (Admin)';
$activeAdminNav = 'users';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
  <div>
    <h1 class="font-display text-2xl font-bold text-navy-900">Users</h1>
    <p class="mt-1 text-sm text-navy-900/50">Everyone with access to this admin area. <?= count($admins) ?> total.</p>
  </div>
  <a href="/admin/user-form.php" class="btn-primary"><?= icon('plus', 'h-4 w-4') ?> Add User</a>
</div>

<div class="card overflow-x-auto">
  <table class="w-full min-w-[560px] text-left text-sm">
    <thead>
      <tr class="border-b border-navy-900/5 text-xs font-semibold uppercase tracking-wider text-navy-900/40">
        <th class="px-5 py-3">Username</th>
        <th class="px-5 py-3">Role</th>
        <th class="px-5 py-3">Added</th>
        <th class="px-5 py-3 text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-navy-900/5">
      <?php foreach ($admins as $adminRow): ?>
        <tr class="hover:bg-navy-900/[0.02]">
          <td class="px-5 py-3.5 font-semibold text-navy-900">
            <?= e($adminRow['username']) ?>
            <?php if ((int) $adminRow['id'] === (int) $_SESSION['admin_id']): ?>
              <span class="ml-1.5 text-xs font-normal text-navy-900/40">(you)</span>
            <?php endif; ?>
          </td>
          <td class="px-5 py-3.5">
            <span class="badge <?= $adminRow['role'] === 'super_admin' ? 'bg-brand-600/10 text-brand-700' : 'bg-slate-400/10 text-slate-600' ?>">
              <?= e(adminRoleLabel($adminRow['role'])) ?>
            </span>
          </td>
          <td class="px-5 py-3.5 text-navy-900/50"><?= timeAgo($adminRow['created_at']) ?></td>
          <td class="px-5 py-3.5">
            <div class="flex items-center justify-end gap-1.5">
              <a href="/admin/user-form.php?id=<?= (int) $adminRow['id'] ?>" class="flex h-8 w-8 items-center justify-center rounded-lg text-navy-900/40 hover:bg-navy-900/5 hover:text-navy-900" title="Edit">
                <?= icon('pencil', 'h-4 w-4') ?>
              </a>
              <form method="post" action="/admin/users.php" onsubmit="return confirm('Delete this user? They will immediately lose access.');">
                <?= csrfField() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int) $adminRow['id'] ?>">
                <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg text-red-500/60 hover:bg-red-50 hover:text-red-700" title="Delete">
                  <?= icon('trash', 'h-4 w-4') ?>
                </button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<p class="mt-4 text-xs text-navy-900/40">
  Super Admin and Admin currently have the same access. More roles with finer-grained permissions may be added later.
</p>

<?php require __DIR__ . '/includes/footer.php'; ?>
