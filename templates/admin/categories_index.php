<?php

declare(strict_types=1);

/**
 * فهرست دسته‌بندی‌ها + فرم افزودن (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$categories = $__data['categories'] ?? [];
$toFa = $__data['toFa'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>دسته‌بندی محصولات</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><span>دسته‌بندی</span></div>
    </div>
</div>

<div class="pa-row">
    <div class="pa-col-8">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>دسته‌ها</h3></div>
            <div class="pa-card-body" style="padding:0">
                <div class="pa-table-wrap">
                    <table class="pa-table pa-table-striped pa-table-hover">
                        <thead><tr><th>نام</th><th>slug</th><th class="pa-text-end">ترتیب</th><th data-no-export></th></tr></thead>
                        <tbody>
                        <?php if ($categories === []): ?>
                            <tr><td colspan="4" class="pa-text-center pa-text-muted" style="padding:30px 0">دسته‌ای ثبت نشده است.</td></tr>
                        <?php else: ?>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td>
                                        <strong><?= View::e($cat['name']) ?></strong>
                                        <?php if (!empty($cat['description'])): ?><br><small class="pa-text-muted"><?= View::e($cat['description']) ?></small><?php endif; ?>
                                    </td>
                                    <td><code class="pa-text-muted"><?= View::e($cat['slug']) ?></code></td>
                                    <td class="pa-text-end"><?= $toFa((string) $cat['sort_order']) ?></td>
                                    <td data-no-export><a class="pa-btn pa-btn-ghost pa-btn-sm" href="/admin/categories/<?= (int) $cat['id'] ?>/edit" data-pa-icon="edit" data-pa-icon-size="16" aria-label="ویرایش"></a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="pa-col-4">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>دستهٔ جدید</h3></div>
            <div class="pa-card-body">
                <form method="post" action="/admin/categories" data-pa-validate-form>
                    <?= Csrf::field() ?>
                    <div class="pa-form-group">
                        <label for="cat-name">نام دسته</label>
                        <input id="cat-name" class="pa-input" type="text" name="name" data-pa-validate="required" required>
                        <div class="pa-form-feedback"></div>
                    </div>
                    <div class="pa-form-group">
                        <label for="cat-parent">دستهٔ والد</label>
                        <select id="cat-parent" class="pa-select" name="parent_id">
                            <option value="0">— هیچ —</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= (int) $cat['id'] ?>"><?= View::e($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="pa-form-group">
                        <label for="cat-sort">ترتیب نمایش</label>
                        <input id="cat-sort" class="pa-input" type="number" name="sort_order" value="0" min="0">
                    </div>
                    <div class="pa-form-group">
                        <label for="cat-desc">توضیح (اختیاری)</label>
                        <input id="cat-desc" class="pa-input" type="text" name="description">
                    </div>
                    <button type="submit" class="pa-btn pa-btn-primary pa-btn-block" data-pa-icon="plus"> افزودن دسته</button>
                </form>
            </div>
        </div>
    </div>
</div>
