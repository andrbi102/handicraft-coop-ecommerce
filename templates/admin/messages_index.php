<?php

declare(strict_types=1);

/**
 * فهرست پیام‌های تماس (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$messages = $__data['messages'] ?? [];
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$toFa = $__data['toFa'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>پیام‌های تماس</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><span>پیام‌ها</span></div>
    </div>
</div>

<div class="pa-card pa-anim-slide-up pa-anim-delay-1">
    <div class="pa-card-header"><h3>صندوق پیام‌ها</h3></div>
    <div class="pa-card-body" style="padding:0">
        <div class="pa-table-wrap">
            <table class="pa-table pa-table-striped pa-table-hover">
                <thead><tr>
                    <th>نام</th><th>ایمیل</th><th>موضوع</th><th>تاریخ</th><th>وضعیت</th><th data-no-export></th>
                </tr></thead>
                <tbody>
                <?php if ($messages === []): ?>
                    <tr><td colspan="6" class="pa-text-center pa-text-muted" style="padding:30px 0">پیامی دریافت نشده است.</td></tr>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <tr<?= (int) $msg['is_read'] === 0 ? ' style="font-weight:600"' : '' ?>>
                            <td><strong><?= View::e($msg['name']) ?></strong></td>
                            <td><?= View::e($msg['email']) ?></td>
                            <td><?= View::e($msg['subject']) ?></td>
                            <td class="pa-text-muted"><?= $toFa((string) mb_substr((string) $msg['created_at'], 0, 10, 'UTF-8')) ?></td>
                            <td>
                                <?php if ((int) $msg['is_read'] === 0): ?>
                                    <span class="pa-badge pa-badge-danger">جدید</span>
                                <?php else: ?>
                                    <span class="pa-text-muted pa-fs-sm">خوانده‌شده</span>
                                <?php endif; ?>
                            </td>
                            <td data-no-export><a class="pa-btn pa-btn-ghost pa-btn-sm" href="/admin/messages/<?= (int) $msg['id'] ?>" data-pa-icon="eye" data-pa-icon-size="16" aria-label="مشاهده"></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/pagination.php'; ?>
