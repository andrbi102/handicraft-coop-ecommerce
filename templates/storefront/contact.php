<?php

declare(strict_types=1);

/**
 * تماس با ما — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

/** @var array<string, mixed> $__data */

$settings = $__data['settings'] ?? [];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>تماس با ما</h1>
        <div class="pa-breadcrumb"><a href="/">خانه</a><span class="pa-sep">›</span><span>تماس</span></div>
    </div>
</div>

<div class="pa-row">
    <div class="pa-col-7">
        <div class="pa-card">
            <div class="pa-card-header"><h3>فرم تماس</h3></div>
            <div class="pa-card-body">
                <form method="post" action="/contact" data-pa-validate-form>
                    <?= Csrf::field() ?>
                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="c-name">نام *</label>
                            <input id="c-name" class="pa-input" type="text" name="name" data-pa-validate="required" data-pa-minlen="3" required>
                            <div class="pa-form-feedback"></div>
                        </div>
                        <div class="pa-form-group">
                            <label for="c-email">ایمیل *</label>
                            <input id="c-email" class="pa-input" type="email" name="email" data-pa-validate="required" required>
                            <div class="pa-form-feedback"></div>
                        </div>
                    </div>
                    <div class="pa-form-group">
                        <label for="c-subject">موضوع</label>
                        <input id="c-subject" class="pa-input" type="text" name="subject">
                    </div>
                    <div class="pa-form-group">
                        <label for="c-message">پیام *</label>
                        <textarea id="c-message" class="pa-textarea" name="message" rows="5" data-pa-validate="required" data-pa-minlen="10" required></textarea>
                        <div class="pa-form-feedback"></div>
                    </div>
                    <button type="submit" class="pa-btn pa-btn-primary" data-pa-icon="envelope"> ارسال پیام</button>
                </form>
            </div>
        </div>
    </div>

    <div class="pa-col-5">
        <div class="pa-card">
            <div class="pa-card-header"><h3>راه‌های ارتباطی</h3></div>
            <div class="pa-card-body">
                <p class="pa-d-flex pa-gap-1 pa-align-center"><span data-pa-icon="phone"></span> <?= View::e((string) ($settings['contact_phone'] ?? '—')) ?></p>
                <p class="pa-d-flex pa-gap-1 pa-align-center"><span data-pa-icon="envelope"></span> <?= View::e((string) ($settings['contact_email'] ?? '—')) ?></p>
                <p class="pa-d-flex pa-gap-1 pa-align-center"><span data-pa-icon="map"></span> <?= View::e((string) ($settings['contact_address'] ?? '—')) ?></p>
                <?php if (!empty($settings['social_instagram'])): ?>
                    <p class="pa-d-flex pa-gap-1 pa-align-center"><span data-pa-icon="camera"></span> اینستاگرام: <?= View::e((string) $settings['social_instagram']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
