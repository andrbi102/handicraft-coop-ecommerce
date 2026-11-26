<?php

declare(strict_types=1);

/**
 * فهرست اعضای تعاونی / نساجان (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$members = $__data['members'] ?? [];
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$money = $__data['money'];
$toFa = $__data['toFa'];

$statusLabel = ['active' => 'فعال', 'inactive' => 'غیرفعال'];
$statusBadge = ['active' => 'pa-badge-success', 'inactive' => 'pa-badge-secondary'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>نساجان تعاونی</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><span>اعضا</span></div>
    </div>
    <div class="pa-d-flex pa-gap-2">
        <a class="pa-btn pa-btn-primary pa-btn-sm" href="/admin/members/create" data-pa-icon="user_plus"> عضو جدید</a>
    </div>
</div>

<div class="pa-card pa-anim-slide-up pa-anim-delay-1">
    <div class="pa-card-header"><h3>فهرست اعضا</h3></div>
    <div class="pa-card-body" style="padding:0">
        <div class="pa-table-wrap">
            <table class="pa-table pa-table-striped pa-table-hover">
                <thead><tr>
                    <th>نام</th><th>تخصص</th><th>منطقه</th><th class="pa-text-end">سهام</th><th>عضویت</th><th>وضعیت</th><th data-no-export></th>
                </tr></thead>
                <tbody>
                <?php if ($members === []): ?>
                    <tr><td colspan="7" class="pa-text-center pa-text-muted" style="padding:30px 0">عضوی ثبت نشده است.</td></tr>
                <?php else: ?>
                    <?php foreach ($members as $m): ?>
                        <tr>
                            <td><a href="/admin/members/<?= (int) $m['id'] ?>"><strong><?= View::e($m['full_name']) ?></strong></a></td>
                            <td><?= View::e($m['craft']) ?></td>
                            <td class="pa-text-muted"><?= View::e($m['region']) ?></td>
                            <td class="pa-text-end"><?= $money((int) $m['share_capital']) ?></td>
                            <td class="pa-text-muted"><?= $toFa((string) mb_substr((string) $m['joined_at'], 0, 10, 'UTF-8')) ?></td>
                            <td><span class="pa-badge <?= View::e($statusBadge[$m['status']] ?? 'pa-badge-secondary') ?>"><?= View::e($statusLabel[$m['status']] ?? $m['status']) ?></span></td>
                            <td data-no-export class="pa-d-flex pa-gap-1">
                                <a class="pa-btn pa-btn-ghost pa-btn-sm" href="/admin/members/<?= (int) $m['id'] ?>" data-pa-icon="eye" data-pa-icon-size="16" aria-label="جزئیات"></a>
                                <a class="pa-btn pa-btn-ghost pa-btn-sm" href="/admin/members/<?= (int) $m['id'] ?>/edit" data-pa-icon="edit" data-pa-icon-size="16" aria-label="ویرایش"></a>
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
