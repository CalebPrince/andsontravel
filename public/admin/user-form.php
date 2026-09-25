<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireSuperAdmin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$existing = $id ? getAdminById($id) : null;

if ($id && !$existing) {
    flash('error', 'That user could not be found.');
    redirect('/admin/users.php');
}

$errors = [];
$values = [
    'username' => $existing['username'] ?? '',
    'role'     => $existing['role'] ?? 'admin',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck() || isBotSubmission(false)) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $values['username'] = trim((string) ($_POST['username'] ?? ''));
        $values['role']     = (string) ($_POST['role'] ?? 'admin');
        $password            = (string) ($_POST['password'] ?? '');
        $confirmPassword      = (string) ($_POST['confirm_password'] ?? '');

        if ($values['username'] === '' || mb_strlen($values['username']) > 60 || !preg_match('/^[a-zA-Z0-9_.-]+$/', $values['username'])) {
            $errors[] = 'Username must be 1-60 characters: letters, numbers, dots, dashes or underscores only.';
        }
        if (adminUsernameExists($values['username'], $id)) {
            $errors[] = 'That username is already taken.';
        }
        if (!in_array($values['role'], availableAdminRoles(), true)) {
            $errors[] = 'Please choose a valid role.';
        }
        if ($id && $existing['role'] === 'super_admin' && $values['role'] !== 'super_admin' && countSuperAdmins() <= 1) {
            $errors[] = 'You can&rsquo;t demote the last super admin.';
        }

        if (!$id) {
            if (mb_strlen($password) < 8) {
                $errors[] = 'Password must be at least 8 characters.';
            }
            if ($password !== $confirmPassword) {
                $errors[] = 'Password and confirmation do not match.';
            }
        } elseif ($password !== '' || $confirmPassword !== '') {
            if (mb_strlen($password) < 8) {
                $errors[] = 'New password must be at least 8 characters.';
            }
            if ($password !== $confirmPassword) {
                $errors[] = 'New password and confirmation do not match.';
            }
        }

        if (!$errors) {
            if ($id) {
                updateAdminRole($id, $values['role']);
                if ($password !== '') {
                    updateAdminPassword($id, $password);
                }
                flash('success', 'User updated.');
            } else {
                createAdmin($values['username'], $password, $values['role']);
                flash('success', 'User created.');
            }
            redirect('/admin/users.php');
        }
    }
}

$pageTitle = ($id ? 'Edit User' : 'Add User') . ' (Admin)';
$activeAdminNav = 'users';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<a href="/admin/users.php" class="inline-flex items-center gap-1.5 text-sm font-semibold text-navy-900/50 hover:text-navy-900">
  &larr; Back to Users
</a>

<div class="mt-4 max-w-lg">
  <h1 class="font-display text-2xl font-bold text-navy-900"><?= $id ? 'Edit User' : 'Add User' ?></h1>

  <?php if ($errors): ?>
    <div class="mt-4 rounded-xl border border-red-500/30 bg-red-50 p-4 text-sm text-red-800">
      <ul class="list-inside list-disc space-y-1">
        <?php foreach ($errors as $error): ?><li><?= $error ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= $id ? '/admin/user-form.php?id=' . $id : '/admin/user-form.php' ?>" class="card mt-4 space-y-5 p-6 sm:p-8">
    <?= csrfField() ?>
    <?= botTrapFields(false) ?>

    <div>
      <label for="username" class="field-label">Username</label>
      <input type="text" id="username" name="username" required maxlength="60" class="field-input" value="<?= e($values['username']) ?>" placeholder="e.g. staff">
    </div>

    <div>
      <span class="field-label">Role</span>
      <div class="grid grid-cols-2 gap-2">
        <?php foreach (availableAdminRoles() as $role): ?>
          <label class="relative flex cursor-pointer items-center justify-center rounded-xl border border-navy-900/10 py-3 text-sm font-semibold text-navy-900/60 transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-600/10 has-[:checked]:text-brand-700">
            <input type="radio" name="role" value="<?= e($role) ?>" class="sr-only" <?= $values['role'] === $role ? 'checked' : '' ?>>
            <?= e(adminRoleLabel($role)) ?>
          </label>
        <?php endforeach; ?>
      </div>
      <p class="mt-1.5 text-xs text-navy-900/40">Super Admin and Admin currently have the same access; more roles may be added later.</p>
    </div>

    <div>
      <label for="password" class="field-label"><?= $id ? 'New password' : 'Password' ?> <?= $id ? '<span class="font-normal text-navy-900/40">(leave blank to keep the current password)</span>' : '' ?></label>
      <input type="password" id="password" name="password" <?= $id ? '' : 'required' ?> minlength="8" class="field-input">
    </div>

    <div>
      <label for="confirm_password" class="field-label">Confirm <?= $id ? 'new ' : '' ?>password</label>
      <input type="password" id="confirm_password" name="confirm_password" <?= $id ? '' : 'required' ?> minlength="8" class="field-input">
    </div>

    <div class="flex items-center gap-3">
      <button type="submit" class="btn-primary"><?= $id ? 'Save Changes' : 'Create User' ?></button>
      <a href="/admin/users.php" class="btn-outline-navy">Cancel</a>
    </div>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
