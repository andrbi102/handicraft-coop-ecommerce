<?php

declare(strict_types=1);

/**
 * فرم تنظیمات فروشگاه — پوستهٔ PAdminLTE.
 *
 * کلیدها مسطح‌اند (با SettingsController::KEYS هم‌خوان)؛ گروه‌بندی فقط
 * نمایشی است و nameها دست‌نخورده می‌مانند.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

use App\Support\Form;

/** @var array<string, mixed> $__data */

$settings = $__data['settings'] ?? [];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>تنظیمات فروشگاه</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><span>تنظیمات</span></div>
    </div>
</div>

<form method="post" action="/admin/settings" data-pa-validate-form>
    <?= Csrf::field() ?>

    <div class="pa-card pa-anim-slide-up pa-mb-2">
        <div class="pa-card-header"><h3>اطلاعات عمومی</h3></div>
        <div class="pa-card-body">
            <div class="pa-form-row">
                <div class="pa-form-group">
                    <label for="s-site">نام فروشگاه</label>
                    <input id="s-site" class="pa-input" type="text" name="site_name" value="<?= Form::value($settings, 'site_name') ?>">
                </div>
                <div class="pa-form-group">
                    <label for="s-tag">شعار</label>
                    <input id="s-tag" class="pa-input" type="text" name="tagline" value="<?= Form::value($settings, 'tagline') ?>">
                </div>
            </div>
            <div class="pa-form-group">
                <label for="s-announce">پیام اعلان بالای صفحهٔ اصلی</label>
                <textarea id="s-announce" class="pa-textarea" name="announcement" rows="2"><?= Form::value($settings, 'announcement') ?></textarea>
            </div>
            <div class="pa-form-group">
                <label for="s-footer">متن فوتر</label>
                <input id="s-footer" class="pa-input" type="text" name="footer_text" value="<?= Form::value($settings, 'footer_text') ?>">
            </div>
        </div>
    </div>

    <div class="pa-card pa-anim-slide-up pa-mb-2">
        <div class="pa-card-header"><h3>درباره و تماس</h3></div>
        <div class="pa-card-body">
            <div class="pa-form-group">
                <label for="s-about">متن صفحهٔ درباره ما</label>
                <textarea id="s-about" class="pa-textarea" name="about_text" rows="4"><?= Form::value($settings, 'about_text') ?></textarea>
            </div>
            <div class="pa-form-row">
                <div class="pa-form-group">
                    <label for="s-phone">تلفن تماس</label>
                    <input id="s-phone" class="pa-input" type="text" name="contact_phone" value="<?= Form::value($settings, 'contact_phone') ?>">
                </div>
                <div class="pa-form-group">
                    <label for="s-email">ایمیل تماس</label>
                    <input id="s-email" class="pa-input" type="email" name="contact_email" value="<?= Form::value($settings, 'contact_email') ?>">
                </div>
            </div>
            <div class="pa-form-group">
                <label for="s-addr">نشانی</label>
                <textarea id="s-addr" class="pa-textarea" name="contact_address" rows="2"><?= Form::value($settings, 'contact_address') ?></textarea>
            </div>
        </div>
    </div>

    <div class="pa-card pa-anim-slide-up pa-mb-2">
        <div class="pa-card-header"><h3>پرداخت و شبکه</h3></div>
        <div class="pa-card-body">
            <div class="pa-form-row">
                <div class="pa-form-group">
                    <label for="s-bank">شماره حساب</label>
                    <input id="s-bank" class="pa-input" type="text" name="bank_account" value="<?= Form::value($settings, 'bank_account') ?>">
                </div>
                <div class="pa-form-group">
                    <label for="s-sheba">شماره شبا</label>
                    <input id="s-sheba" class="pa-input" type="text" name="sheba" value="<?= Form::value($settings, 'sheba') ?>">
                </div>
            </div>
            <div class="pa-form-group">
                <label for="s-ig">اینستاگرام</label>
                <input id="s-ig" class="pa-input" type="text" name="social_instagram" value="<?= Form::value($settings, 'social_instagram') ?>">
            </div>
        </div>
    </div>

    <div class="pa-d-flex pa-gap-2">
        <button type="submit" class="pa-btn pa-btn-primary pa-btn-lg" data-pa-icon="save"> ذخیرهٔ تنظیمات</button>
    </div>
</form>
