<?php

declare(strict_types=1);

/**
 * پیکربندی اصلی فروشگاه تعاونی صنایع‌دستی نساجی.
 *
 * آرایه‌ای ساده از مقادیر پیکربندی — بدون فریمورک، بدون YAML، بدون .env
 * پیچیده. کلیدهای امنیتی (APP_KEY) از محیط یا فایل ذخیره‌سازی خوانده
 * می‌شوند تا هرگز در مخزن هاردکد نشوند.
 *
 * @package Coop\Config
 * @version 0.1.0
 * @security-level config
 */

return [
    'name' => 'تعاونی نساجی دست‌ساز',
    'name_en' => 'Handicraft Textile Cooperative',
    'legal_name' => 'شرکت تعاونی صنایع‌دستی نساجی روستایی',
    'tagline' => 'فرش، گلیم و پارچه‌های دست‌بافت اهل صنعت',

    // واحد پول: تمام مبالغ به‌صورت عدد صحیح تومان نگه‌داری می‌شوند
    // (هرگز float برای پول — قید دقت داده SPEC).
    'currency' => 'تومان',
    'currency_code' => 'IRT',

    // پایگاه داده: پیش‌فرض SQLite (بدون نیاز به سرور — مناسب هاست اشتراکی).
    // برای MySQL مقدارdsn را به 'mysql:host=127.0.0.1;dbname=coop;charset=utf8mb4'
    // تغییر دهید و user/pass را از محیط بخوانید.
    'db' => [
        'dsn' => getenv('COOP_DB_DSN') ?: 'sqlite:' . dirname(__DIR__) . '/storage/db/coop.sqlite',
        'user' => getenv('COOP_DB_USER') ?: '',
        'pass' => getenv('COOP_DB_PASS') ?: '',
    ],

    'debug' => getenv('COOP_DEBUG') === '1',

    // تنظیمات فروشگاه
    'shop' => [
        'shipping_flat' => 80000,        // هزینه حمل ثابت (تومان)
        'free_shipping_over' => 3000000, // ارسال رایگان بالای این مبلغ
        'tax_rate' => 0,                 // مالیات بر ارزش افزوده (٪) — صفر تا زمان تنظیم رسمی
        'low_stock_threshold' => 3,
    ],

    // نقش‌های کاربری — منبع حقیقت کنترل دسترسی
    'roles' => [
        'customer'   => 'مشتری',
        'manager'    => 'مدیر فروش',
        'accountant' => 'حسابدار تعاونی',
        'admin'      => 'مدیر کل',
    ],

    'admin_prefix' => '/admin',
];
