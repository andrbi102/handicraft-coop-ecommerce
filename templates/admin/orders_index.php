<?php
declare(strict_types=1);
/**
 * فهرست سفارش‌ها (مدیریت).
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$orders = $__data['orders'] ?? [];
$status = $__data['status'] ?? '';
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$statusLabels = $__data['statusLabels'] ?? [];
$money = $__data['money'];
?>
<div class="toolbar">
    <form method="get" class="filters">
        <select name="status" onchange="this.form.submit()">
            <option value="">همه وضعیت‌ها</option>
            <?php foreach ($statusLabels as $k => $lbl): ?>
                <option value="<?= $k ?>" <?= $status === $k ? 'selected' : '' ?>><?= $lbl ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>
<div class="admin-card">
    <div class="table-wrap" style="border:0;box-shadow:none">
    <table class="data">
        <thead><tr><th>شماره</th><th>مشتری</th><th>تاریخ</th><th>وضعیت</th><th class="num">مبلغ</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($orders as $o): ?>
        <tr>
            <td><a href="/admin/orders/<?= (int) $o['id'] ?>"><?= View::e($o['order_number']) ?></a></td>
            <td><?= View::e($o['shipping_name']) ?></td>
            <td><?= $toFa((string) substr((string) $o['created_at'], 0, 10)) ?></td>
            <td><span class="status-pill <?= View::e($o['status']) ?>"><?= View::e($statusLabels[$o['status']] ?? $o['status']) ?></span></td>
            <td class="num"><?= $money((int) $o['total']) ?></td>
            <td><a href="/admin/orders/<?= (int) $o['id'] ?>" class="btn btn-outline btn-sm">مشاهده</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php if ($pages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): $q = $status !== '' ? '&status=' . rawurlencode($status) : ''; ?>
            <?php if ($i === $page): ?><span class="current"><?= $toFa((string) $i) ?></span>
            <?php else: ?><a href="/admin/orders?page=<?= $i ?><?= $q ?>"><?= $toFa((string) $i) ?></a><?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
