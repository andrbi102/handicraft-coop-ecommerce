<?php

declare(strict_types=1);

/**
 * گزارش موجودی انبار (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$lowStock = $__data['lowStock'] ?? [];
$threshold = (int) ($__data['threshold'] ?? 3);
$totalActive = (int) ($__data['totalActive'] ?? 0);
$money = $__data['money'];
$toFa = $__data['toFa'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>گزارش موجودی انبار</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><a href="/admin/reports">گزارش‌ها</a><span class="pa-sep">›</span><span>موجودی</span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/admin/reports" data-pa-icon="arrow_right"> بازگشت</a>
</div>

<div class="pa-row pa-mb-2">
    <div class="pa-col-6">
        <div class="pa-small-box pa-bg-info">
            <div class="pa-inner"><h3><?= $toFa((string) $totalActive) ?></h3><p>محصولات فعال</p></div>
            <span class="pa-icon-bg" data-pa-icon="cube" data-pa-icon-size="60"></span>
        </div>
    </div>
    <div class="pa-col-6">
        <div class="pa-small-box pa-bg-warning">
            <div class="pa-inner"><h3><?= $toFa((string) count($lowStock)) ?></h3><p>موجودی پایین (≤ <?= $toFa((string) $threshold) ?>)</p></div>
            <span class="pa-icon-bg" data-pa-icon="package_alt" data-pa-icon-size="60"></span>
        </div>
    </div>
</div>

<div class="pa-card pa-anim-slide-up pa-anim-delay-1">
    <div class="pa-card-header"><h3>محصولات با موجودی پایین</h3></div>
    <div class="pa-card-body" style="padding:0">
        <div class="pa-table-wrap">
            <table class="pa-table pa-table-striped pa-table-hover">
                <thead><tr><th>محصول</th><th>کد</th><th class="pa-text-end">موجودی</th><th class="pa-text-end">قیمت</th><th data-no-export></th></tr></thead>
                <tbody>
                <?php if ($lowStock === []): ?>
                    <tr><td colspan="5" class="pa-text-center pa-text-muted" style="padding:24px">همه محصولات موجودی کافی دارند ✅</td></tr>
                <?php else: ?>
                    <?php foreach ($lowStock as $p): ?>
                        <tr>
                            <td><?= View::e($p['name']) ?></td>
                            <td><code class="pa-text-muted"><?= View::e($p['sku']) ?></code></td>
                            <td class="pa-text-end"><span class="pa-badge <?= (int) $p['stock'] <= 0 ? 'pa-badge-danger' : 'pa-badge-warning' ?>"><?= $toFa((string) $p['stock']) ?></span></td>
                            <td class="pa-text-end"><?= $money((int) $p['price']) ?></td>
                            <td data-no-export><a class="pa-btn pa-btn-ghost pa-btn-sm" href="/admin/products/<?= (int) $p['id'] ?>/edit" data-pa-icon="edit" data-pa-icon-size="16"></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
