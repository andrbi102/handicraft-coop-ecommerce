<?php
declare(strict_types=1);
/**
 * فرم ثبت‌نام.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level public
 */
?>
<div class="form-card">
    <h1>ایجاد حساب</h1>
    <p class="text-muted" style="font-size:14px">حساب کاربری برای خرید از تعاونی.</p>
    <form action="/register" method="post" class="mt-2">
        <?= Csrf::field() ?>
        <div class="field"><label>نام و نام خانوادگی</label><input type="text" name="name" required minlength="3" maxlength="80"></div>
        <div class="field"><label>ایمیل</label><input type="email" name="email" required></div>
        <div class="field"><label>تلفن</label><input type="tel" name="phone"></div>
        <div class="field"><label>رمز عبور <span class="text-muted">(حداقل ۸ نویسه)</span></label><input type="password" name="password" required minlength="8"></div>
        <div class="field"><label>تکرار رمز عبور</label><input type="password" name="password_confirm" required minlength="8"></div>
        <button class="btn btn-primary btn-block">ثبت‌نام</button>
    </form>
    <p class="text-center mt-2 text-muted">از قبل حساب دارید؟ <a href="/login">ورود</a></p>
</div>
