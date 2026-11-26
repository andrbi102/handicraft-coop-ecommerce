<?php

declare(strict_types=1);

/**
 * سایدبار پنل مدیریت — سمت سرور با مسیرهای واقعی اپ.
 *
 * وضعیت فعال بر اساس REQUEST_URI محاسبه می‌شود. آیکون‌ها با data-pa-icon
 * توسط pa-icons.js تزریق می‌شوند.
 *
 * @package Coop\Views\Partials
 * @version 0.2.0
 * @security-level core
 */

/** @var array<string, mixed>|null $user */
/** @var string $siteName */

$path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/admin'), PHP_URL_PATH) ?: '/admin';
$path = (string) $path;

/** آیا مسیر فعلی با این بخش مطابقت دارد؟ (خود مسیر یا زیرمسیرها) */
$isActive = static function (string $route) use ($path): bool {
    if ($path === $route) {
        return true;
    }
    return str_starts_with($path, $route . '/');
};

/** آیا بخش گزارش‌ها فعال است؟ */
$reportsActive = $isActive('/admin/reports');

/** نقش کاربر برای نمایش منوی مالی */
$role = (string) ($user['role'] ?? '');
$isFinance = in_array($role, ['accountant', 'admin'], true);

/** برند: حرف اول نام سایت */
$brandMark = mb_substr((string) $siteName, 0, 1, 'UTF-8') ?: 'ت';

/** آیتم منو — label, route, icon, active */
$item = static function (string $label, string $route, string $icon, bool $active) use ($siteName): void {
    $cls = 'pa-sidebar-link' . ($active ? ' is-active' : '');
    ?>
    <a class="<?= $cls ?>" href="<?= View::e($route) ?>">
        <span data-pa-icon="<?= View::e($icon) ?>"></span>
        <span class="pa-label"><?= View::e($label) ?></span>
    </a>
    <?php
};
?>
<aside class="pa-sidebar" aria-label="منوی مدیریت">
    <a class="pa-sidebar-brand" href="/admin">
        <span class="pa-brand-mark"><?= View::e($brandMark) ?></span>
        <span class="pa-label"><?= View::e($siteName) ?></span>
    </a>
    <nav class="pa-sidebar-nav" aria-label="ناوبری مدیریت">
        <div class="pa-sidebar-section">اصلی</div>
        <?php $item('داشبورد', '/admin', 'dashboard', $path === '/admin'); ?>
        <?php $item('سفارش‌ها', '/admin/orders', 'shopping_cart', $isActive('/admin/orders')); ?>
        <?php if ($isFinance): ?>
            <?php $item('تسویه مالی', '/admin/settlements', 'coins', $isActive('/admin/settlements')); ?>
        <?php endif; ?>

        <div class="pa-sidebar-section">کاتالوگ</div>
        <?php $item('محصولات', '/admin/products', 'cube', $isActive('/admin/products')); ?>
        <?php $item('دسته‌بندی', '/admin/categories', 'folder_open', $isActive('/admin/categories')); ?>
        <?php $item('کد تخفیف', '/admin/coupons', 'tag', $isActive('/admin/coupons')); ?>

        <div class="pa-sidebar-section">اعضا</div>
        <?php $item('نساجان', '/admin/members', 'users', $isActive('/admin/members')); ?>
        <?php $item('مشتریان', '/admin/customers', 'user', $isActive('/admin/customers')); ?>
        <?php $item('پیام‌ها', '/admin/messages', 'envelope', $isActive('/admin/messages')); ?>

        <div class="pa-tree-item <?= $reportsActive ? 'is-open' : '' ?>">
            <button type="button" class="pa-sidebar-link<?= $reportsActive ? ' is-active' : '' ?>"
                    data-pa-tree-toggle aria-expanded="<?= $reportsActive ? 'true' : 'false' ?>">
                <span data-pa-icon="chart"></span>
                <span class="pa-label">گزارش‌ها</span>
                <span data-pa-icon="chevron_right" class="pa-chevron"></span>
            </button>
            <ul class="pa-sidebar-tree <?= $reportsActive ? 'is-open' : '' ?>">
                <li><?php $item('گزارش فروش', '/admin/reports/sales', 'chartline', $isActive('/admin/reports/sales')); ?></li>
                <li><?php $item('موجودی انبار', '/admin/reports/inventory', 'package_alt', $isActive('/admin/reports/inventory')); ?></li>
                <li><?php $item('عملکرد نساجان', '/admin/reports/members', 'users_alt', $isActive('/admin/reports/members')); ?></li>
            </ul>
        </div>

        <div class="pa-sidebar-section">سیستم</div>
        <?php $item('تنظیمات', '/admin/settings', 'cog', $isActive('/admin/settings')); ?>
        <a class="pa-sidebar-link" href="/">
            <span data-pa-icon="globe"></span>
            <span class="pa-label">نمایش فروشگاه</span>
        </a>
        <a class="pa-sidebar-link" href="/logout">
            <span data-pa-icon="logout"></span>
            <span class="pa-label">خروج از حساب</span>
        </a>
    </nav>
    <div class="pa-sidebar-footer">پنل مدیریت — نسخه ۰.۲.۰</div>
</aside>
