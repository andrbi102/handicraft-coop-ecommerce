<?php

declare(strict_types=1);

namespace App\Core;

/**
 * مدیریت کلید برنامه (APP_KEY).
 *
 * کلید هرگز در مخزن هاردکد نمی‌شود: اول از محیط خوانده می‌شود، سپس از
 * یک فایل در storage. اگر نبود، یک کلید تصادفی ۳۲ بایتی تولید و ذخیره
 * می‌شود تا هر نصب کلید یکتای خود را داشته باشد.
 *
 * این کلید برای امضای HMAC توکن CSRF (scaffolds/csrf.php) به‌کار می‌رود.
 *
 * @package Coop\Core
 * @version 0.1.0
 * @security-level security
 */
final class AppKey
{
    public static function ensure(): string
    {
        $env = getenv('APP_KEY');
        if (is_string($env) && $env !== '') {
            return $env;
        }

        $file = dirname(__DIR__, 2) . '/storage/db/app_key.txt';
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0700, true);
        }

        if (is_file($file)) {
            $stored = trim((string) file_get_contents($file));
            if ($stored !== '') {
                putenv('APP_KEY=' . $stored);

                return $stored;
            }
        }

        // تولید کلید تصادفی با random_bytes (هسته PHP) — امن رمزنگارانه.
        $key = bin2hex(random_bytes(32));
        file_put_contents($file, $key, LOCK_EX);
        chmod($file, 0600);
        putenv('APP_KEY=' . $key);

        return $key;
    }
}
