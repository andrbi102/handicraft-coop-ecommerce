<?php
declare(strict_types=1);
/**
 * جزئیات سفارش (مدیریت) + تغییر وضعیت.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$order = $__data['order'] ?? [];
$items = $__data['items'] ?? [];
$statusLabels = $__data['statusLabels'] ?? [];
$money = $__data['money'];
?>
<div class="toolbar"><h2></h2><a href="/admin/orders" class="btn btn-outline btn-sm">← بازگشت</a></div>
<div class="row">
    <div class="col" style="flex:2">
        <div class="admin-card">
            <h2>اقلام سفارش</h2>
            <div class="table-wrap" style="border:0;box-shadow:none">
            <table class="data">
                <thead><tr><th>کالا</th><th class="num">تعداد</th><th class="num">مبلغ واحد</th><th class="num">مبلغ کل</th><th>سازنده</th></tr></thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= View::e($item['product_name']) ?></td>
                    <td class="num"><?= $toFa((string) $item['quantity']) ?></td>
                    <td class="num"><?= $money((int) $item['price']) ?></td>
                    <td class="num"><?= $money((int) $item['subtotal']) ?></td>
                    <td class="text-muted"><?= $item['member_id'] !== null ? ('#' . $toFa((string) $item['member_id'])) : '—' ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <div class="summary-card" style="position:static;max-width:300px;margin-inline:auto;margin-top:12px">
                <div class="row-line"><span>جمع کالاها</span><span><?= $money((int) $order['subtotal']) ?></span></div>
                <div class="row-line"><span>تخفیف</span><span>-<?= $money((int) $order['discount']) ?></span></div>
                <div class="row-line"><span>ارسال</span><span><?= (int) $order['shipping_cost'] === 0 ? 'رایگان' : $money((int) $order['shipping_cost']) ?></span></div>
                <div class="total row-line"><span>کل</span><span><?= $money((int) $order['total']) ?></span></div>
            </div>
        </div>
    </div>
    <div class="col" style="flex:1">
        <div class="admin-card">
            <h2>اطلاعات تحویل</h2>
            <p><strong><?= View::e($order['shipping_name']) ?></strong></p>
            <p><?= View::e($order['shipping_address']) ?></p>
            <p><?= View::e($order['shipping_city']) ?> — کد پستی: <?= View::e($order['shipping_postal_code']) ?></p>
            <p>تلفن: <?= View::e($order['shipping_phone']) ?></p>
            <?php if (!empty($order['notes'])): ?><p class="text-muted">یادداشت: <?= View::e($order['notes']) ?></p><?php endif; ?>
            <?php if (!empty($order['coupon_code'])): ?><p>کد تخفیف: <code><?= View::e($order['coupon_code']) ?></code></p><?php endif; ?>
        </div>
        <div class="admin-card">
            <h2>وضعیت سفارش</h2>
            <p>وضعیت فعلی: <span class="status-pill <?= View::e($order['status']) ?>"><?= View::e($statusLabels[$order['status']] ?? $order['status']) ?></span></p>
            <form action="/admin/orders/<?= (int) $order['id'] ?>/status" method="post" class="mt-2">
                <?= Csrf::field() ?>
                <select name="status">
                    <?php foreach ($statusLabels as $k => $lbl): ?>
                        <option value="<?= $k ?>" <?= $order['status'] === $k ? 'selected' : '' ?>><?= $lbl ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-primary btn-block mt-2">تغییر وضعیت</button>
            </form>
            <?php if ($order['status'] !== 'delivered' && $order['status'] !== 'cancelled'): ?>
            <form action="/admin/orders/<?= (int) $order['id'] ?>/deliver" method="post" class="mt-2" data-confirm="تحویل نهایی؟ تسویه‌های اعضا ایجاد می‌شود.">
                <?= Csrf::field() ?>
                <button class="btn btn-accent btn-block">تحویل و ایجاد تسویه</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
