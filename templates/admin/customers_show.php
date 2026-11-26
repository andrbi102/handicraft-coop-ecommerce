<?php

declare(strict_types=1);

/**
 * جزئیات مشتری (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$customer = $__data['customer'] ?? [];
$orders = $__data['orders'] ?? [];
$money = $__data['money'];
$toFa = $__data['toFa'];

// نقشهٔ کوتاه وضعیت (هم‌خوان با نسخهٔ قبلی قالب)
$statusLabels = [
    'pending' => 'در انتظار', 'paid' => 'پرداخت‌شده', 'processing' => 'آماده‌سازی',
    'shipped' => 'ارسال', 'delivered' => 'تحویل', 'cancelled' => 'لغو', 'refunded' => 'بازگشت',
];
$statusBadge = [
    'pending' => 'pa-badge-warning', 'paid' => 'pa-badge-info', 'processing' => 'pa-badge-primary',
    'shipped' => 'pa-badge-primary', 'delivered' => 'pa-badge-success', 'cancelled' => 'pa-badge-danger', 'refunded' => 'pa-badge-danger',
];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1><?= View::e((string) ($customer['name'] ?? '')) ?></h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><a href="/admin/customers">مشتریان</a><span class="pa-sep">›</span><span><?= View::e((string) ($customer['name'] ?? '')) ?></span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/admin/customers" data-pa-icon="arrow_right"> بازگشت</a>
</div>

<div class="pa-row">
    <div class="pa-col-4">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>اطلاعات مشتری</h3></div>
            <div class="pa-card-body">
                <p><strong><?= View::e((string) ($customer['name'] ?? '')) ?></strong></p>
                <p class="pa-text-muted">ایمیل: <?= View::e((string) ($customer['email'] ?? '')) ?></p>
                <p class="pa-text-muted">تلفن: <?= View::e((string) ($customer['phone'] ?? '')) ?></p>
                <p class="pa-text-muted">عضویت: <?= $toFa((string) mb_substr((string) ($customer['created_at'] ?? ''), 0, 10, 'UTF-8')) ?></p>
            </div>
        </div>
    </div>

    <div class="pa-col-8">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>سفارش‌های مشتری</h3></div>
            <div class="pa-card-body" style="padding:0">
                <div class="pa-table-wrap">
                    <table class="pa-table pa-table-striped pa-table-hover">
                        <thead><tr><th>شماره</th><th>تاریخ</th><th>وضعیت</th><th class="pa-text-end">مبلغ</th><th data-no-export></th></tr></thead>
                        <tbody>
                        <?php if ($orders === []): ?>
                            <tr><td colspan="5" class="pa-text-center pa-text-muted" style="padding:24px">سفارشی ندارد.</td></tr>
                        <?php else: ?>
                            <?php foreach ($orders as $o): ?>
                                <tr>
                                    <td><strong><?= View::e($o['order_number']) ?></strong></td>
                                    <td class="pa-text-muted"><?= $toFa((string) mb_substr((string) $o['created_at'], 0, 10, 'UTF-8')) ?></td>
                                    <td><span class="pa-badge <?= View::e($statusBadge[$o['status']] ?? 'pa-badge-secondary') ?>"><?= View::e($statusLabels[$o['status']] ?? $o['status']) ?></span></td>
                                    <td class="pa-text-end"><?= $money((int) $o['total']) ?></td>
                                    <td data-no-export><a class="pa-btn pa-btn-ghost pa-btn-sm" href="/admin/orders/<?= (int) $o['id'] ?>" data-pa-icon="eye" data-pa-icon-size="16"></a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
