<?php

declare(strict_types=1);

/**
 * روتر برای PHP built-in server (توسعه محلی).
 *
 * استفاده: php -S localhost:8000 server.php
 *
 * فایل‌های استاتیک موجود در public/ مستقیم سرو می‌شوند؛ بقیه درخواست‌ها
 * به کنترلر فرانت public/index.php فرستاده می‌شوند.
 *
 * @package Coop\Dev
 * @version 0.1.0
 * @security-level dev
 */

$uri = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
if ($uri !== '/' && $uri !== false) {
    $file = __DIR__ . '/public' . $uri;
    if (is_file($file)) {
        return false; // اجازه به سرور داخلی برای سرو فایل استاتیک
    }
}
require __DIR__ . '/public/index.php';
