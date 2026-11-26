<?php

declare(strict_types=1);

/**
 * فرم ثبت‌نام — کارت احراز هویت PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

use App\Support\Form;

/** @var array<string, mixed> $__data */
?>
<h1>ثبت‌نام</h1>
<p class="pa-sub">حساب کاربری جدید بسازید.</p>

<form method="post" action="/register" data-pa-validate-form>
    <?= Csrf::field() ?>

    <div class="pa-form-group">
        <label for="r-name">نام و نام خانوادگی</label>
        <div class="pa-input-group">
            <span class="pa-input-addon" data-pa-icon="user"></span>
            <input id="r-name" class="pa-input" type="text" name="name" data-pa-validate="required" data-pa-minlen="3" required autofocus>
        </div>
        <div class="pa-form-feedback"></div>
    </div>

    <div class="pa-form-group">
        <label for="r-email">ایمیل</label>
        <div class="pa-input-group">
            <span class="pa-input-addon" data-pa-icon="envelope"></span>
            <input id="r-email" class="pa-input" type="email" name="email" data-pa-validate="required" required>
        </div>
        <div class="pa-form-feedback"></div>
    </div>

    <div class="pa-form-group">
        <label for="r-phone">تلفن</label>
        <div class="pa-input-group">
            <span class="pa-input-addon" data-pa-icon="phone"></span>
            <input id="r-phone" class="pa-input" type="tel" name="phone">
        </div>
    </div>

    <div class="pa-form-group">
        <label for="r-password">رمز عبور (حداقل ۸ نویسه)</label>
        <div class="pa-input-group">
            <span class="pa-input-addon" data-pa-icon="lock"></span>
            <input id="r-password" class="pa-input" type="password" name="password" data-pa-validate="required" data-pa-minlen="8" required>
        </div>
        <div class="pa-form-feedback"></div>
    </div>

    <div class="pa-form-group">
        <label for="r-confirm">تکرار رمز عبور</label>
        <div class="pa-input-group">
            <span class="pa-input-addon" data-pa-icon="lock"></span>
            <input id="r-confirm" class="pa-input" type="password" name="password_confirm" data-pa-validate="required" data-pa-minlen="8" required>
        </div>
        <div class="pa-form-feedback"></div>
    </div>

    <button type="submit" class="pa-btn pa-btn-primary pa-btn-block pa-btn-lg" data-pa-icon="user_plus"> ثبت‌نام</button>
</form>

<p class="pa-text-center pa-text-muted pa-fs-sm pa-mt-2">حساب دارید؟ <a href="/login">ورود</a></p>
