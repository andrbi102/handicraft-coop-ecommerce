<?php
declare(strict_types=1);
/**
 * پروفایل مشتری.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level private
 */
$profile = $__data['profile'] ?? [];
?>
<div class="section-title"><h2>پروفایل</h2><a href="/account">سفارش‌های من ←</a></div>
<div class="checkout-layout">
    <div class="admin-card">
        <h2>اطلاعات حساب</h2>
        <form action="/account/profile" method="post">
            <?= Csrf::field() ?>
            <div class="field"><label>نام و نام خانوادگی</label><input type="text" name="name" required value="<?= View::e($profile['name'] ?? '') ?>"></div>
            <div class="field"><label>ایمیل</label><input type="email" value="<?= View::e($profile['email'] ?? '') ?>" disabled></div>
            <div class="field"><label>تلفن</label><input type="tel" name="phone" value="<?= View::e($profile['phone'] ?? '') ?>"></div>
            <button class="btn btn-primary">ذخیره تغییرات</button>
        </form>
    </div>
    <div class="admin-card">
        <h2>تغییر رمز عبور</h2>
        <form action="/account/password" method="post">
            <?= Csrf::field() ?>
            <div class="field"><label>رمز فعلی</label><input type="password" name="current_password" required></div>
            <div class="field"><label>رمز جدید <span class="text-muted">(حداقل ۸)</span></label><input type="password" name="new_password" required minlength="8"></div>
            <div class="field"><label>تکرار رمز جدید</label><input type="password" name="new_password_confirm" required minlength="8"></div>
            <button class="btn btn-outline">تغییر رمز</button>
        </form>
    </div>
</div>
