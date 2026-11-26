<?php

declare(strict_types=1);

/**
 * فرم ویرایش دسته‌بندی + حذف — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

use App\Support\Form;

/** @var array<string, mixed> $__data */

$category = $__data['category'] ?? [];
$categories = $__data['categories'] ?? [];
$cid = (int) ($category['id'] ?? 0);
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>ویرایش دسته‌بندی</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><a href="/admin/categories">دسته‌بندی</a><span class="pa-sep">›</span><span>ویرایش</span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/admin/categories" data-pa-icon="arrow_right"> بازگشت</a>
</div>

<div class="pa-row">
    <div class="pa-col-6">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>مشخصات دسته</h3></div>
            <div class="pa-card-body">
                <form method="post" action="/admin/categories/<?= $cid ?>" data-pa-validate-form>
                    <?= Csrf::field() ?>
                    <div class="pa-form-group">
                        <label for="c-name">نام *</label>
                        <input id="c-name" class="pa-input" type="text" name="name" data-pa-validate="required" value="<?= Form::value($category, 'name') ?>" required>
                        <div class="pa-form-feedback"></div>
                    </div>
                    <div class="pa-form-group">
                        <label for="c-parent">دستهٔ والد</label>
                        <select id="c-parent" class="pa-select" name="parent_id">
                            <option value="0">— هیچ —</option>
                            <?php foreach ($categories as $cat): ?>
                                <?php if ((int) $cat['id'] === $cid) { continue; } ?>
                                <option value="<?= (int) $cat['id'] ?>" <?= (int) $category['parent_id'] === (int) $cat['id'] ? 'selected' : '' ?>><?= View::e($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="pa-form-group">
                        <label for="c-sort">ترتیب</label>
                        <input id="c-sort" class="pa-input" type="number" name="sort_order" value="<?= Form::value($category, 'sort_order', '0') ?>" min="0">
                    </div>
                    <div class="pa-form-group">
                        <label for="c-desc">توضیح</label>
                        <input id="c-desc" class="pa-input" type="text" name="description" value="<?= Form::value($category, 'description') ?>">
                    </div>
                    <div class="pa-d-flex pa-gap-2">
                        <button type="submit" class="pa-btn pa-btn-primary" data-pa-icon="save"> ذخیره</button>
                        <a class="pa-btn pa-btn-outline" href="/admin/categories">انصراف</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="pa-col-6">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-2">
            <div class="pa-card-header"><h3>منطقهٔ خطر</h3></div>
            <div class="pa-card-body">
                <p class="pa-text-muted pa-fs-sm pa-mb-2">حذف دسته فقط در صورت نداشتن محصول ممکن است.</p>
                <form method="post" action="/admin/categories/<?= $cid ?>/delete" onsubmit="return confirm('حذف این دسته؟')">
                    <?= Csrf::field() ?>
                    <button type="submit" class="pa-btn pa-btn-danger pa-btn-block" data-pa-icon="trash"> حذف دسته</button>
                </form>
            </div>
        </div>
    </div>
</div>
