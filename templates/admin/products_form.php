<?php

declare(strict_types=1);

/**
 * فرم محصول (ایجاد/ویرایش) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

use App\Support\Form;

/** @var array<string, mixed> $__data */

$product = $__data['product'] ?? null;
$categories = $__data['categories'] ?? [];
$members = $__data['members'] ?? [];
$money = $__data['money'];
$isEdit = $product !== null;
$pid = $isEdit ? (int) $product['id'] : 0;

$action = $isEdit ? '/admin/products/' . $pid : '/admin/products';
$title = $isEdit ? 'ویرایش محصول' : 'محصول جدید';
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1><?= View::e($title) ?></h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><a href="/admin/products">محصولات</a><span class="pa-sep">›</span><span><?= View::e($title) ?></span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/admin/products" data-pa-icon="arrow_right"> بازگشت</a>
</div>

<div class="pa-row">
    <div class="pa-col-8">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>مشخصات محصول</h3></div>
            <div class="pa-card-body">
                <form method="post" action="<?= View::e($action) ?>" data-pa-validate-form>
                    <?= Csrf::field() ?>
                    <div class="pa-form-group">
                        <label for="p-name">نام محصول *</label>
                        <input id="p-name" class="pa-input" type="text" name="name" data-pa-validate="required" data-pa-minlen="3" value="<?= Form::value($product, 'name') ?>" required>
                        <div class="pa-form-feedback"></div>
                    </div>

                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="p-cat">دسته‌بندی *</label>
                            <select id="p-cat" class="pa-select" name="category_id" data-pa-validate="required" required>
                                <option value="">— انتخاب —</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= (int) $cat['id'] ?>" <?= $isEdit && (int) $product['category_id'] === (int) $cat['id'] ? 'selected' : '' ?>><?= View::e($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="pa-form-feedback"></div>
                        </div>
                        <div class="pa-form-group">
                            <label for="p-member">عضو سازنده (نساج)</label>
                            <select id="p-member" class="pa-select" name="member_id">
                                <option value="0">— بدون عضو —</option>
                                <?php foreach ($members as $m): ?>
                                    <option value="<?= (int) $m['id'] ?>" <?= $isEdit && (int) $product['member_id'] === (int) $m['id'] ? 'selected' : '' ?>><?= View::e($m['full_name']) ?> — <?= View::e($m['craft']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="p-price">قیمت فروش (تومان) *</label>
                            <input id="p-price" class="pa-input" type="text" inputmode="numeric" name="price" data-pa-validate="required" value="<?= $isEdit ? $money((int) $product['price']) : '' ?>" required>
                            <div class="pa-form-feedback"></div>
                        </div>
                        <div class="pa-form-group">
                            <label for="p-cost">هزینه ساخت / مزد نساجی</label>
                            <input id="p-cost" class="pa-input" type="text" inputmode="numeric" name="cost" value="<?= $isEdit ? $money((int) $product['cost']) : '' ?>">
                            <div class="pa-hint">برای محاسبهٔ حاشیه و تسویهٔ نساج</div>
                        </div>
                    </div>

                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="p-stock">موجودی</label>
                            <input id="p-stock" class="pa-input" type="number" name="stock" value="<?= Form::value($product, 'stock', '0') ?>" min="0">
                        </div>
                        <div class="pa-form-group">
                            <label for="p-sku">کد محصول (SKU)</label>
                            <input id="p-sku" class="pa-input" type="text" name="sku" value="<?= Form::value($product, 'sku') ?>">
                        </div>
                    </div>

                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="p-weight">وزن (گرم)</label>
                            <input id="p-weight" class="pa-input" type="number" name="weight_grams" value="<?= Form::value($product, 'weight_grams', '0') ?>" min="0">
                        </div>
                        <div class="pa-form-group">
                            <label for="p-status">وضعیت</label>
                            <select id="p-status" class="pa-select" name="status">
                                <?php foreach (['active' => 'فعال', 'draft' => 'پیش‌نویس', 'archived' => 'بایگانی'] as $k => $lbl): ?>
                                    <option value="<?= View::e($k) ?>" <?= $isEdit && $product['status'] === $k ? 'selected' : '' ?>><?= View::e($lbl) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="p-material">متریال</label>
                            <input id="p-material" class="pa-input" type="text" name="material" value="<?= Form::value($product, 'material') ?>">
                        </div>
                        <div class="pa-form-group">
                            <label for="p-dim">ابعاد</label>
                            <input id="p-dim" class="pa-input" type="text" name="dimensions" value="<?= Form::value($product, 'dimensions') ?>">
                        </div>
                    </div>

                    <div class="pa-form-check pa-mb-2">
                        <input type="checkbox" id="p-featured" name="is_featured" value="1" <?= $isEdit && (int) $product['is_featured'] === 1 ? 'checked' : '' ?>>
                        <label for="p-featured">محصول ویژه — نمایش در صفحهٔ اصلی</label>
                    </div>

                    <div class="pa-form-group">
                        <label for="p-desc">توضیحات</label>
                        <textarea id="p-desc" class="pa-textarea" name="description" rows="4"><?= Form::value($product, 'description') ?></textarea>
                    </div>

                    <div class="pa-d-flex pa-gap-2">
                        <button type="submit" class="pa-btn pa-btn-primary" data-pa-icon="save"> ذخیره</button>
                        <a class="pa-btn pa-btn-outline" href="/admin/products">انصراف</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($isEdit): ?>
    <div class="pa-col-4">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1 pa-mb-2">
            <div class="pa-card-header"><h3>تغییر سریع موجودی</h3></div>
            <div class="pa-card-body">
                <form method="post" action="/admin/products/<?= $pid ?>/stock">
                    <?= Csrf::field() ?>
                    <div class="pa-form-group">
                        <label for="p-delta">تغییر موجودی</label>
                        <input id="p-delta" class="pa-input" type="number" name="delta" value="0">
                        <div class="pa-hint">مثبت = افزودن، منفی = کسر</div>
                    </div>
                    <button type="submit" class="pa-btn pa-btn-outline pa-btn-block" data-pa-icon="zap"> اعمال</button>
                </form>
            </div>
        </div>

        <div class="pa-card pa-anim-slide-up pa-anim-delay-2">
            <div class="pa-card-header"><h3>منطقهٔ خطر</h3></div>
            <div class="pa-card-body">
                <form method="post" action="/admin/products/<?= $pid ?>/delete" onsubmit="return confirm('این محصول بایگانی شود؟')">
                    <?= Csrf::field() ?>
                    <button type="submit" class="pa-btn pa-btn-danger pa-btn-block" data-pa-icon="archive_alt"> بایگانی محصول</button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
