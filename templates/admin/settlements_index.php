<?php

declare(strict_types=1);

/**
 * تسویه مالی اعضا (مدیریت/حسابدار) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level finance
 */

/** @var array<string, mixed> $__data */

$rows = $__data['rows'] ?? [];
$memberMap = $__data['memberMap'] ?? [];
$statusFilter = (string) ($__data['status'] ?? '');
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$totalPending = (int) ($__data['totalPending'] ?? 0);
$totalPaid = (int) ($__data['totalPaid'] ?? 0);
$money = $__data['money'];
$toFa = $__data['toFa'];

$statusLabel = ['pending' => 'معلق', 'paid' => 'پرداخت‌شده'];
$statusBadge = ['pending' => 'pa-badge-warning', 'paid' => 'pa-badge-success'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>تسویه مالی اعضا</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><span>تسویه</span></div>
    </div>
    <form method="get" class="pa-d-flex pa-gap-1 pa-align-center">
        <select name="status" class="pa-select pa-btn-sm" onchange="this.form.submit()">
            <option value="" <?= $statusFilter === '' ? 'selected' : '' ?>>همه</option>
            <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>معلق</option>
            <option value="paid" <?= $statusFilter === 'paid' ? 'selected' : '' ?>>پرداخت‌شده</option>
        </select>
    </form>
</div>

<div class="pa-row pa-mb-2">
    <div class="pa-col-6">
        <div class="pa-small-box pa-bg-warning">
            <div class="pa-inner"><h3><?= $money($totalPending) ?></h3><p>مجموع معلق</p></div>
            <span class="pa-icon-bg" data-pa-icon="hourglass" data-pa-icon-size="60"></span>
        </div>
    </div>
    <div class="pa-col-6">
        <div class="pa-small-box pa-bg-success">
            <div class="pa-inner"><h3><?= $money($totalPaid) ?></h3><p>مجموع پرداخت‌شده</p></div>
            <span class="pa-icon-bg" data-pa-icon="check_circle" data-pa-icon-size="60"></span>
        </div>
    </div>
</div>

<div class="pa-card pa-anim-slide-up pa-anim-delay-1">
    <div class="pa-card-header"><h3>صورت‌روی تسویه</h3></div>
    <div class="pa-card-body" style="padding:0">
        <div class="pa-table-wrap">
            <table class="pa-table pa-table-striped pa-table-hover">
                <thead><tr>
                    <th>عضو</th><th>دوره</th><th>نوع</th><th class="pa-text-end">مبلغ</th><th>وضعیت</th><th data-no-export></th>
                </tr></thead>
                <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="6" class="pa-text-center pa-text-muted" style="padding:30px 0">رکورد تسویه‌ای نیست.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $s): ?>
                        <tr>
                            <td><a href="/admin/members/<?= (int) $s['member_id'] ?>"><?= View::e($memberMap[$s['member_id']] ?? ('#' . (int) $s['member_id'])) ?></a></td>
                            <td><?= View::e($s['period']) ?></td>
                            <td class="pa-text-muted"><?= View::e($s['type']) ?></td>
                            <td class="pa-text-end"><?= $money((int) $s['amount']) ?></td>
                            <td><span class="pa-badge <?= View::e($statusBadge[$s['status']] ?? 'pa-badge-secondary') ?>"><?= View::e($statusLabel[$s['status']] ?? $s['status']) ?></span></td>
                            <td data-no-export>
                                <?php if ($s['status'] === 'pending'): ?>
                                    <form method="post" action="/admin/settlements/<?= (int) $s['id'] ?>/pay" style="display:inline">
                                        <?= Csrf::field() ?>
                                        <button type="submit" class="pa-btn pa-btn-success pa-btn-sm" data-pa-icon="check"> تأیید پرداخت</button>
                                    </form>
                                <?php else: ?>
                                    <span class="pa-text-muted pa-fs-sm"><?= $s['settled_at'] ? $toFa((string) mb_substr((string) $s['settled_at'], 0, 10, 'UTF-8')) : '—' ?></span>
                                <?php endif; ?>
                            </td>
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
