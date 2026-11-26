<?php
declare(strict_types=1);
/**
 * کدهای تخفیف — فهرست + ایجاد.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$coupons = $__data['coupons'] ?? [];
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$money = $__data['money'];
?>
<div class="row">
    <div class="col" style="flex:2">
        <div class="admin-card">
            <h2>کدهای تخفیف</h2>
            <div class="table-wrap" style="border:0;box-shadow:none">
            <table class="data">
                <thead><tr><th>کد</th><th>نوع</th><th class="num">مقدار</th><th class="num">حداقل</th><th class="num">استفاده</th><th>انقضا</th><th>وضعیت</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($coupons as $c): ?>
                <tr>
                    <td><code><?= View::e($c['code']) ?></code></td>
                    <td><?= View::e($c['type'] === 'percent' ? 'درصدی' : 'ثابت') ?></td>
                    <td class="num"><?= $c['type'] === 'percent' ? $toFa((string) $c['value']) . '٪' : $money((int) $c['value']) ?></td>
                    <td class="num"><?= $money((int) $c['min_subtotal']) ?></td>
                    <td class="num"><?= $toFa((string) $c['used_count']) ?>/<?= $c['usage_limit'] !== null ? $toFa((string) $c['usage_limit']) : '∞' ?></td>
                    <td><?= View::e((string) ($c['expires_at'] ?? '—')) ?></td>
                    <td><span class="tag <?= $c['status'] === 'active' ? '' : 'out' ?>"><?= View::e($c['status']) ?></span></td>
                    <td><a href="/admin/coupons/<?= (int) $c['id'] ?>/edit" class="btn btn-outline btn-sm">ویرایش</a></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php if ($pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <?php if ($i === $page): ?><span class="current"><?= $toFa((string) $i) ?></span>
                    <?php else: ?><a href="/admin/coupons?page=<?= $i ?>"><?= $toFa((string) $i) ?></a><?php endif; ?>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col" style="flex:1">
        <div class="admin-card">
            <h2>کد جدید</h2>
            <form action="/admin/coupons" method="post">
                <?= Csrf::field() ?>
                <div class="field"><label>کد *</label><input type="text" name="code" required minlength="3"></div>
                <div class="field"><label>نوع</label>
                    <select name="type"><option value="fixed">مبلغ ثابت</option><option value="percent">درصدی</option></select>
                </div>
                <div class="field"><label>مقدار *</label><input type="number" name="value" required min="1"></div>
                <div class="field"><label>حداقل مبلغ خرید</label><input type="number" name="min_subtotal" value="0"></div>
                <div class="field"><label>سقف استفاده (۰=نامحدود)</label><input type="number" name="usage_limit" value="0"></div>
                <div class="field"><label>تاریخ انقضا</label><input type="date" name="expires_at"></div>
                <button class="btn btn-primary">ایجاد</button>
            </form>
        </div>
    </div>
</div>
