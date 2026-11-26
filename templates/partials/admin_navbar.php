<?php

declare(strict_types=1);

/**
 * نوار بالای پنل مدیریت — سمت سرور.
 *
 * @package Coop\Views\Partials
 * @version 0.2.0
 * @security-level core
 */

/** @var array<string, mixed>|null $user */
/** @var string $siteName */

$name = (string) ($user['name'] ?? 'کاربر');
$email = (string) ($user['email'] ?? '');
$role = (string) ($user['role'] ?? '');
$initials = mb_substr($name, 0, 1, 'UTF-8') ?: '؟';

$roleLabel = match ($role) {
    'admin' => 'مدیرکل',
    'manager' => 'مدیر فروش',
    'accountant' => 'حسابدار',
    default => $role,
};
?>
<header class="pa-header" role="banner">
    <button class="pa-icon-btn" data-pa-sidebar-toggle aria-label="باز/بسته کردن نوار کناری"></button>

    <div class="pa-search">
        <span data-pa-icon="search"></span>
        <input type="search" placeholder="جستجو…" data-pa-search="[data-pa-searchable-scope]" aria-label="جستجو">
    </div>

    <div class="pa-header-spacer"></div>

    <button class="pa-icon-btn" data-pa-theme-toggle aria-label="تغییر تم روشن/تاریک"></button>
    <button class="pa-icon-btn" data-pa-dir-toggle aria-label="تغییر جهت RTL/LTR"></button>

    <div class="pa-dropdown">
        <button class="pa-user-widget" data-pa-dropdown-toggle aria-haspopup="true" aria-expanded="false">
            <span class="pa-avatar pa-avatar-md"><?= View::e($initials) ?></span>
            <span class="pa-uw-text">
                <span class="pa-uw-name"><?= View::e($name) ?></span>
                <span class="pa-uw-role"><?= View::e($roleLabel) ?></span>
            </span>
            <span data-pa-icon="chevron_down"></span>
        </button>
        <div class="pa-dropdown-menu" role="menu">
            <div class="pa-dropdown-header"><?= View::e($email) ?></div>
            <a class="pa-dropdown-item" href="/account/profile"><span data-pa-icon="user"></span> پروفایل من</a>
            <a class="pa-dropdown-item" href="/account"><span data-pa-icon="shopping_bag"></span> سفارش‌های من</a>
            <div class="pa-dropdown-divider"></div>
            <a class="pa-dropdown-item" href="/"><span data-pa-icon="globe"></span> نمایش فروشگاه</a>
            <div class="pa-dropdown-divider"></div>
            <a class="pa-dropdown-item" href="/logout"><span data-pa-icon="log_out"></span> خروج از حساب</a>
        </div>
    </div>
</header>
