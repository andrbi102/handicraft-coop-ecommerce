<?php

declare(strict_types=1);

/**
 * رندر پیام‌های فلش — با ARIA صحیح (قاعدهٔ UX: Focusable Error Summary).
 *  - خطا → role="alert" aria-live="assertive" (اعلان فوری برای صفحه‌خوان)
 *  - موفقیت/اطلاع → role="status" aria-live="polite"
 *  - اولین خطا بعد از بارگذاری فوکوس می‌گیرد (tabindex=-1).
 *
 * متغیرها از scope لایوت: $flash (array<string,string>), $toFa (callable).
 *
 * @package Coop\Views\Partials
 * @version 0.2.0
 * @security-level public
 */

if (!isset($flash) || !is_array($flash)) {
    $flash = [];
}

$levels = [
    'success' => 'success',
    'error' => 'danger',
    'info' => 'info',
    'warning' => 'warning',
];
$icons = [
    'success' => 'check_circle',
    'danger' => 'x_circle',
    'info' => 'info',
    'warning' => 'alert_triangle',
];

$hasError = false;
foreach ($levels as $key => $kind):
    $msg = trim((string) ($flash[$key] ?? ''));
    if ($msg === '') {
        continue;
    }
    $isError = $kind === 'danger';
    $hasError = $hasError || $isError;
    ?>
    <div class="pa-alert pa-alert-<?= $kind ?> pa-anim-slide-up"
         role="<?= $isError ? 'alert' : 'status' ?>"
         aria-live="<?= $isError ? 'assertive' : 'polite' ?>"
         tabindex="-1">
        <span data-pa-icon="<?= $icons[$kind] ?? 'info' ?>"></span>
        <div class="pa-alert-body"><?= View::e($msg) ?></div>
        <button type="button" class="pa-close" aria-label="بستن پیام">&times;</button>
    </div>
    <?php
endforeach;

if ($hasError):
    // فوکوس روی اولین خطا پس از بارگذاری — قابل‌دسترسی با کیبورد/صفحه‌خوان
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var a = document.querySelector('.pa-alert[role="alert"]');
            if (a) { a.focus(); }
        });
    </script>
    <?php
endif;
