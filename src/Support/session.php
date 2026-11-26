<?php

declare(strict_types=1);

/**
 * نشست امن — کوکی با HttpOnly + Secure + SameSite=Strict
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level security
 */
final class SecureSession
{
    /**
     * آیا درخواست فعلی روی HTTPS است؟
     *
     * پرچم Secure کوکیِ نشست فقط روی HTTPS فعال می‌شود؛ روی HTTP (مثل
     * توسعهٔ محلی localhost) مرورگر کوکیِ Secure را ذخیره نمی‌کرد و نشست
     * بین GET (فرم) و POST (تأیید توکن) از دست می‌رفت — که خطای ۴۱۹ CSRF
     * را می‌ساخت. با شرطی‌کردن، توسعه روی HTTP کار می‌کند و تولید روی HTTPS
     * همچنان Secure می‌ماند.
     */
    private static function isHttps(): bool
    {
        $https = strtolower((string) ($_SERVER['HTTPS'] ?? ''));
        if ($https !== '' && $https !== 'off') {
            return true;
        }
        if (($_SERVER['SERVER_PORT'] ?? '') === '443') {
            return true;
        }
        $proto = strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));

        return $proto === 'https';
    }

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => self::isHttps(),   // فقط روی HTTPS — توسعهٔ HTTP هم کار کند
            'httponly' => true,             // دور از جاوااسکریپت
            'samesite' => 'Strict',
        ]);
        session_start();
    }

    /**
     * پس از login اجباری — جلوگیری از Session Fixation.
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /**
     * پیام یک‌بارمصرف بین دو request.
     */
    public static function flash(string $key, ?string $message = null): ?string
    {
        self::start();
        if ($message !== null) {
            $_SESSION['_flash'][$key] = $message;

            return null;
        }
        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);

        return $value;
    }

    /**
     * ذخیرهٔ ورودی‌های ارسالیِ کاربر برای بازنمایش بعد از خطای اعتبارسنجی.
     * کلیدهای حساس (رمز و توکن) هرگز ذخیره نمی‌شوند.
     *
     * @param array<string, mixed> $input عموماً $_POST
     */
    public static function flashOld(array $input): void
    {
        self::start();
        $old = [];
        foreach ($input as $k => $v) {
            if (is_string($k) && is_scalar($v) && !str_starts_with($k, '_') && !preg_match('/password|csrf|token/i', $k)) {
                $old[$k] = (string) $v;
            }
        }
        $_SESSION['_old'] = $old;
    }

    /**
     * خواندن یک‌بارمصرفِ ورودی قدیمی یک فیلد (بعد از خواندن پاک می‌شود).
     */
    public static function old(string $key): ?string
    {
        self::start();
        if (!isset($_SESSION['_old'][$key])) {
            return null;
        }
        $value = (string) $_SESSION['_old'][$key];
        unset($_SESSION['_old'][$key]);

        return $value;
    }

    /**
     * پاک‌کردن ورودی‌های قدیری (پس از رندر موفق فرم).
     */
    public static function clearOld(): void
    {
        self::start();
        unset($_SESSION['_old']);
    }

    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }
}
