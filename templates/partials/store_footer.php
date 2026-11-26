<?php

declare(strict_types=1);

/**
 * فوتر فروشگاه — سمت سرور.
 *
 * @package Coop\Views\Partials
 * @version 0.2.0
 * @security-level public
 */

use App\Core\PersianDate;

/** @var string $siteName */
/** @var string $tagline */
/** @var array<string, mixed> $__data */
/** @var callable $toFa */

$settings = $__data['settings'] ?? [];
$year = PersianDate::year();
?>
<footer class="pa-site-footer" role="contentinfo">
    <div class="pa-site-footer-in">
        <div class="pa-site-footer-grid">
            <div>
                <h4><?= View::e($siteName) ?></h4>
                <p class="pa-text-muted pa-fs-sm"><?= View::e($tagline) ?></p>
                <p class="pa-text-muted pa-fs-sm"><?= View::e((string) ($settings['contact_address'] ?? '')) ?></p>
            </div>
            <div>
                <h4>دسترسی سریع</h4>
                <div class="pa-site-footer-links">
                    <a href="/products">محصولات</a>
                    <a href="/members">نساجان تعاونی</a>
                    <a href="/about">درباره تعاونی</a>
                    <a href="/contact">تماس</a>
                </div>
            </div>
            <div>
                <h4>تماس</h4>
                <div class="pa-site-footer-links">
                    <span>تلفن: <?= View::e((string) ($settings['contact_phone'] ?? '—')) ?></span>
                    <span>ایمیل: <?= View::e((string) ($settings['contact_email'] ?? '—')) ?></span>
                    <?php if (!empty($settings['social_instagram'])): ?>
                        <span>اینستاگرام: <?= View::e((string) $settings['social_instagram']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <h4>پرداخت</h4>
                <div class="pa-site-footer-links">
                    <span class="pa-text-muted pa-fs-sm">شماره حساب: <?= View::e((string) ($settings['bank_account'] ?? '—')) ?></span>
                    <span class="pa-text-muted pa-fs-sm">شبا: <?= View::e((string) ($settings['sheba'] ?? '—')) ?></span>
                </div>
            </div>
        </div>
        <div class="pa-site-footer-bar">
            © <?= $toFa((string) $year) ?> <?= View::e($siteName) ?> — تمامی حقوق محفوظ است.
            <?= View::e((string) ($settings['footer_text'] ?? '')) ?>
        </div>
    </div>
</footer>
