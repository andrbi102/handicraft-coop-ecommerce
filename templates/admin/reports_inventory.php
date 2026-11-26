<?php
declare(strict_types=1);
/**
 * گزارش موجودی.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$lowStock = $__data['lowStock'] ?? [];
$threshold = (int) ($__data['threshold'] ?? 3);
$totalActive = (int) ($__data['totalActive'] ?? 0);
$money = $__data['money'];
?>
<div class="toolbar"><h2></h2><a href="/admin/reports" class="btn btn-outline btn-sm">← بازگشت</a></div>
<div class="kpi-grid">
    <div class="kpi"><div class="label">محصولات فعال</div><div class="value"><?= $toFa((string) $totalActive) ?></div></div>
    <div class="kpi accent"><div class="label">موجودی پایین (≤ <?= $toFa((string) $threshold) ?>)</div><div class="value"><?= $toFa((string) count($lowStock)) ?></div></div>
</div>
<div class="admin-card">
    <h2>محصولات با موجودی پایین</h2>
    <?php if ($lowStock === []): ?><p class="text-muted">همه محصولات موجودی کافی دارند ✅</p><?php else: ?>
    <div class="table-wrap" style="border:0;box-shadow:none">
    <table class="data"><thead><tr><th>محصول</th><th>کد</th><th class="num">موجودی</th><th class="num">قیمت</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($lowStock as $p): ?>
        <tr><td><?= View::e($p['name']) ?></td><td class="text-muted"><?= View::e($p['sku']) ?></td>
            <td class="num"><span class="tag <?= (int) $p['stock'] <= 0 ? 'out' : 'low' ?>"><?= $toFa((string) $p['stock']) ?></span></td>
            <td class="num"><?= $money((int) $p['price']) ?></td>
            <td><a href="/admin/products/<?= (int) $p['id'] ?>/edit" class="btn btn-outline btn-sm">ویرایش</a></td></tr>
    <?php endforeach; ?>
    </tbody></table>
    </div>
    <?php endif; ?>
</div>
