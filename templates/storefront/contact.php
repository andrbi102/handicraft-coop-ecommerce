<?php
declare(strict_types=1);
/**
 * تماس با ما.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level public
 */
$settings = $__data['settings'] ?? [];
?>
<div class="section-title"><h2>تماس با تعاونی</h2></div>
<div class="checkout-layout">
    <div class="admin-card">
        <form action="/contact" method="post">
            <?= Csrf::field() ?>
            <div class="field"><label>نام</label><input type="text" name="name" required minlength="3"></div>
            <div class="field"><label>ایمیل</label><input type="email" name="email" required></div>
            <div class="field"><label>موضوع</label><input type="text" name="subject"></div>
            <div class="field"><label>پیام</label><textarea name="message" required minlength="10"></textarea></div>
            <button class="btn btn-primary">ارسال پیام</button>
        </form>
    </div>
    <aside class="summary-card">
        <h3>راه‌های ارتباطی</h3>
        <p>تلفن: <?= View::e($settings['contact_phone'] ?? '—') ?></p>
        <p>ایمیل: <?= View::e($settings['contact_email'] ?? '—') ?></p>
        <p>نشانی: <?= View::e($settings['contact_address'] ?? '—') ?></p>
        <?php if (!empty($settings['social_instagram'])): ?>
            <p>اینستاگرام: <?= View::e($settings['social_instagram']) ?></p>
        <?php endif; ?>
    </aside>
</div>
