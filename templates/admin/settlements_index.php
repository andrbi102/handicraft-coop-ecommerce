<?php
declare(strict_types=1);
/**
 * تسویه‌های تعاونی — فهرست + تأیید پرداخت.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level finance
 */
$rows = $__data['rows'] ?? [];
$memberMap = $__data['memberMap'] ?? [];
$status = $__data['status'] ?? '';
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$totalPending = (int) ($__data['totalPending'] ?? 0);
$totalPaid = (int) ($__data['totalPaid'] ?? 0);
$money = $__data['money'];
?>
<div class="kpi-grid">
    <div class="kpi accent"><div class="label">مجموع معلق</div><div class="value"><?= $money($totalPending) ?></div></div>
    <div class="kpi"><div class="label">مجموع پرداخت‌شده</div><div class="value"><?= $money($totalPaid) ?></div></div>
</div>
<div class="toolbar">
    <form method="get" class="filters">
        <select name="status" onchange="this.form.submit()">
            <option value="">همه</option>
            <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>معلق</option>
            <option value="paid" <?= $status === 'paid' ? 'selected' : '' ?>>پرداخت‌شده</option>
        </select>
    </form>
</div>
<div class="admin-card">
    <div class="table-wrap" style="border:0;box-shadow:none">
    <table class="data">
        <thead><tr><th>عضو</th><th>دوره</th><th>نوع</th><th class="num">مبلغ</th><th>وضعیت</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($rows as $s): $mid = (int) $s['member_id']; ?>
        <tr>
            <td><a href="/admin/members/<?= $mid ?>"><?= View::e($memberMap[$mid] ?? ('#' . $mid)) ?></a></td>
            <td><?= View::e($s['period']) ?></td>
            <td><?= View::e($s['type']) ?></td>
            <td class="num"><?= $money((int) $s['amount']) ?></td>
            <td><span class="status-pill <?= View::e($s['status']) ?>"><?= View::e($s['status'] === 'paid' ? 'پرداخت‌شده' : 'معلق') ?></span></td>
            <td>
                <?php if ($s['status'] === 'pending'): ?>
                <form action="/admin/settlements/<?= (int) $s['id'] ?>/pay" method="post" data-confirm="تأیید پرداخت این تسویه؟">
                    <?= Csrf::field() ?>
                    <button class="btn btn-primary btn-sm">تأیید پرداخت</button>
                </form>
                <?php else: ?>
                    <span class="text-muted" style="font-size:12px"><?= View::e((string) ($s['settled_at'] ?? '')) ?></span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php if ($pages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): $q = $status !== '' ? '&status=' . rawurlencode($status) : ''; ?>
            <?php if ($i === $page): ?><span class="current"><?= $toFa((string) $i) ?></span>
            <?php else: ?><a href="/admin/settlements?page=<?= $i ?><?= $q ?>"><?= $toFa((string) $i) ?></a><?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
