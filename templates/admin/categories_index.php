<?php
declare(strict_types=1);
/**
 * دسته‌بندی‌ها — فهرست + فرم ایجاد.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$categories = $__data['categories'] ?? [];
?>
<div class="row">
    <div class="col" style="flex:2">
        <div class="admin-card">
            <h2>دسته‌بندی‌ها</h2>
            <div class="table-wrap" style="border:0;box-shadow:none">
            <table class="data"><thead><tr><th>نام</th><th>slug</th><th class="num">ترتیب</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($categories as $c): ?>
                <tr>
                    <td><strong><?= View::e($c['name']) ?></strong><br><span class="text-muted" style="font-size:12px"><?= View::e($c['description']) ?></span></td>
                    <td class="text-muted"><?= View::e($c['slug']) ?></td>
                    <td class="num"><?= $toFa((string) $c['sort_order']) ?></td>
                    <td><a href="/admin/categories/<?= (int) $c['id'] ?>/edit" class="btn btn-outline btn-sm">ویرایش</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody></table>
            </div>
        </div>
    </div>
    <div class="col" style="flex:1">
        <div class="admin-card">
            <h2>دسته جدید</h2>
            <form action="/admin/categories" method="post">
                <?= Csrf::field() ?>
                <div class="field"><label>نام *</label><input type="text" name="name" required></div>
                <div class="field"><label>دسته والد</label>
                    <select name="parent_id"><option value="0">— هیچ —</option>
                    <?php foreach ($categories as $c): ?><option value="<?= (int) $c['id'] ?>"><?= View::e($c['name']) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="field"><label>ترتیب</label><input type="number" name="sort_order" value="0"></div>
                <div class="field"><label>توضیح</label><input type="text" name="description"></div>
                <button class="btn btn-primary">افزودن</button>
            </form>
        </div>
    </div>
</div>
