<?php
declare(strict_types=1);
/**
 * جزئیات سفارش مشتری.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level private
 */
$order = $__data['order'] ?? [];
$items = $__data['items'] ?? [];
$money = $__data['money'];
$statusLabels = [
    'pending'=>'در انتظار','paid'=>'پرداخت‌شده','processing'=>'در حال آماده‌سازی',
    'shipped'=>'ارسال‌شده','delivered'=>'تحویل‌شده','cancelled'=>'لغو‌شده','refunded'=>'بازگشت‌دار',
];
?>
<div class="section-title"><h2>سفارش <?= View::e($order['order_number']) ?></h2><a href="/account">بازگست ←</a></div>
<div class="admin-card">
    <div class="row" style="justify-content:space-between">
        <div>
            <p><span class="text-muted">وضعیت:</span>
            <span class="status-pill <?= View::e($order['status']) ?>"><?= View::e($statusLabels[$order['status']] ?? $order['status']) ?></span></p>
            <p class="text-muted">تاریخ ثبت: <?= $toFa((string) substr((string) $order['created_at'], 0, 10)) ?></p>
        </div>
        <div class="text-muted" style="font-size:14px">
            <strong>تحویل به:</strong><br>
            <?= View::e($order['shipping_name']) ?><br>
            <?= View::e($order['shipping_address']) ?>، <?= View::e($order['shipping_city']) ?><br>
            <?= View::e($order['shipping_phone']) ?> — <?= View::e($order['shipping_postal_code']) ?>
        </div>
    </div>
</div>
<div class="admin-card">
    <div class="table-wrap">
        <table class="data">
            <thead><tr><th>کالا</th><th class="num">تعداد</th><th class="num">مبلغ واحد</th><th class="num">مبلغ کل</th></tr></thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= View::e($item['product_name']) ?></td>
                    <td class="num"><?= $toFa((string) $item['quantity']) ?></td>
                    <td class="num"><?= $money((int) $item['price']) ?></td>
                    <td class="num"><?= $money((int) $item['subtotal']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="summary-card mt-2" style="position:static;max-width:320px;margin-inline:auto">
        <div class="row-line"><span>جمع کالاها</span><span><?= $money((int) $order['subtotal']) ?></span></div>
        <div class="row-line"><span>تخفیف</span><span>-<?= $money((int) $order['discount']) ?></span></div>
        <div class="row-line"><span>ارسال</span><span><?= (int) $order['shipping_cost'] === 0 ? 'رایگان' : $money((int) $order['shipping_cost']) ?></span></div>
        <div class="total row-line"><span>قابل پرداخت</span><span><?= $money((int) $order['total']) ?></span></div>
    </div>
</div>
