<?php

declare(strict_types=1);

/**
 * فهرست مشتریان (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$customers = $__data['customers'] ?? [];
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$toFa = $__data['toFa'];

$statusLabel = ['active' => 'فعال', 'inactive' => 'غیرفعال'];
$statusBadge = ['active' => 'pa-badge-success', 'inactive' => 'pa-badge-secondary'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>مشتریان</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><span>مشتریان</span></div>
    </div>
</div>

<div class="pa-card pa-anim-slide-up pa-anim-delay-1">
    <div class="pa-card-header"><h3>فهرست مشتریان</h3></div>
    <div class="pa-card-body" style="padding:0">
        <div class="pa-table-wrap">
            <table class="pa-table pa-table-striped pa-table-hover">
                <thead><tr>
                    <th>نام</th><th>ایمیل</th><th>تلفن</th><th>عضویت</th><th>وضعیت</th><th data-no-export></th>
                </tr></thead>
                <tbody>
                <?php if ($customers === []): ?>
                    <tr><td colspan="6" class="pa-text-center pa-text-muted" style="padding:30px 0">مشتری‌ای ثبت نشده است.</td></tr>
                <?php else: ?>
                    <?php foreach ($customers as $cu): ?>
                        <tr>
                            <td><strong><?= View::e($cu['name']) ?></strong></td>
                            <td><?= View::e($cu['email']) ?></td>
                            <td class="pa-text-muted"><?= View::e($cu['phone']) ?></td>
                            <td class="pa-text-muted"><?= $toFa((string) mb_substr((string) $cu['created_at'], 0, 10, 'UTF-8')) ?></td>
                            <td><span class="pa-badge <?= View::e($statusBadge[$cu['status']] ?? 'pa-badge-secondary') ?>"><?= View::e($statusLabel[$cu['status']] ?? $cu['status']) ?></span></td>
                            <td data-no-export><a class="pa-btn pa-btn-ghost pa-btn-sm" href="/admin/customers/<?= (int) $cu['id'] ?>" data-pa-icon="eye" data-pa-icon-size="16" aria-label="مشاهده"></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/pagination.php'; ?>
