<?php $flashes = takeFlashes(); ?>
<?php if ($flashes): ?>
  <div class="mx-auto max-w-3xl px-4 pt-6 sm:px-6 lg:px-8">
    <?php foreach ($flashes as $flash): ?>
      <?php
        $isSuccess = $flash['type'] === 'success';
        $classes = $isSuccess
          ? 'border-emerald-500/30 bg-emerald-50 text-emerald-800'
          : 'border-red-500/30 bg-red-50 text-red-800';
      ?>
      <div class="mb-4 flex items-start gap-3 rounded-xl border px-4 py-3 text-sm font-medium <?= $classes ?>">
        <?= icon($isSuccess ? 'check-circle' : 'close', 'h-5 w-5 shrink-0') ?>
        <span><?= e($flash['message']) ?></span>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
