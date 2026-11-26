<?php

declare(strict_types=1);

/**
 * چیدمان مدیریت — پوستهٔ PAdminLTE (بدون وابستگی).
 *
 * کروم (سایدبار/نوار بالا/فوتر) سمت سرور رندر می‌شود (با مسیرهای واقعی اپ)،
 * نه با data-pa-layout + pa-layout.js (که لینک‌های دموی پوسته را تزریق می‌کند).
 * فقط pa-icons.js و padminlte.js بارگذاری می‌شوند — رفتارها (تم، دراپ‌داون،
 * سایدبار، دیتاتیبل، فرم) با data-pa-* سیم‌کشی می‌شوند.
 *
 * متغیرها از Controller::render (extract $data) در دسترس‌اند: $content, $title,
 * $user, $flash, $siteName, $settings, $__data.
 *
 * @package Coop\Views\Layouts
 * @version 0.2.0
 * @security-level core
 */

/** @var array<string, mixed> $__data */
/** @var string $content */
/** @var string $siteName */
/** @var string $title */
/** @var array<string, mixed>|null $user */
/** @var array<string, string> $flash */
/** @var array<string, mixed> $settings */

$siteName = (string) ($__data['siteName'] ?? 'تعاونی نساجی');
$title = (string) ($__data['title'] ?? $siteName);
$user = $__data['user'] ?? null;
$flash = $__data['flash'] ?? [];
$settings = $__data['settings'] ?? [];
$theme = (string) ($settings['admin_theme'] ?? 'light');

?>
<!doctype html>
<html lang="fa" dir="rtl" data-pa-theme="<?= View::e($theme) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="پنل مدیریت <?= View::e($siteName) ?>">
    <meta name="robots" content="noindex, nofollow">
    <title><?= View::e($title) ?> — <?= View::e($siteName) ?></title>
    <link rel="icon" href="/assets/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="stylesheet" href="/assets/padminlte/padminlte.css">
    <link rel="stylesheet" href="/assets/padminlte/coop-overrides.css">
</head>
<body>
    <a class="pa-skip-link" href="#pa-main-content">پرش به محتوا</a>
    <div class="pa-app" id="pa-app">
        <?php include __DIR__ . '/../partials/admin_sidebar.php'; ?>
        <div class="pa-main">
            <?php include __DIR__ . '/../partials/admin_navbar.php'; ?>
            <main class="pa-content" id="pa-main-content">
                <?php include __DIR__ . '/../partials/flash.php'; ?>

                <?= $content ?>

                <?php include __DIR__ . '/../partials/admin_footer.php'; ?>
            </main>
        </div>
    </div>
    <script src="/assets/padminlte/pa-icons.js"></script>
    <script src="/assets/padminlte/padminlte.js"></script>
</body>
</html>
