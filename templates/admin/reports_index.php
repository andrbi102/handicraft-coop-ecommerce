<?php
declare(strict_types=1);
/**
 * گزارش‌ها — نمای کلی.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$revenue = (int) ($__data['revenue'] ?? 0);
$ordersCount = (int) ($__data['ordersCount'] ?? 0);
$dailyRevenue = $__data['dailyRevenue'] ?? [];
$money = $__data['money'];
$maxRev = 1;
foreach ($dailyRevenue as $d) { $maxRev = max($maxRev, (int) $d['s']); }
?>
<div class="kpi-grid">
    <div class="kpi"><div class="label">درآمد تأییدشده</div><div class="value"><?= $money($revenue) ?></div></div>
    <div class="kpi"><div class="label">کل سفارش‌ها</div><div class="value"><?= $toFa((string) $ordersCount) ?></div></div>
</div>
<div class="row">
    <div class="col admin-card" style="flex:2">
        <h2>فروش ۱۴ روز اخیر</h2>
        <?php if ($dailyRevenue === []): ?><p class="text-muted">داده‌ای نیست.</p><?php else: ?>
        <div class="bar-chart" style="margin-bottom:30px">
            <?php foreach ($dailyRevenue as $d): $h = (int) round((int) $d['s'] / $maxRev * 100); ?>
                <div class="bar" style="height:<?= max(2, $h) ?>%" title="<?= View::e($d['d']) ?>: <?= $money((int) $d['s']) ?>"><span><?= $toFa((string) substr((string) $d['d'], 5)) ?></span></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="col admin-card" style="flex:1">
        <h2>گزارش‌های تفصیلی</h2>
        <p><a href="/admin/reports/sales" class="btn btn-outline btn-block">گزارش فروش</a></p>
        <p class="mt-1"><a href="/admin/reports/inventory" class="btn btn-outline btn-block">گزارش موجودی</a></p>
        <p class="mt-1"><a href="/admin/reports/members" class="btn btn-outline btn-block">عملکرد اعضا</a></p>
    </div>
</div>
