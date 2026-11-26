<?php

declare(strict_types=1);

/**
 * فهرست سفارش‌ها (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$orders = $__data['orders'] ?? [];
$statusFilter = (string) ($__data['status'] ?? '');
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$statusLabels = $__data['statusLabels'] ?? [];
$money = $__data['money'];
$toFa = $__data['toFa'];

$statusBadge = [
    'pending' => 'pa-badge-warning', 'paid' => 'pa-badge-info', 'processing' => 'pa-badge-primary',
    'shipped' => 'pa-badge-primary', 'delivered' => 'pa-badge-success', 'cancelled' => 'pa-badge-danger', 'refunded' => 'pa-badge-danger',
];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>سفارش‌ها</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><span>سفارش‌ها</span></div>
    </div>
    <form method="get" class="pa-d-flex pa-gap-1 pa-align-center">
        <select name="status" class="pa-select pa-btn-sm" onchange="this.form.submit()">
            <option value="" <?= $statusFilter === '' ? 'selected' : '' ?>>همه وضعیت‌ها</option>
            <?php foreach ($statusLabels as $key => $label): ?>
                <option value="<?= View::e($key) ?>" <?= $statusFilter === $key ? 'selected' : '' ?>><?= View::e($label) ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="pa-card pa-anim-slide-up pa-anim-delay-1">
    <div class="pa-card-header"><h3>فهرست سفارش‌ها</h3></div>
    <div class="pa-card-body" style="padding:0">
        <div class="pa-table-wrap">
            <table class="pa-table pa-table-striped pa-table-hover">
                <thead><tr>
                    <th>شماره</th><th>مشتری</th><th>تاریخ</th><th>وضعیت</th><th class="pa-text-end">مبلغ</th><th data-no-export></th>
                </tr></thead>
                <tbody>
                <?php if ($orders === []): ?>
                    <tr><td colspan="6" class="pa-text-center pa-text-muted" style="padding:30px 0">سفارشی یافت نشد.</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><a href="/admin/orders/<?= (int) $o['id'] ?>"><strong><?= View::e($o['order_number']) ?></strong></a></td>
                            <td><?= View::e($o['shipping_name']) ?></td>
                            <td class="pa-text-muted"><?= $toFa((string) mb_substr((string) $o['created_at'], 0, 10, 'UTF-8')) ?></td>
                            <td><span class="pa-badge <?= View::e($statusBadge[$o['status']] ?? 'pa-badge-secondary') ?>"><?= View::e($statusLabels[$o['status']] ?? $o['status']) ?></span></td>
                            <td class="pa-text-end"><?= $money((int) $o['total']) ?></td>
                            <td data-no-export><a class="pa-btn pa-btn-ghost pa-btn-sm" href="/admin/orders/<?= (int) $o['id'] ?>" data-pa-icon="eye" data-pa-icon-size="16" aria-label="مشاهده"></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$query = $statusFilter !== '' ? ['status' => $statusFilter] : [];
include __DIR__ . '/../partials/pagination.php';
?>
