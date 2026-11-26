<?php

declare(strict_types=1);

/**
 * چیدمان فروشگاه عمومی — پوستهٔ PAdminLTE (storefront skin).
 *
 * کروم (site-nav/site-footer) سمت سرور رندر می‌شود. سبد خرید سمت سرور است
 * (SecureSession) پس pa-shop.js بارگذاری نمی‌شود — فقط pa-icons.js و
 * padminlte.js برای آیکون‌ها/تم/دراپ‌داون.
 *
 * @package Coop\Views\Layouts
 * @version 0.2.0
 * @security-level public
 */

use App\Core\PersianDate;

/** @var array<string, mixed> $__data */
/** @var string $content */

$user = $__data['user'] ?? null;
$flash = $__data['flash'] ?? [];
$cartCount = (int) ($__data['cartCount'] ?? 0);
$categories = $__data['categories'] ?? [];
$siteName = (string) ($__data['siteName'] ?? 'تعاونی نساجی');
$tagline = (string) ($__data['tagline'] ?? '');
$settings = $__data['settings'] ?? [];
$title = (string) ($__data['title'] ?? $siteName);
$path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$path = (string) $path;
?>
<!doctype html>
<html lang="fa" dir="rtl" data-pa-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= View::e($tagline) ?>">
    <title><?= View::e($title) ?> — <?= View::e($siteName) ?></title>
    <link rel="stylesheet" href="/assets/padminlte/padminlte.css">
    <link rel="stylesheet" href="/assets/padminlte/coop-overrides.css">
</head>
<body>
    <a class="pa-skip-link" href="#pa-main-content">پرش به محتوا</a>
    <?php include __DIR__ . '/../partials/store_nav.php'; ?>

    <main class="pa-site-main" id="pa-main-content">
        <?php include __DIR__ . '/../partials/flash.php'; ?>

        <?= $content ?>
    </main>

    <?php include __DIR__ . '/../partials/store_footer.php'; ?>
    <script src="/assets/padminlte/pa-icons.js"></script>
    <script src="/assets/padminlte/padminlte.js"></script>
</body>
</html>
