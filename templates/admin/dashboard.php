<?php

declare(strict_types=1);

/**
 * داشبورد مدیریت — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$kpi = $__data['kpi'] ?? [];
$recentOrders = $__data['recentOrders'] ?? [];
$dailyRevenue = $__data['dailyRevenue'] ?? [];
$lowStock = $__data['lowStock'] ?? [];
$money = $__data['money'];
$num = $__data['num'];
$toFa = $__data['toFa'];

$statusLabels = [
    'pending' => 'در انتظار', 'paid' => 'پرداخت‌شده', 'processing' => 'آماده‌سازی',
    'shipped' => 'ارسال‌شده', 'delivered' => 'تحویل‌شده', 'cancelled' => 'لغو', 'refunded' => 'بازگشت',
];
$statusBadge = [
    'pending' => 'pa-badge-warning', 'paid' => 'pa-badge-info', 'processing' => 'pa-badge-primary',
    'shipped' => 'pa-badge-primary', 'delivered' => 'pa-badge-success', 'cancelled' => 'pa-badge-danger', 'refunded' => 'pa-badge-danger',
];

// ساخت دادهٔ نمودار فروش ۷ روز اخیر
$chartLabels = [];
$chartValues = [];
foreach ($dailyRevenue as $d) {
    $chartLabels[] = $toFa((string) mb_substr((string) $d['d'], 5, null, 'UTF-8'));
    $chartValues[] = (int) $d['s'];
}
$chartData = $dailyRevenue !== []
    ? json_encode(
        ['labels' => $chartLabels, 'series' => [['label' => 'فروش', 'values' => $chartValues]], 'colors' => ['#0d6efd']],
        JSON_UNESCAPED_UNICODE
    )
    : '{}';
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>داشبورد</h1>
        <div class="pa-breadcrumb">
            <a href="/admin">پنل مدیریت</a>
            <span class="pa-sep">›</span>
            <span>داشبورد</span>
        </div>
    </div>
    <div class="pa-d-flex pa-gap-2">
        <a class="pa-btn pa-btn-outline pa-btn-sm" href="/" data-pa-icon="globe"> نمایش فروشگاه</a>
    </div>
</div>

<div class="pa-row pa-mb-2">
    <div class="pa-col-3">
        <div class="pa-small-box pa-bg-primary">
            <div class="pa-inner"><h3><?= $money((int) ($kpi['revenue'] ?? 0)) ?></h3><p>درآمد تأییدشده</p></div>
            <span class="pa-icon-bg" data-pa-icon="coins" data-pa-icon-size="60"></span>
            <a class="pa-footer-link" href="/admin/reports/sales">جزئیات فروش <span data-pa-icon="arrow_left"></span></a>
        </div>
    </div>
    <div class="pa-col-3">
        <div class="pa-small-box pa-bg-info">
            <div class="pa-inner"><h3><?= $toFa((string) ($kpi['orders'] ?? 0)) ?></h3><p>کل سفارش‌ها</p></div>
            <span class="pa-icon-bg" data-pa-icon="shopping_cart" data-pa-icon-size="60"></span>
            <a class="pa-footer-link" href="/admin/orders">همه سفارش‌ها <span data-pa-icon="arrow_left"></span></a>
        </div>
    </div>
    <div class="pa-col-3">
        <div class="pa-small-box pa-bg-warning">
            <div class="pa-inner"><h3><?= $toFa((string) ($kpi['pendingOrders'] ?? 0)) ?></h3><p>سفارش در انتظار</p></div>
            <span class="pa-icon-bg" data-pa-icon="hourglass" data-pa-icon-size="60"></span>
            <a class="pa-footer-link" href="/admin/orders">پیگیری <span data-pa-icon="arrow_left"></span></a>
        </div>
    </div>
    <div class="pa-col-3">
        <div class="pa-small-box pa-bg-success">
            <div class="pa-inner"><h3><?= $toFa((string) ($kpi['products'] ?? 0)) ?></h3><p>محصولات فعال</p></div>
            <span class="pa-icon-bg" data-pa-icon="cube" data-pa-icon-size="60"></span>
            <a class="pa-footer-link" href="/admin/products">کاتالوگ <span data-pa-icon="arrow_left"></span></a>
        </div>
    </div>
</div>

<div class="pa-row pa-mb-2">
    <div class="pa-col-8">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>فروش ۷ روز اخیر</h3></div>
            <div class="pa-card-body">
                <?php if ($dailyRevenue === []): ?>
                    <p class="pa-text-muted pa-text-center" style="padding:40px 0">هنوز داده فروشی ثبت نشده است.</p>
                <?php else: ?>
                    <canvas data-pa-chart="bar" data-pa-chart-data='<?= View::e($chartData) ?>' height="120"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="pa-col-4">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>سفارش‌های اخیر</h3></div>
            <div class="pa-card-body" style="padding:0">
                <?php if ($recentOrders === []): ?>
                    <p class="pa-text-muted pa-text-center" style="padding:24px 0">سفارشی نیست.</p>
                <?php else: ?>
                    <div class="pa-table-wrap">
                        <table class="pa-table pa-table-sm">
                            <thead><tr><th>شماره</th><th>وضعیت</th><th class="pa-text-end">مبلغ</th></tr></thead>
                            <tbody>
                            <?php foreach ($recentOrders as $o): ?>
                                <tr>
                                    <td><a href="/admin/orders/<?= (int) $o['id'] ?>"><?= View::e($o['order_number']) ?></a></td>
                                    <td><span class="pa-badge <?= View::e($statusBadge[$o['status']] ?? 'pa-badge-secondary') ?>"><?= View::e($statusLabels[$o['status']] ?? $o['status']) ?></span></td>
                                    <td class="pa-text-end"><?= $money((int) $o['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="pa-row">
    <div class="pa-col-8">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-2">
            <div class="pa-card-header">
                <h3>موجودی پایین</h3>
                <div class="pa-tools"><a class="pa-btn pa-btn-ghost pa-btn-sm" href="/admin/products" data-pa-icon="cube"> همه محصولات</a></div>
            </div>
            <div class="pa-card-body" style="padding:0">
                <?php if ($lowStock === []): ?>
                    <p class="pa-text-muted pa-text-center" style="padding:30px 0">همه محصولات موجودی کافی دارند ✅</p>
                <?php else: ?>
                    <div class="pa-table-wrap">
                        <table class="pa-table pa-table-striped pa-table-sm">
                            <thead><tr><th>محصول</th><th class="pa-text-end">موجودی</th><th data-no-export></th></tr></thead>
                            <tbody>
                            <?php foreach ($lowStock as $p): ?>
                                <tr>
                                    <td><a href="/admin/products/<?= (int) $p['id'] ?>/edit"><?= View::e($p['name']) ?></a></td>
                                    <td class="pa-text-end"><span class="pa-badge <?= (int) $p['stock'] <= 0 ? 'pa-badge-danger' : 'pa-badge-warning' ?>"><?= $toFa((string) $p['stock']) ?></span></td>
                                    <td data-no-export><a class="pa-btn pa-btn-ghost pa-btn-sm" href="/admin/products/<?= (int) $p['id'] ?>/edit" data-pa-icon="edit" data-pa-icon-size="14"></a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="pa-col-4">
        <div class="pa-info-box pa-mb-1">
            <div class="pa-ib-icon pa-bg-info" data-pa-icon="users_alt"></div>
            <div class="pa-ib-body">
                <p class="pa-ib-text">نساجان فعال</p>
                <p class="pa-ib-number"><?= $toFa((string) ($kpi['members'] ?? 0)) ?></p>
            </div>
        </div>
        <div class="pa-info-box pa-mb-1">
            <div class="pa-ib-icon pa-bg-warning" data-pa-icon="coins"></div>
            <div class="pa-ib-body">
                <p class="pa-ib-text">تسویه معلق</p>
                <p class="pa-ib-number"><?= $money((int) ($kpi['pendingSettlements'] ?? 0)) ?></p>
            </div>
        </div>
        <div class="pa-info-box">
            <div class="pa-ib-icon pa-bg-success" data-pa-icon="briefcase"></div>
            <div class="pa-ib-body">
                <p class="pa-ib-text">سرمایه سهام</p>
                <p class="pa-ib-number"><?= $money((int) ($kpi['shareCapital'] ?? 0)) ?></p>
            </div>
        </div>
    </div>
</div>
