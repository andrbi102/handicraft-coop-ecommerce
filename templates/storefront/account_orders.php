<?php

declare(strict_types=1);

/**
 * سفارش‌های من — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level private
 */

/** @var array<string, mixed> $__data */

$orders = $__data['orders'] ?? [];
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
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>سفارش‌های من</h1>
        <div class="pa-breadcrumb"><a href="/">خانه</a><span class="pa-sep">›</span><span>حساب من</span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/account/profile" data-pa-icon="user"> پروفایل</a>
</div>

<?php if ($orders === []): ?>
    <div class="pa-card pa-text-center" style="padding:40px">
        <span data-pa-icon="shopping_bag" data-pa-icon-size="48" style="opacity:.4"></span>
        <h3 class="pa-mt-1">هنوز سفارشی ثبت نکرده‌اید.</h3>
        <a class="pa-btn pa-btn-primary pa-mt-1" href="/products" data-pa-icon="package_alt"> شروع خرید</a>
    </div>
<?php else: ?>
<div class="pa-card">
    <div class="pa-card-body" style="padding:0">
        <div class="pa-table-wrap">
            <table class="pa-table pa-table-striped pa-table-hover">
                <thead><tr><th>شماره</th><th>تاریخ</th><th>وضعیت</th><th class="pa-text-end">مبلغ</th><th data-no-export></th></tr></thead>
                <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><strong><?= View::e((string) $o['order_number']) ?></strong></td>
                        <td class="pa-text-muted"><?= $toFa((string) mb_substr((string) $o['created_at'], 0, 10, 'UTF-8')) ?></td>
                        <td><span class="pa-badge <?= View::e($statusBadge[$o['status']] ?? 'pa-badge-secondary') ?>"><?= View::e($statusLabels[$o['status']] ?? $o['status']) ?></span></td>
                        <td class="pa-text-end"><?= $money((int) $o['total']) ?></td>
                        <td data-no-export><a class="pa-btn pa-btn-outline pa-btn-sm" href="/account/order/<?= rawurlencode((string) $o['order_number']) ?>">جزئیات</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
