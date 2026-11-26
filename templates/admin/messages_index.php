<?php
declare(strict_types=1);
/**
 * پیام‌های دریافتی.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$messages = $__data['messages'] ?? [];
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
?>
<div class="admin-card">
    <div class="table-wrap" style="border:0;box-shadow:none">
    <table class="data">
        <thead><tr><th>نام</th><th>ایمیل</th><th>موضوع</th><th>تاریخ</th><th>وضعیت</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($messages as $m): ?>
        <tr>
            <td><strong><?= View::e($m['name']) ?></strong></td>
            <td><?= View::e($m['email']) ?></td>
            <td><?= View::e($m['subject']) ?></td>
            <td><?= $toFa((string) substr((string) $m['created_at'], 0, 10)) ?></td>
            <td><?php if ((int) $m['is_read'] === 0): ?><span class="tag low">جدید</span><?php else: ?><span class="text-muted">خوانده‌شده</span><?php endif; ?></td>
            <td><a href="/admin/messages/<?= (int) $m['id'] ?>" class="btn btn-outline btn-sm">مشاهده</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php if ($pages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <?php if ($i === $page): ?><span class="current"><?= $toFa((string) $i) ?></span>
            <?php else: ?><a href="/admin/messages?page=<?= $i ?>"><?= $toFa((string) $i) ?></a><?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
