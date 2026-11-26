<?php
declare(strict_types=1);
/**
 * ویرایش دسته.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$category = $__data['category'] ?? [];
$categories = $__data['categories'] ?? [];
?>
<div class="toolbar"><h2></h2><a href="/admin/categories" class="btn btn-outline btn-sm">← بازگشت</a></div>
<div class="admin-card" style="max-width:560px">
    <form action="/admin/categories/<?= (int) $category['id'] ?>" method="post">
        <?= Csrf::field() ?>
        <div class="field"><label>نام *</label><input type="text" name="name" required value="<?= View::e($category['name']) ?>"></div>
        <div class="field"><label>دسته والد</label>
            <select name="parent_id"><option value="0">— هیچ —</option>
            <?php foreach ($categories as $c): if ((int) $c['id'] === (int) $category['id']) continue; ?>
                <option value="<?= (int) $c['id'] ?>" <?= (int) ($category['parent_id'] ?? 0) === (int) $c['id'] ? 'selected' : '' ?>><?= View::e($c['name']) ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <div class="field"><label>ترتیب</label><input type="number" name="sort_order" value="<?= (int) $category['sort_order'] ?>"></div>
        <div class="field"><label>توضیح</label><input type="text" name="description" value="<?= View::e($category['description']) ?>"></div>
        <button class="btn btn-primary">به‌روزرسانی</button>
    </form>
    <form action="/admin/categories/<?= (int) $category['id'] ?>/delete" method="post" class="mt-2" data-confirm="حذف این دسته؟">
        <?= Csrf::field() ?>
        <button class="btn btn-danger btn-sm">حذف دسته</button>
    </form>
</div>
