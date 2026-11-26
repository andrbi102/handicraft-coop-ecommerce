<?php

declare(strict_types=1);

/**
 * روتر برای PHP built-in server (توسعه محلی).
 *
 * استفاده: php -S localhost:8000 server.php
 *
 * فایل‌های استاتیک موجود در public/ مستقیم سرو می‌شوند (با MIME درست)؛
 * بقیه درخواست‌ها به کنترلر فرانت public/index.php فرستاده می‌شوند.
 *
 * نکته: سرو مستقیم با readfile انجام می‌شود، چون `return false` در روتر
 * فایل را از CWD (ریشه پروژه) می‌خواند نه از public/ — که با ساختار ما
 * هم‌خوان نیست و موجب ۴۰۴ شدن دارایی‌ها می‌شد.
 *
 * @package Coop\Dev
 * @version 0.1.0
 * @security-level dev
 */

$uri = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';

// جلوگیری از پیمایش مسیر: نرمال‌سازی و رد کردن «..»
if ($uri !== '/' && $uri !== false) {
    $uri = (string) $uri;
    // فقط سرو فایل‌های استاتیک در public/ — مسیرهای خارج از آن به اپ می‌رود
    if (str_contains($uri, '..') === false) {
        $file = __DIR__ . '/public' . $uri;
        if (is_file($file)) {
            // نقش‌افزونه → MIME معتبر برای دارایی‌های وب
            static $extMap = [
                'css' => 'text/css; charset=utf-8',
                'js' => 'application/javascript; charset=utf-8',
                'mjs' => 'application/javascript; charset=utf-8',
                'json' => 'application/json; charset=utf-8',
                'svg' => 'image/svg+xml',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'ico' => 'image/x-icon',
                'woff' => 'font/woff',
                'woff2' => 'font/woff2',
                'ttf' => 'font/ttf',
                'eot' => 'application/vnd.ms-fontobject',
                'otf' => 'font/otf',
                'map' => 'application/json',
                'txt' => 'text/plain; charset=utf-8',
            ];
            $ext = strtolower((string) pathinfo($file, PATHINFO_EXTENSION));
            $mime = $extMap[$ext] ?? (mime_content_type($file) ?: 'application/octet-stream');

            // کش مرورگر در توسعه (۱ ساعت) — هدرهای امنیتی اپ در index.php اعمال می‌شود
            header('Content-Type: ' . $mime);
            header('Content-Length: ' . (string) filesize($file));
            header('Cache-Control: public, max-age=3600');
            header('X-Content-Type-Options: nosniff');
            readfile($file);
            return; // پاسخ پایان یافت
        }
    }
}

require __DIR__ . '/public/index.php';
