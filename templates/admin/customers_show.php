<?php
declare(strict_types=1);
/**
 * جزئیات مشتری + سفارش‌ها.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$customer = $__data['customer'] ?? [];
$orders = $__data['orders'] ?? [];
$money = $__data['money'];
$statusLabels = ['pending'=>'در انتظار','paid'=>'پرداخت‌شده','processing'=>'آماده‌سازی','shipped'=>'ارسال','delivered'=>'تحویل','cancelled'=>'لغو','refunded'=>'بازگشت'];
?>
<div class="toolbar"><h2></h2><a href="/admin/customers" class="btn btn-outline btn-sm">← بازگشت</a></div>
<div class="row">
    <div class="col" style="flex:1">
        <div class="admin-card">
            <h2>اطلاعات مشتری</h2>
            <p><strong><?= View::e($customer['name']) ?></strong></p>
            <p>ایمیل: <?= View::e($customer['email']) ?></p>
            <p>تلفن: <?= View::e($customer['phone']) ?></p>
            <p>عضویت: <?= $toFa((string) substr((string) $customer['created_at'], 0, 10)) ?></p>
        </div>
    </div>
    <div class="col" style="flex:2">
        <div class="admin-card">
            <h2>سفارش‌های مشتری</h2>
            <?php if ($orders === []): ?><p class="text-muted">سفارشی ندارد.</p><?php else: ?>
            <div class="table-wrap" style="border:0;box-shadow:none">
            <table class="data">
                <thead><tr><th>شماره</th><th>تاریخ</th><th>وضعیت</th><th class="num">مبلغ</th></tr></thead>
                <tbody>
                <?php foreach ($orders as $o): ?>
                <tr><td><a href="/admin/orders/<?= (int) $o['id'] ?>"><?= View::e($o['order_number']) ?></a></td>
                    <td><?= $toFa((string) substr((string) $o['created_at'], 0, 10)) ?></td>
                    <td><span class="status-pill <?= View::e($o['status']) ?>"><?= View::e($statusLabels[$o['status']] ?? $o['status']) ?></span></td>
                    <td class="num"><?= $money((int) $o['total']) ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
