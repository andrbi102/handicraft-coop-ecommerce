<?php

declare(strict_types=1);

/**
 * پروفایل حساب مشتری — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level private
 */

use App\Support\Form;

/** @var array<string, mixed> $__data */

$profile = $__data['profile'] ?? [];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>پروفایل</h1>
        <div class="pa-breadcrumb"><a href="/">خانه</a><span class="pa-sep">›</span><a href="/account">حساب من</a><span class="pa-sep">›</span><span>پروفایل</span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/account" data-pa-icon="shopping_bag"> سفارش‌های من</a>
</div>

<div class="pa-row">
    <div class="pa-col-6">
        <div class="pa-card">
            <div class="pa-card-header"><h3>اطلاعات حساب</h3></div>
            <div class="pa-card-body">
                <form method="post" action="/account/profile" data-pa-validate-form>
                    <?= Csrf::field() ?>
                    <div class="pa-form-group">
                        <label for="pr-name">نام و نام خانوادگی *</label>
                        <input id="pr-name" class="pa-input" type="text" name="name" data-pa-validate="required" value="<?= Form::value($profile, 'name') ?>" required>
                        <div class="pa-form-feedback"></div>
                    </div>
                    <div class="pa-form-group">
                        <label for="pr-email">ایمیل</label>
                        <input id="pr-email" class="pa-input" type="email" value="<?= Form::value($profile, 'email') ?>" disabled>
                        <div class="pa-hint">ایمیل قابل تغییر نیست.</div>
                    </div>
                    <div class="pa-form-group">
                        <label for="pr-phone">تلفن</label>
                        <input id="pr-phone" class="pa-input" type="tel" name="phone" value="<?= Form::value($profile, 'phone') ?>">
                    </div>
                    <button type="submit" class="pa-btn pa-btn-primary" data-pa-icon="save"> ذخیره تغییرات</button>
                </form>
            </div>
        </div>
    </div>

    <div class="pa-col-6">
        <div class="pa-card">
            <div class="pa-card-header"><h3>تغییر رمز عبور</h3></div>
            <div class="pa-card-body">
                <form method="post" action="/account/password" data-pa-validate-form>
                    <?= Csrf::field() ?>
                    <div class="pa-form-group">
                        <label for="pw-current">رمز فعلی *</label>
                        <div class="pa-input-group">
                            <span class="pa-input-addon" data-pa-icon="lock"></span>
                            <input id="pw-current" class="pa-input" type="password" name="current_password" data-pa-validate="required" required>
                        </div>
                        <div class="pa-form-feedback"></div>
                    </div>
                    <div class="pa-form-group">
                        <label for="pw-new">رمز جدید *</label>
                        <div class="pa-input-group">
                            <span class="pa-input-addon" data-pa-icon="lock"></span>
                            <input id="pw-new" class="pa-input" type="password" name="new_password" data-pa-validate="required" data-pa-minlen="8" required>
                        </div>
                        <div class="pa-form-feedback"></div>
                    </div>
                    <div class="pa-form-group">
                        <label for="pw-confirm">تکرار رمز جدید *</label>
                        <div class="pa-input-group">
                            <span class="pa-input-addon" data-pa-icon="lock"></span>
                            <input id="pw-confirm" class="pa-input" type="password" name="new_password_confirm" data-pa-validate="required" data-pa-minlen="8" required>
                        </div>
                        <div class="pa-form-feedback"></div>
                    </div>
                    <button type="submit" class="pa-btn pa-btn-primary" data-pa-icon="key_alt"> تغییر رمز</button>
                </form>
            </div>
        </div>
    </div>
</div>
