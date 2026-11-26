<?php
declare(strict_types=1);
/**
 * فرم ورود.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level public
 */
$return = $__data['return'] ?? '';
?>
<div class="form-card">
    <h1>ورود به حساب</h1>
    <p class="text-muted" style="font-size:14px">برای پیگیری سفارش‌ها و تسویحه حساب وارد شوید.</p>
    <form action="/login" method="post" class="mt-2">
        <?= Csrf::field() ?>
        <?php if ($return !== ''): ?><input type="hidden" name="return" value="<?= View::e($return) ?>"><?php endif; ?>
        <div class="field"><label>ایمیل</label><input type="email" name="email" required autofocus></div>
        <div class="field"><label>رمز عبور</label><input type="password" name="password" required></div>
        <button class="btn btn-primary btn-block">ورود</button>
    </form>
    <p class="text-center mt-2 text-muted">حساب ندارید؟ <a href="/register">ثبت‌نام کنید</a></p>
</div>
