<?php
declare(strict_types=1);
/**
 * تنظیمات فروشگاه.
 * @package Coop\Templates\Admin
 * @version 0.1.0
 * @security-level admin
 */
$settings = $__data['settings'] ?? [];
$keys = $__data['keys'] ?? [];
$fieldDefs = [
    'site_name'        => ['نام فروشگاه', 'text'],
    'tagline'          => ['شعار', 'text'],
    'announcement'     => ['پیام اعلان بالای صفحه اصلی', 'textarea'],
    'about_text'       => ['متن صفحه درباره ما', 'textarea'],
    'contact_phone'    => ['تلفن تماس', 'text'],
    'contact_email'    => ['ایمیل تماس', 'email'],
    'contact_address'  => ['نشانی', 'textarea'],
    'bank_account'     => ['شماره حساب', 'text'],
    'sheba'            => ['شماره شبا', 'text'],
    'social_instagram' => ['اینستاگرام', 'text'],
    'footer_text'      => ['متن فوتر', 'text'],
];
function sval(array $s, string $k): string { return View::e((string) ($s[$k] ?? '')); }
?>
<div class="admin-card">
    <form action="/admin/settings" method="post">
        <?= Csrf::field() ?>
        <div class="form-grid">
            <?php foreach ($fieldDefs as $key => [$label, $type]): ?>
            <div class="field" style="grid-column:<?= $type === 'textarea' ? '1/-1' : 'auto' ?>">
                <label><?= View::e($label) ?></label>
                <?php if ($type === 'textarea'): ?>
                    <textarea name="<?= $key ?>"><?= sval($settings, $key) ?></textarea>
                <?php else: ?>
                    <input type="<?= $type ?>" name="<?= $key ?>" value="<?= sval($settings, $key) ?>">
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="btn btn-primary">ذخیره تنظیمات</button>
    </form>
</div>
