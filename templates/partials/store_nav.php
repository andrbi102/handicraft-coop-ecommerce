<?php

declare(strict_types=1);

/**
 * نوار بالای فروشگاه — سمت سرور با مسیرهای واقعی اپ.
 *
 * @package Coop\Views\Partials
 * @version 0.2.0
 * @security-level public
 */

/** @var array<string, mixed>|null $user */
/** @var int $cartCount */
/** @var array<int, array<string, mixed>> $categories */
/** @var string $siteName */
/** @var string $tagline */
/** @var string $path */

$brandMark = mb_substr($siteName, 0, 1, 'UTF-8') ?: 'ت';
$isStaff = $user !== null && in_array($user['role'], ['manager', 'accountant', 'admin'], true);
$name = (string) ($user['name'] ?? '');
$initials = mb_substr($name, 0, 1, 'UTF-8') ?: '؟';
$q = (string) ($_GET['q'] ?? '');

/** لینک فعال؟ */
$active = static fn(string $route): bool => $path === $route || str_starts_with($path, $route . '/');
?>
<header class="pa-site-nav" role="banner">
    <div class="pa-site-nav-in">
        <a class="pa-site-brand" href="/">
            <span class="pa-brand-mark"><?= View::e($brandMark) ?></span>
            <span><?= View::e($siteName) ?></span>
        </a>

        <nav class="pa-site-links" aria-label="ناوبری اصلی">
            <a href="/" class="<?= $path === '/' ? 'is-active' : '' ?>" aria-current="<?= $path === '/' ? 'page' : 'false' ?>">خانه</a>
            <a href="/products" class="<?= $active('/products') || $active('/category') || $active('/product') ? 'is-active' : '' ?>">محصولات</a>
            <a href="/members" class="<?= $active('/members') || $active('/member') ? 'is-active' : '' ?>">نساجان</a>
            <a href="/about" class="<?= $active('/about') ? 'is-active' : '' ?>">درباره</a>
            <a href="/track" class="<?= $active('/track') ? 'is-active' : '' ?>">پیگیری سفارش</a>
            <a href="/contact" class="<?= $active('/contact') ? 'is-active' : '' ?>">تماس</a>
        </nav>

        <form class="pa-site-search" action="/products" method="get" role="search">
            <span data-pa-icon="search"></span>
            <input type="search" name="q" placeholder="جستجوی محصول…" value="<?= View::e($q) ?>" aria-label="جستجو">
        </form>

        <div class="pa-site-actions">
            <button class="pa-icon-btn" data-pa-theme-toggle aria-label="تغییر تم"></button>

            <a class="pa-site-cart" href="/wishlist" aria-label="علاقه‌مندی‌ها">
                <span data-pa-icon="heart"></span>
                <?php if (($wishlistCount ?? 0) > 0): ?>
                    <span class="pa-site-cart-badge"><?= $toFa((string) $wishlistCount) ?></span>
                <?php endif; ?>
            </a>

            <a class="pa-site-cart" href="/cart" aria-label="سبد خرید">
                <span data-pa-icon="shopping_cart"></span>
                <?php if ($cartCount > 0): ?>
                    <span class="pa-site-cart-badge"><?= $toFa((string) $cartCount) ?></span>
                <?php endif; ?>
            </a>

            <?php if ($user !== null): ?>
                <div class="pa-dropdown">
                    <button class="pa-user-widget" data-pa-dropdown-toggle aria-haspopup="true" aria-expanded="false">
                        <span class="pa-avatar pa-avatar-sm"><?= View::e($initials) ?></span>
                        <span class="pa-uw-text"><span class="pa-uw-name"><?= View::e($name) ?></span></span>
                        <span data-pa-icon="chevron_down"></span>
                    </button>
                    <div class="pa-dropdown-menu" role="menu">
                        <a class="pa-dropdown-item" href="/account"><span data-pa-icon="shopping_bag"></span> سفارش‌های من</a>
                        <a class="pa-dropdown-item" href="/account/profile"><span data-pa-icon="user"></span> پروفایل</a>
                        <?php if ($isStaff): ?>
                            <div class="pa-dropdown-divider"></div>
                            <a class="pa-dropdown-item" href="/admin"><span data-pa-icon="dashboard"></span> پنل مدیریت</a>
                        <?php endif; ?>
                        <div class="pa-dropdown-divider"></div>
                        <a class="pa-dropdown-item" href="/logout"><span data-pa-icon="logout"></span> خروج</a>
                    </div>
                </div>
            <?php else: ?>
                <a class="pa-btn pa-btn-outline pa-btn-sm" href="/login">ورود</a>
                <a class="pa-btn pa-btn-primary pa-btn-sm" href="/register">ثبت‌نام</a>
            <?php endif; ?>

            <button class="pa-icon-btn pa-site-burger" data-pa-site-burger aria-label="منو"></button>
        </div>
    </div>

    <div class="pa-site-subbar">
        <div class="pa-site-nav-in">
            <a href="/products">همه محصولات</a>
            <?php foreach ($categories as $cat): ?>
                <a href="/category/<?= rawurlencode((string) $cat['slug']) ?>" class="<?= $active('/category/' . $cat['slug']) ? 'is-active' : '' ?>"><?= View::e($cat['name']) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</header>
