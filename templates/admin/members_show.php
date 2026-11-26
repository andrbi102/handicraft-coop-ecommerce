<?php
declare(strict_types=1);
/**
 * جزئیات عضو تعاونی — محصولات، تسویه‌ها، وضعیت مالی.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$member = $__data['member'] ?? [];
$products = $__data['products'] ?? [];
$settlements = $__data['settlements'] ?? [];
$pending = (int) ($__data['pending'] ?? 0);
$paid = (int) ($__data['paid'] ?? 0);
$productCount = (int) ($__data['productCount'] ?? 0);
$money = $__data['money'];
?>
<div class="toolbar"><h2></h2>
    <div style="display:flex;gap:8px">
        <a href="/admin/members/<?= (int) $member['id'] ?>/edit" class="btn btn-outline btn-sm">ویرایش</a>
        <a href="/admin/members" class="btn btn-outline btn-sm">← بازگشت</a>
    </div>
</div>

<div class="kpi-grid">
    <div class="kpi"><div class="label">سرمایه سهام</div><div class="value"><?= $money((int) $member['share_capital']) ?></div></div>
    <div class="kpi"><div class="label">محصولات فعال</div><div class="value"><?= $toFa((string) $productCount) ?></div></div>
    <div class="kpi accent"><div class="label">تسویه معلق</div><div class="value"><?= $money($pending) ?></div></div>
    <div class="kpi"><div class="label">تسویه پرداخت‌شده</div><div class="value"><?= $money($paid) ?></div></div>
</div>

<div class="row">
    <div class="col" style="flex:1">
        <div class="admin-card">
            <h2>پروفایل</h2>
            <p><strong><?= View::e($member['full_name']) ?></strong></p>
            <p>تخصص: <?= View::e($member['craft']) ?></p>
            <p>منطقه: <?= View::e($member['region']) ?></p>
            <p>عضویت: <?= $toFa((string) substr((string) $member['joined_at'], 0, 10)) ?></p>
            <p>تلفن: <?= View::e($member['phone']) ?></p>
            <?php if (!empty($member['bio'])): ?><p class="text-muted mt-1"><?= nl2br(View::e($member['bio'])) ?></p><?php endif; ?>
        </div>
        <div class="admin-card">
            <h2>محصولات (<?= $toFa((string) count($products)) ?>)</h2>
            <?php if ($products === []): ?><p class="text-muted">محصولی ندارد.</p><?php else: ?>
            <div class="table-wrap" style="border:0;box-shadow:none">
            <table class="data"><thead><tr><th>نام</th><th class="num">قیمت</th><th class="num">موجودی</th></tr></thead>
            <tbody>
            <?php foreach ($products as $p): ?>
                <tr><td><a href="/admin/products/<?= (int) $p['id'] ?>/edit"><?= View::e($p['name']) ?></a></td>
                    <td class="num"><?= $money((int) $p['price']) ?></td><td class="num"><?= $toFa((string) $p['stock']) ?></td></tr>
            <?php endforeach; ?>
            </tbody></table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col" style="flex:1">
        <div class="admin-card">
            <h2>تسویه‌ها</h2>
            <?php if ($settlements === []): ?><p class="text-muted">تسویه‌ای ثبت نشده.</p><?php else: ?>
            <div class="table-wrap" style="border:0;box-shadow:none">
            <table class="data"><thead><tr><th>دوره</th><th>نوع</th><th class="num">مبلغ</th><th>وضعیت</th></tr></thead>
            <tbody>
            <?php foreach ($settlements as $s): ?>
                <tr><td><?= View::e($s['period']) ?></td>
                    <td><?= View::e($s['type']) ?></td>
                    <td class="num"><?= $money((int) $s['amount']) ?></td>
                    <td><span class="status-pill <?= View::e($s['status']) ?>"><?= View::e($s['status'] === 'paid' ? 'پرداخت‌شده' : 'معلق') ?></span></td></tr>
            <?php endforeach; ?>
            </tbody></table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
