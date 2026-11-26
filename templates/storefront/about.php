<?php
declare(strict_types=1);
/**
 * درباره تعاونی.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level public
 */
$settings = $__data['settings'] ?? [];
?>
<div class="hero" style="margin-bottom:24px">
    <div class="container">
        <h1>درباره تعاونی صنایع‌دستی نساجی</h1>
        <p>حفظ میراث نساجی روستایی، توانمندسازی اهل صنعت، و عرضه تولیدات اصیل به سراسر کشور.</p>
    </div>
</div>

<div class="admin-card">
    <?php $about = $settings['about_text'] ?? ''; ?>
    <?php if ($about !== ''): ?>
        <?= nl2br(View::e($about)) ?>
    <?php else: ?>
        <p>این تعاونی با هدف سازمان‌دهی تولید فرش، گلیم و پارچه‌های دست‌بافت اعضای روستایی
        شکل گرفته است. هر محصول، دست‌سازه یک عضو تعاونی است و بخش عمده‌ای از مبلغ فروش،
        مستقیماً به‌صورت مزد نساجی به سازنده پرداخت می‌شود. سامانه تسویه شفاف تعاونی،
        تسویه دوره‌ای هر عضو را قابل پیگیری می‌سازد.</p>
        <p class="mt-2">اهداف تعاونی:</p>
        <ul style="margin-inline-start:20px">
            <li>حفظ و احیای هنرهای سنتی نساجی</li>
            <li>پرداخت منصفانه و به‌موقع به نساجان</li>
            <li>عرضه مستقیم محصول به مصرف‌کننده بدون واسطه</li>
            <li>تسهیل مشارکت اعضا از طریق سهام تعاونی</li>
        </ul>
    <?php endif; ?>
</div>

<div class="row mt-3">
    <div class="col admin-card">
        <h3>تماس با تعاونی</h3>
        <p>تلفن: <?= View::e($settings['contact_phone'] ?? '—') ?></p>
        <p>ایمیل: <?= View::e($settings['contact_email'] ?? '—') ?></p>
        <p>نشانی: <?= View::e($settings['contact_address'] ?? '—') ?></p>
    </div>
    <div class="col admin-card">
        <h3>پرداخت و تسویه</h3>
        <p>شماره حساب: <?= View::e($settings['bank_account'] ?? '—') ?></p>
        <p>شبا: <?= View::e($settings['sheba'] ?? '—') ?></p>
        <p class="text-muted" style="font-size:13px">پس از ثبت سفارش، مبلغ به حساب تعاونی واریز و سفارش در پنل تأیید می‌شود.</p>
    </div>
</div>
