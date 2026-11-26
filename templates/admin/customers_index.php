<?php
declare(strict_types=1);
/**
 * فهرست مشتریان.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$customers = $__data['customers'] ?? [];
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
?>
<div class="admin-card">
    <div class="table-wrap" style="border:0;box-shadow:none">
    <table class="data">
        <thead><tr><th>نام</th><th>ایمیل</th><th>تلفن</th><th>وضعیت</th><th class="num">عضویت</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($customers as $c): ?>
        <tr>
            <td><strong><?= View::e($c['name']) ?></strong></td>
            <td><?= View::e($c['email']) ?></td>
            <td><?= View::e($c['phone']) ?></td>
            <td><span class="tag <?= $c['status'] === 'active' ? '' : 'out' ?>"><?= View::e($c['status']) ?></span></td>
            <td class="num"><?= $toFa((string) substr((string) $c['created_at'], 0, 10)) ?></td>
            <td><a href="/admin/customers/<?= (int) $c['id'] ?>" class="btn btn-outline btn-sm">مشاهده</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php if ($pages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <?php if ($i === $page): ?><span class="current"><?= $toFa((string) $i) ?></span>
            <?php else: ?><a href="/admin/customers?page=<?= $i ?>"><?= $toFa((string) $i) ?></a><?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
