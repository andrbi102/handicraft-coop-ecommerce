<?php

declare(strict_types=1);

/**
 * فرم ورود — کارت احراز هویت PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

use App\Support\Form;

/** @var array<string, mixed> $__data */

$return = (string) ($__data['return'] ?? '');
$siteName = $__data['siteName'];
?>
<h1>ورود به حساب</h1>
<p class="pa-sub">به <?= View::e((string) $siteName) ?> خوش آمدید.</p>

<form method="post" action="/login" data-pa-validate-form>
    <?= Csrf::field() ?>
    <?php if ($return !== ''): ?><input type="hidden" name="return" value="<?= View::e($return) ?>"><?php endif; ?>

    <div class="pa-form-group">
        <label for="email">ایمیل</label>
        <div class="pa-input-group">
            <span class="pa-input-addon" data-pa-icon="envelope"></span>
            <input id="email" class="pa-input" type="email" name="email" data-pa-validate="required" required autofocus>
        </div>
        <div class="pa-form-feedback"></div>
    </div>

    <div class="pa-form-group">
        <label for="password">رمز عبور</label>
        <div class="pa-input-group">
            <span class="pa-input-addon" data-pa-icon="lock"></span>
            <input id="password" class="pa-input" type="password" name="password" data-pa-validate="required" required>
        </div>
        <div class="pa-form-feedback"></div>
    </div>

    <div class="pa-form-row" style="align-items:center;justify-content:space-between">
        <div class="pa-form-check"><input type="checkbox" id="remember" name="remember" value="1"><label for="remember">مرا به خاطر بسپار</label></div>
    </div>

    <button type="submit" class="pa-btn pa-btn-primary pa-btn-block pa-btn-lg" data-pa-icon="log_in"> ورود</button>
</form>

<p class="pa-text-center pa-text-muted pa-fs-sm pa-mt-2">حساب ندارید؟ <a href="/register">ثبت‌نام</a></p>
