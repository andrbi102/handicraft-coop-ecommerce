<?php
declare(strict_types=1);
/**
 * سفارش‌های من.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level private
 */
$orders = $__data['orders'] ?? [];
$money = $__data['money'];
$statusLabels = [
    'pending'=>'در انتظار','paid'=>'پرداخت‌شده','processing'=>'در حال آماده‌سازی',
    'shipped'=>'ارسال‌شده','delivered'=>'تحویل‌شده','cancelled'=>'لغو‌شده','refunded'=>'بازگشت‌دار',
];
?>
<div class="section-title">
    <h2>سفارش‌های من</h2>
    <a href="/account/profile">پروفایل →</a>
</div>
<?php if ($orders === []): ?>
<div class="admin-card text-center">
    <p>هنوز سفارشی ثبت نکرده‌اید.</p>
    <p class="mt-2"><a href="/products" class="btn btn-primary">شروع خرید</a></p>
</div>
<?php else: ?>
<div class="table-wrap">
    <table class="data">
        <thead><tr><th>شماره</th><th>تاریخ</th><th>وضعیت</th><th class="num">مبلغ</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($orders as $o): ?>
            <tr>
                <td><strong><?= View::e($o['order_number']) ?></strong></td>
                <td><?= $toFa((string) substr((string) $o['created_at'], 0, 10)) ?></td>
                <td><span class="status-pill <?= View::e($o['status']) ?>"><?= View::e($statusLabels[$o['status']] ?? $o['status']) ?></span></td>
                <td class="num"><?= $money((int) $o['total']) ?></td>
                <td><a href="/account/order/<?= rawurlencode((string) $o['order_number']) ?>" class="btn btn-outline btn-sm">جزئیات</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
