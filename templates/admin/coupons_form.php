<?php

declare(strict_types=1);

/**
 * فرم ویرایش کد تخفیف — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

use App\Support\Form;

/** @var array<string, mixed> $__data */

$coupon = $__data['coupon'] ?? [];
$money = $__data['money'];
$cid = (int) ($coupon['id'] ?? 0);
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>ویرایش کد تخفیف</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><a href="/admin/coupons">تخفیف</a><span class="pa-sep">›</span><span>ویرایش</span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/admin/coupons" data-pa-icon="arrow_right"> بازگشت</a>
</div>

<div class="pa-row">
    <div class="pa-col-6">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>مشخصات کد</h3></div>
            <div class="pa-card-body">
                <form method="post" action="/admin/coupons/<?= $cid ?>" data-pa-validate-form>
                    <?= Csrf::field() ?>
                    <div class="pa-form-group">
                        <label for="cp-code">کد *</label>
                        <input id="cp-code" class="pa-input" type="text" name="code" data-pa-validate="required" data-pa-minlen="3" value="<?= Form::value($coupon, 'code') ?>" required>
                        <div class="pa-form-feedback"></div>
                    </div>
                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="cp-type">نوع</label>
                            <select id="cp-type" class="pa-select" name="type">
                                <?php foreach (['fixed' => 'مبلغ ثابت', 'percent' => 'درصدی'] as $k => $lbl): ?>
                                    <option value="<?= View::e($k) ?>" <?= $coupon['type'] === $k ? 'selected' : '' ?>><?= View::e($lbl) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="pa-form-group">
                            <label for="cp-value">مقدار *</label>
                            <input id="cp-value" class="pa-input" type="number" name="value" min="1" data-pa-validate="required" value="<?= Form::value($coupon, 'value') ?>" required>
                        </div>
                    </div>
                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="cp-min">حداقل مبلغ خرید</label>
                            <input id="cp-min" class="pa-input" type="number" name="min_subtotal" value="<?= Form::value($coupon, 'min_subtotal', '0') ?>" min="0">
                        </div>
                        <div class="pa-form-group">
                            <label for="cp-limit">سقف استفاده (۰=نامحدود)</label>
                            <input id="cp-limit" class="pa-input" type="number" name="usage_limit" value="<?= Form::value($coupon, 'usage_limit', '0') ?>" min="0">
                        </div>
                    </div>
                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="cp-exp">تاریخ انقضا</label>
                            <input id="cp-exp" class="pa-input" type="date" name="expires_at" value="<?= Form::value($coupon, 'expires_at') ?>">
                        </div>
                        <div class="pa-form-group">
                            <label for="cp-status">وضعیت</label>
                            <select id="cp-status" class="pa-select" name="status">
                                <?php foreach (['active' => 'فعال', 'inactive' => 'غیرفعال'] as $k => $lbl): ?>
                                    <option value="<?= View::e($k) ?>" <?= $coupon['status'] === $k ? 'selected' : '' ?>><?= View::e($lbl) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="pa-d-flex pa-gap-2">
                        <button type="submit" class="pa-btn pa-btn-primary" data-pa-icon="save"> ذخیره</button>
                        <a class="pa-btn pa-btn-outline" href="/admin/coupons">انصراف</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
