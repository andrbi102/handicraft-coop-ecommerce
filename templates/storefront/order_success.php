<?php
declare(strict_types=1);
/**
 * تأیید سفارش.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level private
 */
$order = $__data['order'] ?? [];
$items = $__data['items'] ?? [];
$money = $__data['money'];
?>
<div class="admin-card text-center" style="max-width:640px;margin:0 auto">
    <div style="font-size:48px">✅</div>
    <h1>سفارش شما ثبت شد</h1>
    <p class="mt-1">شماره سفارش: <strong><?= View::e($order['order_number']) ?></strong></p>
    <p class="text-muted">جزئیات تحویل و پرداخت به‌زودی از طریق پنل مدیریت تأیید و اطلاع‌رسانی می‌شود. می‌توانید وضعیت سفارش را در «حساب من» پیگیری کنید.</p>
</div>

<div class="admin-card" style="max-width:640px;margin:16px auto 0">
    <h2>خلاصه سفارش</h2>
    <div class="table-wrap">
        <table class="data">
            <thead><tr><th>کالا</th><th class="num">تعداد</th><th class="num">مبلغ</th></tr></thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= View::e($item['product_name']) ?></td>
                    <td class="num"><?= $toFa((string) $item['quantity']) ?></td>
                    <td class="num"><?= $money((int) $item['subtotal']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="summary-card mt-2" style="position:static">
        <div class="row-line"><span>جمع کالاها</span><span><?= $money((int) $order['subtotal']) ?></span></div>
        <div class="row-line"><span>تخفیف</span><span>-<?= $money((int) $order['discount']) ?></span></div>
        <div class="row-line"><span>ارسال</span><span><?= (int) $order['shipping_cost'] === 0 ? 'رایگان' : $money((int) $order['shipping_cost']) ?></span></div>
        <div class="total row-line"><span>قابل پرداخت</span><span><?= $money((int) $order['total']) ?></span></div>
    </div>
    <p class="mt-2 text-center"><a href="/account" class="btn btn-outline">سفارش‌های من</a> <a href="/products" class="btn btn-primary">ادامه خرید</a></p>
</div>
