<?php
declare(strict_types=1);
/**
 * گزارش عملکرد اعضای تعاونی.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$rows = $__data['rows'] ?? [];
$totalShare = (int) ($__data['totalShare'] ?? 0);
$totalPending = (int) ($__data['totalPending'] ?? 0);
$totalPaid = (int) ($__data['totalPaid'] ?? 0);
$money = $__data['money'];
?>
<div class="toolbar"><h2></h2><a href="/admin/reports" class="btn btn-outline btn-sm">← بازگشت</a></div>
<div class="kpi-grid">
    <div class="kpi"><div class="label">کل سرمایه سهام</div><div class="value"><?= $money($totalShare) ?></div></div>
    <div class="kpi accent"><div class="label">تسویه معلق کل</div><div class="value"><?= $money($totalPending) ?></div></div>
    <div class="kpi"><div class="label">تسویه پرداخت‌شده کل</div><div class="value"><?= $money($totalPaid) ?></div></div>
</div>
<div class="admin-card">
    <h2>عملکرد اعضا</h2>
    <?php if ($rows === []): ?><p class="text-muted">عضوی ثبت نشده.</p><?php else: ?>
    <div class="table-wrap" style="border:0;box-shadow:none">
    <table class="data">
        <thead><tr><th>عضو</th><th>تخصص</th><th class="num">سهام</th><th class="num">محصولات</th><th class="num">معلق</th><th class="num">پرداخت‌شده</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
        <tr>
            <td><a href="/admin/members/<?= (int) $r['id'] ?>"><?= View::e($r['full_name']) ?></a></td>
            <td><?= View::e($r['craft']) ?></td>
            <td class="num"><?= $money((int) $r['share_capital']) ?></td>
            <td class="num"><?= $toFa((string) $r['product_count']) ?></td>
            <td class="num"><?= $money((int) $r['pending']) ?></td>
            <td class="num"><?= $money((int) $r['paid']) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>
