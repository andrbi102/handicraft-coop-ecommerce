<?php
declare(strict_types=1);
/**
 * لایوت فروشگاه.
 * @package Coop\Templates
 * @version 0.1.0
 * @security-level public
 */
/** @var array<string, mixed> $__data */
use App\Core\Config;
$user = $__data['user'] ?? null;
$flash = $__data['flash'] ?? [];
$cartCount = (int) ($__data['cartCount'] ?? 0);
$categories = $__data['categories'] ?? [];
$siteName = $__data['siteName'] ?? 'تعاونی نساجی';
$tagline = $__data['tagline'] ?? '';
$settings = $__data['settings'] ?? [];
$title = $__data['title'] ?? $siteName;
$content = $__data['content'] ?? '';
$money = $__data['money'] ?? null;
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= View::e($tagline) ?>">
    <title><?= View::e($title) ?> — <?= View::e($siteName) ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="store-header">
    <div class="container top">
        <a href="/" class="brand">
            <span class="logo">🧶</span>
            <span><?= View::e($siteName) ?><small><?= View::e($tagline) ?></small></span>
        </a>
        <div class="search-box">
            <form action="/products" method="get">
                <input type="search" name="q" placeholder="جستجوی محصول، متریال، دسته…" value="<?= View::e($_GET['q'] ?? '') ?>">
                <button type="submit">جستجو</button>
            </form>
        </div>
        <nav class="store-nav">
            <a href="/cart" class="cart-badge">سبد خرید<span class="count"><?= $toFa((string) $cartCount) ?></span></a>
            <?php if ($user !== null): ?>
                <a href="/account">حساب من</a>
                <?php if (in_array($user['role'], ['manager','accountant','admin'], true)): ?>
                    <a href="/admin">پنل مدیریت</a>
                <?php endif; ?>
                <a href="/logout">خروج</a>
            <?php else: ?>
                <a href="/login">ورود</a>
                <a href="/register">ثبت‌نام</a>
            <?php endif; ?>
        </nav>
    </div>
    <div class="cats-bar">
        <div class="container">
            <a href="/" class="<?= ($_SERVER['REQUEST_URI'] ?? '') === '/' ? 'active' : '' ?>">خانه</a>
            <a href="/products">همه محصولات</a>
            <?php foreach ($categories as $cat): ?>
                <a href="/category/<?= rawurlencode((string) $cat['slug']) ?>"><?= View::e($cat['name']) ?></a>
            <?php endforeach; ?>
            <a href="/members">نساجان</a>
            <a href="/about">درباره</a>
            <a href="/contact">تماس</a>
        </div>
    </div>
</header>

<main class="container" style="padding-block: 20px;">
    <?php foreach (['success','error','info'] as $fkey): ?>
        <?php if (!empty($flash[$fkey])): ?>
            <div class="flash <?= $fkey ?>"><?= View::e((string) $flash[$fkey]) ?></div>
        <?php endif; ?>
    <?php endforeach; ?>
    <?= $content ?>
</main>

<footer class="store-footer">
    <div class="container">
        <div class="grid">
            <div>
                <h4><?= View::e($siteName) ?></h4>
                <p style="font-size:13px;opacity:.8"><?= View::e($tagline) ?></p>
                <p style="font-size:13px;opacity:.8"><?= View::e($settings['contact_address'] ?? '') ?></p>
            </div>
            <div>
                <h4>دسترسی سریع</h4>
                <p><a href="/products">محصولات</a></p>
                <p><a href="/members">نساجان تعاونی</a></p>
                <p><a href="/about">درباره تعاونی</a></p>
                <p><a href="/contact">تماس</a></p>
            </div>
            <div>
                <h4>تماس</h4>
                <p>تلفن: <?= View::e($settings['contact_phone'] ?? '—') ?></p>
                <p>ایمیل: <?= View::e($settings['contact_email'] ?? '—') ?></p>
                <?php if (!empty($settings['social_instagram'])): ?>
                    <p>اینستاگرام: <?= View::e($settings['social_instagram']) ?></p>
                <?php endif; ?>
            </div>
            <div>
                <h4>پرداخت</h4>
                <p style="font-size:13px;opacity:.8">شماره حساب: <?= View::e($settings['bank_account'] ?? '—') ?></p>
                <p style="font-size:13px;opacity:.8">شبا: <?= View::e($settings['sheba'] ?? '—') ?></p>
            </div>
        </div>
        <div class="copy">
            © <?= $toFa(\App\Core\PersianDate::year()) ?> <?= View::e($siteName) ?> — تمامی حقوق محفوظ است. <?= View::e($settings['footer_text'] ?? '') ?>
        </div>
    </div>
</footer>
<script src="/assets/js/app.js"></script>
</body>
</html>
