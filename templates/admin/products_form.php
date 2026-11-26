<?php
declare(strict_types=1);
/**
 * فرم محصول (ایجاد/ویرایش).
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$product = $__data['product'] ?? null;
$categories = $__data['categories'] ?? [];
$members = $__data['members'] ?? [];
$isEdit = $product !== null;
$action = $isEdit ? '/admin/products/' . (int) $product['id'] : '/admin/products';
$money = $__data['money'];
?>
<div class="toolbar"><h2></h2><a href="/admin/products" class="btn btn-outline btn-sm">← بازگشت</a></div>
<div class="admin-card">
    <form action="<?= $action ?>" method="post">
        <?= Csrf::field() ?>
        <div class="form-grid">
            <div class="field" style="grid-column:1/-1"><label>نام محصول *</label>
                <input type="text" name="name" required value="<?= \App\Support\Form::value($product, 'name') ?>"></div>
            <div class="field"><label>دسته‌بندی *</label>
                <select name="category_id" required>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int) $c['id'] ?>" <?= $isEdit && (int) $product['category_id'] === (int) $c['id'] ? 'selected' : '' ?>><?= View::e($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field"><label>عضو سازنده (نساج)</label>
                <select name="member_id">
                    <option value="0">— بدون عضو —</option>
                    <?php foreach ($members as $m): ?>
                        <option value="<?= (int) $m['id'] ?>" <?= $isEdit && (int) ($product['member_id'] ?? 0) === (int) $m['id'] ? 'selected' : '' ?>><?= View::e($m['full_name']) ?> — <?= View::e($m['craft']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field"><label>قیمت فروش (تومان) *</label>
                <input type="text" name="price" inputmode="numeric" required value="<?= $isEdit ? $money((int) $product['price']) : '' ?>"></div>
            <div class="field"><label>هزینه ساخت / مزد نساجی (تومان)</label>
                <input type="text" name="cost" inputmode="numeric" value="<?= $isEdit ? $money((int) $product['cost']) : '' ?>">
                <div class="hint">برای محاسبه تسویه عضو استفاده می‌شود.</div></div>
            <div class="field"><label>موجودی</label>
                <input type="number" name="stock" value="<?= \App\Support\Form::value($product, 'stock', '0') ?>"></div>
            <div class="field"><label>کد محصول (SKU)</label>
                <input type="text" name="sku" value="<?= \App\Support\Form::value($product, 'sku') ?>"></div>
            <div class="field"><label>وزن (گرم)</label>
                <input type="number" name="weight_grams" value="<?= \App\Support\Form::value($product, 'weight_grams', '0') ?>"></div>
            <div class="field"><label>متریال</label>
                <input type="text" name="material" value="<?= \App\Support\Form::value($product, 'material') ?>"></div>
            <div class="field"><label>ابعاد</label>
                <input type="text" name="dimensions" value="<?= \App\Support\Form::value($product, 'dimensions') ?>"></div>
            <div class="field"><label>وضعیت</label>
                <select name="status">
                    <?php foreach (['active'=>'فعال','draft'=>'پیش‌نویس','archived'=>'بایگانی'] as $k => $lbl): ?>
                        <option value="<?= $k ?>" <?= $isEdit && $product['status'] === $k ? 'selected' : '' ?>><?= $lbl ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field"><label>محصول ویژه</label>
                <label style="font-weight:400"><input type="checkbox" name="is_featured" value="1" <?= $isEdit && (int) $product['is_featured'] === 1 ? 'checked' : '' ?>> نمایش در صفحه اصلی</label>
            </div>
            <div class="field" style="grid-column:1/-1"><label>توضیحات</label>
                <textarea name="description"><?= \App\Support\Form::value($product, 'description') ?></textarea></div>
        </div>
        <button class="btn btn-primary"><?= $isEdit ? 'به‌روزرسانی محصول' : 'ایجاد محصول' ?></button>
    </form>
</div>
<?php if ($isEdit): ?>
<div class="admin-card">
    <h2>تنظیم سریع موجودی</h2>
    <form action="/admin/products/<?= (int) $product['id'] ?>/stock" method="post" style="display:flex;gap:8px;align-items:center">
        <?= Csrf::field() ?>
        <label>تغییر موجودی:</label>
        <input type="number" name="delta" value="0" style="width:100px">
        <button class="btn btn-outline btn-sm">اعمال</button>
        <span class="text-muted">(مثبت = افزودن، منفی = کسر)</span>
    </form>
    <form action="/admin/products/<?= (int) $product['id'] ?>/delete" method="post" class="mt-2" data-confirm="این محصول بایگانی شود؟">
        <?= Csrf::field() ?>
        <button class="btn btn-danger btn-sm">بایگانی محصول</button>
    </form>
</div>
<?php endif; ?>
