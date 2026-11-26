<?php
declare(strict_types=1);
/**
 * فهرست محصولات (مدیریت).
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$products = $__data['products'] ?? [];
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$money = $__data['money'];
?>
<div class="toolbar">
    <h2></h2>
    <a href="/admin/products/create" class="btn btn-primary">+ محصول جدید</a>
</div>
<div class="admin-card">
    <div class="table-wrap" style="border:0;box-shadow:none">
    <table class="data">
        <thead><tr><th>نام</th><th>کد</th><th class="num">قیمت</th><th class="num">هزینه</th><th class="num">موجودی</th><th>وضعیت</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><a href="/admin/products/<?= (int) $p['id'] ?>/edit"><?= View::e($p['name']) ?></a></td>
            <td class="text-muted"><?= View::e($p['sku']) ?></td>
            <td class="num"><?= $money((int) $p['price']) ?></td>
            <td class="num"><?= $money((int) $p['cost']) ?></td>
            <td class="num"><?= $toFa((string) $p['stock']) ?></td>
            <td><span class="tag <?= $p['status'] === 'active' ? '' : ($p['status'] === 'archived' ? 'out' : 'low') ?>"><?= View::e($p['status']) ?></span></td>
            <td>
                <a href="/admin/products/<?= (int) $p['id'] ?>/edit" class="btn btn-outline btn-sm">ویرایش</a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php if ($pages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <?php if ($i === $page): ?><span class="current"><?= $toFa((string) $i) ?></span>
            <?php else: ?><a href="/admin/products?page=<?= $i ?>"><?= $toFa((string) $i) ?></a><?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
