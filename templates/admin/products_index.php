<?php

declare(strict_types=1);

/**
 * فهرست محصولات (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$products = $__data['products'] ?? [];
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$money = $__data['money'];
$toFa = $__data['toFa'];

$statusLabel = ['active' => 'فعال', 'archived' => 'بایگانی', 'draft' => 'پیش‌نویس'];
$statusBadge = ['active' => 'pa-badge-success', 'archived' => 'pa-badge-secondary', 'draft' => 'pa-badge-warning'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>مدیریت محصولات</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><span>محصولات</span></div>
    </div>
    <div class="pa-d-flex pa-gap-2">
        <a class="pa-btn pa-btn-primary pa-btn-sm" href="/admin/products/create" data-pa-icon="plus"> محصول جدید</a>
    </div>
</div>

<div class="pa-card pa-anim-slide-up pa-anim-delay-1">
    <div class="pa-card-header"><h3>کاتالوگ محصولات</h3>
        <div class="pa-tools"><span class="pa-text-muted pa-fs-sm"><?= $toFa((string) count($products)) ?> مورد در این صفحه</span></div>
    </div>
    <div class="pa-card-body" style="padding:0">
        <div class="pa-table-wrap">
            <table class="pa-table pa-table-striped pa-table-hover">
                <thead><tr>
                    <th>نام</th><th>کد</th><th class="pa-text-end">قیمت</th><th class="pa-text-end">هزینه</th>
                    <th class="pa-text-end">موجودی</th><th>وضعیت</th><th data-no-export></th>
                </tr></thead>
                <tbody>
                <?php if ($products === []): ?>
                    <tr><td colspan="7" class="pa-text-center pa-text-muted" style="padding:30px 0">محصولی ثبت نشده است.</td></tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><a href="/admin/products/<?= (int) $p['id'] ?>/edit"><strong><?= View::e($p['name']) ?></strong></a></td>
                            <td><code><?= View::e($p['sku']) ?></code></td>
                            <td class="pa-text-end"><?= $money((int) $p['price']) ?></td>
                            <td class="pa-text-end pa-text-muted"><?= $money((int) $p['cost']) ?></td>
                            <td class="pa-text-end">
                                <span class="pa-badge <?= (int) $p['stock'] <= 0 ? 'pa-badge-danger' : ((int) $p['stock'] <= 3 ? 'pa-badge-warning' : 'pa-badge-secondary') ?>"><?= $toFa((string) $p['stock']) ?></span>
                            </td>
                            <td><span class="pa-badge <?= View::e($statusBadge[$p['status']] ?? 'pa-badge-secondary') ?>"><?= View::e($statusLabel[$p['status']] ?? $p['status']) ?></span></td>
                            <td data-no-export class="pa-d-flex pa-gap-1">
                                <a class="pa-btn pa-btn-ghost pa-btn-sm" href="/admin/products/<?= (int) $p['id'] ?>/edit" data-pa-icon="edit" data-pa-icon-size="16" aria-label="ویرایش"></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/pagination.php'; ?>
