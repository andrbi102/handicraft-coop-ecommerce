<?php

declare(strict_types=1);

/**
 * گزارش فروش (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$topProducts = $__data['topProducts'] ?? [];
$byStatus = $__data['byStatus'] ?? [];
$money = $__data['money'];
$toFa = $__data['toFa'];

$statusLabels = [
    'pending' => 'در انتظار', 'paid' => 'پرداخت‌شده', 'processing' => 'آماده‌سازی',
    'shipped' => 'ارسال', 'delivered' => 'تحویل', 'cancelled' => 'لغو', 'refunded' => 'بازگشت',
];
$statusBadge = [
    'pending' => 'pa-badge-warning', 'paid' => 'pa-badge-info', 'processing' => 'pa-badge-primary',
    'shipped' => 'pa-badge-primary', 'delivered' => 'pa-badge-success', 'cancelled' => 'pa-badge-danger', 'refunded' => 'pa-badge-danger',
];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>گزارش فروش</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><a href="/admin/reports">گزارش‌ها</a><span class="pa-sep">›</span><span>فروش</span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/admin/reports" data-pa-icon="arrow_right"> بازگشت</a>
</div>

<div class="pa-row">
    <div class="pa-col-7">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1 pa-mb-2">
            <div class="pa-card-header"><h3>پرفروش‌ترین محصولات</h3></div>
            <div class="pa-card-body" style="padding:0">
                <div class="pa-table-wrap">
                    <table class="pa-table pa-table-striped">
                        <thead><tr><th>محصول</th><th class="pa-text-end">تعداد فروخته‌شده</th><th class="pa-text-end">درآمد</th></tr></thead>
                        <tbody>
                        <?php if ($topProducts === []): ?>
                            <tr><td colspan="3" class="pa-text-center pa-text-muted" style="padding:24px">داده‌ای نیست.</td></tr>
                        <?php else: ?>
                            <?php foreach ($topProducts as $tp): ?>
                                <tr><td><?= View::e($tp['product_name']) ?></td><td class="pa-text-end"><?= $toFa((string) $tp['qty']) ?></td><td class="pa-text-end"><?= $money((int) $tp['revenue']) ?></td></tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="pa-col-5">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>فروش بر اساس وضعیت</h3></div>
            <div class="pa-card-body" style="padding:0">
                <div class="pa-table-wrap">
                    <table class="pa-table pa-table-striped">
                        <thead><tr><th>وضعیت</th><th class="pa-text-end">تعداد</th><th class="pa-text-end">مبلغ</th></tr></thead>
                        <tbody>
                        <?php if ($byStatus === []): ?>
                            <tr><td colspan="3" class="pa-text-center pa-text-muted" style="padding:24px">داده‌ای نیست.</td></tr>
                        <?php else: ?>
                            <?php foreach ($byStatus as $bs): ?>
                                <tr>
                                    <td><span class="pa-badge <?= View::e($statusBadge[$bs['status']] ?? 'pa-badge-secondary') ?>"><?= View::e($statusLabels[$bs['status']] ?? $bs['status']) ?></span></td>
                                    <td class="pa-text-end"><?= $toFa((string) $bs['cnt']) ?></td>
                                    <td class="pa-text-end"><?= $money((int) $bs['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
