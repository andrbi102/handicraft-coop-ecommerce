<?php
declare(strict_types=1);
/**
 * ویرایش کد تخفیف.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$coupon = $__data['coupon'] ?? [];
?>
<div class="toolbar"><h2></h2><a href="/admin/coupons" class="btn btn-outline btn-sm">← بازگشت</a></div>
<div class="admin-card" style="max-width:520px">
    <form action="/admin/coupons/<?= (int) $coupon['id'] ?>" method="post">
        <?= Csrf::field() ?>
        <div class="field"><label>کد *</label><input type="text" name="code" required value="<?= View::e($coupon['code']) ?>"></div>
        <div class="field"><label>نوع</label>
            <select name="type">
                <option value="fixed" <?= $coupon['type'] === 'fixed' ? 'selected' : '' ?>>مبلغ ثابت</option>
                <option value="percent" <?= $coupon['type'] === 'percent' ? 'selected' : '' ?>>درصدی</option>
            </select>
        </div>
        <div class="field"><label>مقدار *</label><input type="number" name="value" required min="1" value="<?= (int) $coupon['value'] ?>"></div>
        <div class="field"><label>حداقل مبلغ خرید</label><input type="number" name="min_subtotal" value="<?= (int) $coupon['min_subtotal'] ?>"></div>
        <div class="field"><label>سقف استفاده (۰=نامحدود)</label><input type="number" name="usage_limit" value="<?= $coupon['usage_limit'] !== null ? (int) $coupon['usage_limit'] : 0 ?>"></div>
        <div class="field"><label>تاریخ انقضا</label><input type="date" name="expires_at" value="<?= View::e((string) ($coupon['expires_at'] ?? '')) ?>"></div>
        <div class="field"><label>وضعیت</label>
            <select name="status">
                <option value="active" <?= $coupon['status'] === 'active' ? 'selected' : '' ?>>فعال</option>
                <option value="inactive" <?= $coupon['status'] === 'inactive' ? 'selected' : '' ?>>غیرفعال</option>
            </select>
        </div>
        <button class="btn btn-primary">به‌روزرسانی</button>
    </form>
</div>
