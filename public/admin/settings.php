<?php
require_once __DIR__ . '/../../src/bootstrap.php';
requireAdmin();

$errors = [];
$notificationEmail = getSetting('notification_email', ADMIN_NOTIFY_EMAIL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfCheck() || isBotSubmission(false)) {
        $errors[] = 'Your session expired. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'notification_email') {
        $notificationEmail = trim((string) ($_POST['notification_email'] ?? ''));

        if (!filter_var($notificationEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid notification email address.';
        }

        if (!$errors) {
            setSetting('notification_email', $notificationEmail);
            flash('success', 'Notification email updated successfully.');
            redirect('/admin/settings.php');
        }
    } else {
        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword     = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        $stmt = db()->prepare('SELECT * FROM admins WHERE id = :id');
        $stmt->execute(['id' => $_SESSION['admin_id']]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($currentPassword, $admin['password_hash'])) {
            $errors[] = 'Your current password is incorrect.';
        }
        if (mb_strlen($newPassword) < 8) {
            $errors[] = 'New password must be at least 8 characters.';
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'New password and confirmation do not match.';
        }

        if (!$errors) {
            $upd = db()->prepare('UPDATE admins SET password_hash = :hash WHERE id = :id');
            $upd->execute([
                'hash' => password_hash($newPassword, PASSWORD_DEFAULT),
                'id'   => $admin['id'],
            ]);
            flash('success', 'Password updated successfully.');
            redirect('/admin/settings.php');
        }
    }
}

$pageTitle = 'Settings (Admin)';
$activeAdminNav = 'settings';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/../../src/partials/flash.php';
?>

<div class="mb-8">
  <h1 class="font-display text-2xl font-bold text-navy-900">Settings</h1>
  <p class="mt-1 text-sm text-navy-900/50">Manage website notifications and your admin account.</p>
</div>

<?php if ($errors): ?>
  <div class="mb-6 max-w-lg rounded-xl border border-red-500/30 bg-red-50 p-4 text-sm text-red-800">
    <ul class="list-inside list-disc space-y-1">
      <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="max-w-lg space-y-6">
  <div class="card p-6 sm:p-8">
    <h2 class="font-display text-base font-bold text-navy-900">Notification Email</h2>
    <p class="mt-1 text-sm text-navy-900/50">New booking and enquiry notifications will be sent to this address.</p>

    <form method="post" action="/admin/settings.php" class="mt-5 space-y-4">
      <?= csrfField() ?>
      <?= botTrapFields(false) ?>
      <input type="hidden" name="action" value="notification_email">
      <div>
        <label for="notification_email" class="field-label">Recipient email address</label>
        <input type="email" id="notification_email" name="notification_email" required maxlength="254" class="field-input" value="<?= e($notificationEmail) ?>" placeholder="name@example.com">
      </div>
      <button type="submit" class="btn-primary w-full">Save Notification Email</button>
    </form>
  </div>

  <div class="card p-6 sm:p-8">
    <h2 class="font-display text-base font-bold text-navy-900">Change Password</h2>
    <p class="mt-1 text-sm text-navy-900/50">Signed in as <strong><?= e(currentAdminUsername()) ?></strong>.</p>

    <form method="post" action="/admin/settings.php" class="mt-5 space-y-4">
      <?= csrfField() ?>
      <?= botTrapFields(false) ?>
      <input type="hidden" name="action" value="password">
      <div>
        <label for="current_password" class="field-label">Current password</label>
        <input type="password" id="current_password" name="current_password" required class="field-input">
      </div>
      <div>
        <label for="new_password" class="field-label">New password</label>
        <input type="password" id="new_password" name="new_password" required minlength="8" class="field-input">
      </div>
      <div>
        <label for="confirm_password" class="field-label">Confirm new password</label>
        <input type="password" id="confirm_password" name="confirm_password" required minlength="8" class="field-input">
      </div>
      <button type="submit" class="btn-primary w-full">Update Password</button>
    </form>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
