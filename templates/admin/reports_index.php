<?php

declare(strict_types=1);

/**
 * نمای گزارش‌ها (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$revenue = (int) ($__data['revenue'] ?? 0);
$ordersCount = (int) ($__data['ordersCount'] ?? 0);
$dailyRevenue = $__data['dailyRevenue'] ?? [];
$money = $__data['money'];
$toFa = $__data['toFa'];

// دادهٔ نمودار ۱۴ روزه
$labels = [];
$values = [];
foreach ($dailyRevenue as $d) {
    $labels[] = $toFa((string) mb_substr((string) $d['d'], 5, null, 'UTF-8'));
    $values[] = (int) $d['s'];
}
$chartData = $dailyRevenue !== []
    ? json_encode(['labels' => $labels, 'series' => [['label' => 'درآمد', 'values' => $values]], 'colors' => ['#198754']], JSON_UNESCAPED_UNICODE)
    : '{}';
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>گزارش‌ها</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><span>گزارش‌ها</span></div>
    </div>
</div>

<div class="pa-row pa-mb-2">
    <div class="pa-col-6">
        <div class="pa-small-box pa-bg-success">
            <div class="pa-inner"><h3><?= $money($revenue) ?></h3><p>درآمد تأییدشده</p></div>
            <span class="pa-icon-bg" data-pa-icon="wallet" data-pa-icon-size="60"></span>
            <a class="pa-footer-link" href="/admin/reports/sales">جزئیات فروش <span data-pa-icon="arrow_left"></span></a>
        </div>
    </div>
    <div class="pa-col-6">
        <div class="pa-small-box pa-bg-info">
            <div class="pa-inner"><h3><?= $toFa((string) $ordersCount) ?></h3><p>کل سفارش‌ها</p></div>
            <span class="pa-icon-bg" data-pa-icon="shopping_cart" data-pa-icon-size="60"></span>
            <a class="pa-footer-link" href="/admin/orders">همه سفارش‌ها <span data-pa-icon="arrow_left"></span></a>
        </div>
    </div>
</div>

<div class="pa-row">
    <div class="pa-col-8">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>درآمد ۱۴ روز اخیر</h3></div>
            <div class="pa-card-body">
                <?php if ($dailyRevenue === []): ?>
                    <p class="pa-text-muted pa-text-center" style="padding:40px 0">داده فروشی ثبت نشده است.</p>
                <?php else: ?>
                    <canvas data-pa-chart="bar" data-pa-chart-data='<?= View::e($chartData) ?>' height="140"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="pa-col-4">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>گزارش‌های تفصیلی</h3></div>
            <div class="pa-card-body pa-d-flex pa-gap-1" style="flex-direction:column">
                <a class="pa-btn pa-btn-outline pa-btn-block" href="/admin/reports/sales" data-pa-icon="chartline"> گزارش فروش</a>
                <a class="pa-btn pa-btn-outline pa-btn-block" href="/admin/reports/inventory" data-pa-icon="package_alt"> گزارش موجودی</a>
                <a class="pa-btn pa-btn-outline pa-btn-block" href="/admin/reports/members" data-pa-icon="users_alt"> عملکرد اعضا</a>
            </div>
        </div>
    </div>
</div>
