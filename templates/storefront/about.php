<?php

declare(strict_types=1);

/**
 * درباره تعاونی — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

/** @var array<string, mixed> $__data */

$settings = $__data['settings'] ?? [];
$siteName = $__data['siteName'];
$about = trim((string) ($settings['about_text'] ?? ''));
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>درباره تعاونی صنایع‌دستی نساجی</h1>
        <div class="pa-breadcrumb"><a href="/">خانه</a><span class="pa-sep">›</span><span>درباره</span></div>
    </div>
</div>

<section class="pa-hero pa-mb-2" style="background:linear-gradient(135deg,#6a4c93,#ff595e);color:#fff;border-radius:16px;padding:32px">
    <p style="margin:0;font-size:1.1rem;opacity:.92"><?= View::e($siteName) ?> — تعاونی روستایی برای حفظ صنایع نساجی دست‌بافت و توانمندسازی اعضا.</p>
</section>

<div class="pa-card pa-mb-2">
    <div class="pa-card-body" style="line-height:1.9">
        <?php if ($about !== ''): ?>
            <?= nl2br(View::e($about)) ?>
        <?php else: ?>
            <p>این تعاونی با هدف سازمان‌دهی تولید فرش، گلیم و پارچه‌های دست‌بافت اهل صنعت روستایی تأسیس شده است.</p>
            <ul>
                <li>حفظ میراث نساجی دست‌بافت و انتقال به نسل‌های آینده.</li>
                <li>فروش مستقیم و منصفانهٔ تولید اعضا بدون واسطه.</li>
                <li>تسویهٔ شفاف مالی و سرمایه‌گذاری جمعی اعضا.</li>
            </ul>
        <?php endif; ?>
    </div>
</div>

<div class="pa-row">
    <div class="pa-col-6">
        <div class="pa-card">
            <div class="pa-card-header"><h3>تماس با تعاونی</h3></div>
            <div class="pa-card-body">
                <p>تلفن: <?= View::e((string) ($settings['contact_phone'] ?? '—')) ?></p>
                <p>ایمیل: <?= View::e((string) ($settings['contact_email'] ?? '—')) ?></p>
                <p class="pa-text-muted">نشانی: <?= View::e((string) ($settings['contact_address'] ?? '—')) ?></p>
            </div>
        </div>
    </div>
    <div class="pa-col-6">
        <div class="pa-card">
            <div class="pa-card-header"><h3>پرداخت و تسویه</h3></div>
            <div class="pa-card-body">
                <p>شماره حساب: <?= View::e((string) ($settings['bank_account'] ?? '—')) ?></p>
                <p>شماره شبا: <?= View::e((string) ($settings['sheba'] ?? '—')) ?></p>
                <p class="pa-text-muted pa-fs-sm">پرداخت کارت‌به‌کارت یا در محل — پس از هماهنگی سفارش.</p>
            </div>
        </div>
    </div>
</div>
