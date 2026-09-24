</main>

<footer class="bg-ink-900 text-white/80">
  <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="grid gap-12 lg:grid-cols-4">
      <div class="lg:col-span-2">
        <a href="/" class="font-display text-lg font-bold tracking-tight text-white">
          ANDSON <span class="text-brand-400">TRAVEL CONSULT</span>
        </a>
        <p class="mt-4 max-w-sm text-sm leading-relaxed text-white/60">
          Expert travel consultancy and advisory services for the United States, United Kingdom, Europe and beyond, from document prep to airport assistance.
        </p>
        <p class="mt-4 max-w-sm text-xs leading-relaxed text-white/40">
          We are not a visa connection agency and do not guarantee visa outcomes. We are committed to helping applicants complete the process correctly and confidently.
        </p>
        <?php $facebookUrl = getSetting('facebook_url'); $instagramUrl = getSetting('instagram_url'); ?>
        <?php if ($facebookUrl !== '' || $instagramUrl !== ''): ?>
          <div class="mt-6 flex items-center gap-3">
            <a href="<?= e(CONTACT_WHATSAPP_URL) ?>" target="_blank" rel="noopener" aria-label="WhatsApp" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-whatsapp-500 transition hover:bg-white/20">
              <?= icon('whatsapp', 'h-5 w-5') ?>
            </a>
            <?php if ($facebookUrl !== ''): ?>
              <a href="<?= e($facebookUrl) ?>" target="_blank" rel="noopener" aria-label="Facebook" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20">
                <?= icon('facebook', 'h-4 w-4') ?>
              </a>
            <?php endif; ?>
            <?php if ($instagramUrl !== ''): ?>
              <a href="<?= e($instagramUrl) ?>" target="_blank" rel="noopener" aria-label="Instagram" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20">
                <?= icon('instagram', 'h-5 w-5') ?>
              </a>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <div class="mt-6 flex items-center gap-3">
            <a href="<?= e(CONTACT_WHATSAPP_URL) ?>" target="_blank" rel="noopener" aria-label="WhatsApp" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-whatsapp-500 transition hover:bg-white/20">
              <?= icon('whatsapp', 'h-5 w-5') ?>
            </a>
          </div>
        <?php endif; ?>
      </div>

      <div>
        <h3 class="font-display text-sm font-bold uppercase tracking-widest text-white">Contact</h3>
        <ul class="mt-4 space-y-3 text-sm">
          <li class="flex items-start gap-3">
            <?= icon('location', 'h-5 w-5 shrink-0 text-brand-400') ?>
            <span><?= e(CONTACT_ADDRESS) ?></span>
          </li>
          <li class="flex items-start gap-3">
            <?= icon('phone', 'h-5 w-5 shrink-0 text-brand-400') ?>
            <a href="tel:<?= e(CONTACT_PHONE_TEL) ?>" class="hover:text-white"><?= e(CONTACT_PHONE_DISPLAY) ?></a>
          </li>
          <li class="flex items-start gap-3">
            <?= icon('mail', 'h-5 w-5 shrink-0 text-brand-400') ?>
            <a href="mailto:<?= e(CONTACT_EMAIL) ?>" class="hover:text-white break-all"><?= e(CONTACT_EMAIL) ?></a>
          </li>
        </ul>
      </div>

      <div>
        <h3 class="font-display text-sm font-bold uppercase tracking-widest text-white">Useful Links</h3>
        <ul class="mt-4 space-y-3 text-sm">
          <li><a href="/about.php" class="hover:text-white">About Us</a></li>
          <li><a href="/services.php" class="hover:text-white">Assistance Services</a></li>
          <li><a href="/faqs.php" class="hover:text-white">FAQs</a></li>
          <li><a href="/contact.php" class="hover:text-white">Contact</a></li>
          <li><a href="/privacy-policy.php" class="hover:text-white">Privacy Policy</a></li>
          <li><a href="/terms.php" class="hover:text-white">Terms &amp; Conditions</a></li>
          <li><a href="/admin/login.php" class="inline-flex items-center gap-1.5 text-white/50 hover:text-white"><?= icon('lock', 'h-3.5 w-3.5') ?> Staff Login</a></li>
        </ul>
      </div>
    </div>

    <div class="mt-14 flex flex-col items-center justify-between gap-2 border-t border-white/10 pt-8 text-xs text-white/40 sm:flex-row">
      <p>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved.</p>
      <p>Accra, Ghana &middot; Built for travellers, by travel people.</p>
      <p>Built by <a href="https://princecaleb.dev" target="_blank" rel="noopener" class="font-semibold text-white/60 hover:text-white">princecaleb.dev</a></p>
    </div>
  </div>
</footer>

<a href="<?= e(CONTACT_WHATSAPP_URL) ?>" target="_blank" rel="noopener"
   class="fixed bottom-5 right-5 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-whatsapp-500 text-white shadow-xl shadow-whatsapp-500/30 transition hover:scale-105 hover:bg-whatsapp-600"
   aria-label="Chat with an agent on WhatsApp">
  <?= icon('whatsapp', 'h-7 w-7 text-white') ?>
</a>

<script src="/assets/js/app.js"></script>
</body>
</html>
