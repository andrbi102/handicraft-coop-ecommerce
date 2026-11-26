<?php
declare(strict_types=1);
/**
 * فهرست اعضای تعاونی (نساجان).
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$members = $__data['members'] ?? [];
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$money = $__data['money'];
?>
<div class="toolbar"><h2></h2><a href="/admin/members/create" class="btn btn-primary">+ عضو جدید</a></div>
<div class="admin-card">
    <div class="table-wrap" style="border:0;box-shadow:none">
    <table class="data">
        <thead><tr><th>نام</th><th>تخصص</th><th>منطقه</th><th class="num">سهام</th><th>عضویت</th><th>وضعیت</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($members as $m): ?>
        <tr>
            <td><a href="/admin/members/<?= (int) $m['id'] ?>"><strong><?= View::e($m['full_name']) ?></strong></a></td>
            <td><?= View::e($m['craft']) ?></td>
            <td><?= View::e($m['region']) ?></td>
            <td class="num"><?= $money((int) $m['share_capital']) ?></td>
            <td><?= $toFa((string) substr((string) $m['joined_at'], 0, 10)) ?></td>
            <td><span class="tag <?= $m['status'] === 'active' ? '' : 'out' ?>"><?= View::e($m['status']) ?></span></td>
            <td>
                <a href="/admin/members/<?= (int) $m['id'] ?>" class="btn btn-outline btn-sm">جزئیات</a>
                <a href="/admin/members/<?= (int) $m['id'] ?>/edit" class="btn btn-outline btn-sm">ویرایش</a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php if ($pages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <?php if ($i === $page): ?><span class="current"><?= $toFa((string) $i) ?></span>
            <?php else: ?><a href="/admin/members?page=<?= $i ?>"><?= $toFa((string) $i) ?></a><?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
