<?php

declare(strict_types=1);

/**
 * کنترلر فرانت — نقطه ورود همه درخواست‌ها.
 *
 * Apache (.htaccess) یاbuilt-in server (public/.htaccess.php) همه مسیرها
 * را به این فایل می‌فرستند. این فایل فقط بوت می‌کند و اجرا را به App می‌سپارد.
 *
 * @package Coop\Entry
 * @version 0.1.0
 * @security-level core
 */

// ریشه پروژه
$base = dirname(__DIR__);

// بارگذاری مستقیم هسته (پیش از اتولودر) — سپس boot اتولودر را ثبت می‌کند.
require_once $base . '/src/Core/App.php';

App\Core\App::boot();
App\Core\App::run($_SERVER);
