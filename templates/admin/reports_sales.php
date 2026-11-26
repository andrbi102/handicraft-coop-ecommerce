<?php
declare(strict_types=1);
/**
 * گزارش فروش.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$topProducts = $__data['topProducts'] ?? [];
$byStatus = $__data['byStatus'] ?? [];
$money = $__data['money'];
$statusLabels = ['pending'=>'در انتظار','paid'=>'پرداخت‌شده','processing'=>'آماده‌سازی','shipped'=>'ارسال','delivered'=>'تحویل','cancelled'=>'لغو','refunded'=>'بازگشت'];
?>
<div class="toolbar"><h2></h2><a href="/admin/reports" class="btn btn-outline btn-sm">← بازگشت</a></div>
<div class="admin-card">
    <h2>پرفروش‌ترین محصولات</h2>
    <?php if ($topProducts === []): ?><p class="text-muted">داده فروشی نیست.</p><?php else: ?>
    <div class="table-wrap" style="border:0;box-shadow:none">
    <table class="data"><thead><tr><th>محصول</th><th class="num">تعداد فروخته‌شده</th><th class="num">درآمد</th></tr></thead>
    <tbody>
    <?php foreach ($topProducts as $row): ?>
        <tr><td><?= View::e($row['product_name']) ?></td>
            <td class="num"><?= $toFa((string) $row['qty']) ?></td>
            <td class="num"><?= $money((int) $row['revenue']) ?></td></tr>
    <?php endforeach; ?>
    </tbody></table>
    </div>
    <?php endif; ?>
</div>
<div class="admin-card">
    <h2>فروش بر اساس وضعیت سفارش</h2>
    <?php if ($byStatus === []): ?><p class="text-muted">داده‌ای نیست.</p><?php else: ?>
    <div class="table-wrap" style="border:0;box-shadow:none">
    <table class="data"><thead><tr><th>وضعیت</th><th class="num">تعداد</th><th class="num">مبلغ</th></tr></thead>
    <tbody>
    <?php foreach ($byStatus as $row): ?>
        <tr><td><span class="status-pill <?= View::e($row['status']) ?>"><?= View::e($statusLabels[$row['status']] ?? $row['status']) ?></span></td>
            <td class="num"><?= $toFa((string) $row['cnt']) ?></td>
            <td class="num"><?= $money((int) $row['total']) ?></td></tr>
    <?php endforeach; ?>
    </tbody></table>
    </div>
    <?php endif; ?>
</div>
