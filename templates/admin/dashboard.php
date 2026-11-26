<?php
declare(strict_types=1);
/**
 * داشبورد مدیریت.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$kpi = $__data['kpi'] ?? [];
$recentOrders = $__data['recentOrders'] ?? [];
$dailyRevenue = $__data['dailyRevenue'] ?? [];
$lowStock = $__data['lowStock'] ?? [];
$money = $__data['money'];
$statusLabels = ['pending'=>'در انتظار','paid'=>'پرداخت‌شده','processing'=>'آماده‌سازی','shipped'=>'ارسال‌شده','delivered'=>'تحویل‌شده','cancelled'=>'لغو','refunded'=>'بازگشت'];
$maxRev = 1;
foreach ($dailyRevenue as $d) { $maxRev = max($maxRev, (int) $d['s']); }
?>
<div class="kpi-grid">
    <div class="kpi"><div class="label">درآمد تأییدشده</div><div class="value"><?= $money((int) $kpi['revenue']) ?></div></div>
    <div class="kpi"><div class="label">کل سفارش‌ها</div><div class="value"><?= $toFa((string) $kpi['orders']) ?></div></div>
    <div class="kpi accent"><div class="label">سفارش‌های در انتظار</div><div class="value"><?= $toFa((string) $kpi['pendingOrders']) ?></div></div>
    <div class="kpi"><div class="label">محصولات فعال</div><div class="value"><?= $toFa((string) $kpi['products']) ?></div></div>
    <div class="kpi"><div class="label">نساجان فعال</div><div class="value"><?= $toFa((string) $kpi['members']) ?></div></div>
    <div class="kpi accent"><div class="label">تسویه معلق</div><div class="value"><?= $money((int) $kpi['pendingSettlements']) ?></div></div>
    <div class="kpi"><div class="label">سرمایه سهام</div><div class="value"><?= $money((int) $kpi['shareCapital']) ?></div></div>
</div>

<div class="row">
    <div class="col" style="flex:2">
        <div class="admin-card">
            <h2>فروش ۷ روز اخیر</h2>
            <?php if ($dailyRevenue === []): ?>
                <p class="text-muted">هنوز داده فروشی ثبت نشده است.</p>
            <?php else: ?>
            <div class="bar-chart" style="margin-bottom:30px">
                <?php foreach ($dailyRevenue as $d): $h = (int) round((int) $d['s'] / $maxRev * 100); ?>
                    <div class="bar" style="height:<?= max(2, $h) ?>%" title="<?= View::e($d['d']) ?>: <?= $money((int) $d['s']) ?>"><span><?= $toFa((string) substr((string) $d['d'], 5)) ?></span></div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col" style="flex:1">
        <div class="admin-card">
            <h2>سفارش‌های اخیر</h2>
            <?php if ($recentOrders === []): ?><p class="text-muted">سفارشی نیست.</p><?php else: ?>
            <div class="table-wrap" style="box-shadow:none;border:0">
            <table class="data" style="min-width:0">
                <thead><tr><th>شماره</th><th>وضعیت</th><th class="num">مبلغ</th></tr></thead>
                <tbody>
                <?php foreach ($recentOrders as $o): ?>
                <tr><td><a href="/admin/orders/<?= (int) $o['id'] ?>"><?= View::e($o['order_number']) ?></a></td>
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

<div class="admin-card">
    <h2>موجودی پایین</h2>
    <?php if ($lowStock === []): ?><p class="text-muted">همه محصولات موجودی کافی دارند ✅</p><?php else: ?>
    <div class="table-wrap" style="box-shadow:none;border:0">
    <table class="data"><thead><tr><th>محصول</th><th class="num">موجودی</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($lowStock as $p): ?>
        <tr><td><a href="/admin/products/<?= (int) $p['id'] ?>/edit"><?= View::e($p['name']) ?></a></td>
            <td class="num"><span class="tag <?= (int) $p['stock'] <= 0 ? 'out' : 'low' ?>"><?= $toFa((string) $p['stock']) ?></span></td>
            <td><a href="/admin/products/<?= (int) $p['id'] ?>/edit" class="btn btn-outline btn-sm">ویرایش</a></td></tr>
    <?php endforeach; ?>
    </tbody></table>
    </div>
    <?php endif; ?>
</div>
