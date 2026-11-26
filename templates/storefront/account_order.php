<?php

declare(strict_types=1);

/**
 * جزئیات سفارش (حساب مشتری) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level private
 */

/** @var array<string, mixed> $__data */

$order = $__data['order'] ?? [];
$items = $__data['items'] ?? [];
$money = $__data['money'];
$toFa = $__data['toFa'];

$statusLabels = [
    'pending' => 'در انتظار', 'paid' => 'پرداخت‌شده', 'processing' => 'در حال آماده‌سازی',
    'shipped' => 'ارسال‌شده', 'delivered' => 'تحویل‌شده', 'cancelled' => 'لغو‌شده', 'refunded' => 'بازگشت‌دار',
];
$statusBadge = [
    'pending' => 'pa-badge-warning', 'paid' => 'pa-badge-info', 'processing' => 'pa-badge-primary',
    'shipped' => 'pa-badge-primary', 'delivered' => 'pa-badge-success', 'cancelled' => 'pa-badge-danger', 'refunded' => 'pa-badge-danger',
];
$status = (string) ($order['status'] ?? '');
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>سفارش <?= View::e((string) ($order['order_number'] ?? '')) ?></h1>
        <div class="pa-breadcrumb"><a href="/">خانه</a><span class="pa-sep">›</span><a href="/account">حساب من</a><span class="pa-sep">›</span><span><?= View::e((string) ($order['order_number'] ?? '')) ?></span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/account" data-pa-icon="arrow_right"> بازگشت</a>
</div>

<div class="pa-row pa-mb-2">
    <div class="pa-col-6">
        <div class="pa-card">
            <div class="pa-card-header"><h3>وضعیت سفارش</h3></div>
            <div class="pa-card-body">
                <p>وضعیت: <span class="pa-badge <?= View::e($statusBadge[$status] ?? 'pa-badge-secondary') ?>"><?= View::e($statusLabels[$status] ?? $status) ?></span></p>
                <p class="pa-text-muted">تاریخ ثبت: <?= $toFa((string) mb_substr((string) ($order['created_at'] ?? ''), 0, 10, 'UTF-8')) ?></p>
            </div>
        </div>
    </div>
    <div class="pa-col-6">
        <div class="pa-card">
            <div class="pa-card-header"><h3>تحویل به</h3></div>
            <div class="pa-card-body">
                <p><strong><?= View::e((string) ($order['shipping_name'] ?? '')) ?></strong></p>
                <p class="pa-text-muted"><?= View::e((string) ($order['shipping_address'] ?? '')) ?>، <?= View::e((string) ($order['shipping_city'] ?? '')) ?></p>
                <p class="pa-text-muted">تلفن: <?= View::e((string) ($order['shipping_phone'] ?? '')) ?> — کد پستی: <?= $toFa((string) ($order['shipping_postal_code'] ?? '')) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="pa-card pa-mb-2">
    <div class="pa-card-header"><h3>اقلام</h3></div>
    <div class="pa-card-body" style="padding:0">
        <div class="pa-table-wrap">
            <table class="pa-table pa-table-striped">
                <thead><tr><th>کالا</th><th class="pa-text-end">تعداد</th><th class="pa-text-end">مبلغ واحد</th><th class="pa-text-end">مبلغ کل</th></tr></thead>
                <tbody>
                <?php foreach ($items as $it): ?>
                    <tr><td><?= View::e((string) $it['product_name']) ?></td><td class="pa-text-end"><?= $toFa((string) $it['quantity']) ?></td><td class="pa-text-end"><?= $money((int) $it['price']) ?></td><td class="pa-text-end"><?= $money((int) $it['subtotal']) ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="pa-card" style="max-width:420px;margin-inline:auto">
    <div class="pa-card-body">
        <div class="pa-d-flex pa-justify-between pa-mb-1"><span class="pa-text-muted">جمع کالاها</span><span><?= $money((int) ($order['subtotal'] ?? 0)) ?></span></div>
        <div class="pa-d-flex pa-justify-between pa-mb-1"><span class="pa-text-muted">تخفیف</span><span>-<?= $money((int) ($order['discount'] ?? 0)) ?></span></div>
        <div class="pa-d-flex pa-justify-between pa-mb-1"><span class="pa-text-muted">ارسال</span><span><?= (int) ($order['shipping_cost'] ?? 0) === 0 ? 'رایگان' : $money((int) $order['shipping_cost']) ?></span></div>
        <div class="pa-d-flex pa-justify-between" style="border-top:1px solid var(--pa-border);padding-top:8px;margin-top:8px"><strong>قابل پرداخت</strong><strong class="pa-fs-lg" style="color:var(--pa-primary)"><?= $money((int) ($order['total'] ?? 0)) ?></strong></div>
    </div>
</div>
