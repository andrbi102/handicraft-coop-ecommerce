<?php

declare(strict_types=1);

namespace App\Core;

use SecureSession;

/**
 * میان‌افزارها و گاردهای دسترسی.
 *
 * هدرهای امنیتی پنج‌گانه (CSP، HSTS، X-Content-Type-Options،
 * X-Frame-Options، Referrer-Policy) یک‌جا در نقطه ورود تنظیم می‌شوند —
 * نه در هر کنترلر (قید ۱۱ SPEC).
 *
 * @package Coop\Core
 * @version 0.1.0
 * @security-level security
 */
final class Middleware
{
    /** هدرهای امنیتی — قبل از هر خروجی بدنه فراخوانی شود. */
    public static function securityHeaders(): void
    {
        // چون ممکن است قبلاً هدر ارسال شده باشد (خطای خروجی زودهنگام)،
        // فقط در صورتی تنظیم می‌کنیم که headers chưa ارسال شده باشند.
        if (headers_sent()) {
            return;
        }
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        // HSTS فقط روی HTTPS معنادار است
        if (($_SERVER['HTTPS'] ?? 'off') !== 'off') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
        // CSP — فقط منبع same-origin برای اسکریپت/استایل/تصویر؛ no inline
        // event handlers. 'unsafe-inline' برای استایل مجاز شده تا قالب‌های
        // ساده PHP بدون build step کار کنند.
        header("Content-Security-Policy: default-src 'self'; " .
            "script-src 'self'; " .
            "style-src 'self' 'unsafe-inline'; " .
            "img-src 'self' data:; " .
            "font-src 'self'; " .
            "object-src 'none'; " .
            "base-uri 'self'; " .
            "form-action 'self'");
    }

    /** اگر کاربر وارد نشده، به صفحه ورود هدایت می‌شود. */
    public static function requireAuth(array $ctx): void
    {
        $user = $ctx['user'] ?? null;
        if ($user === null) {
            SecureSession::flash('error', 'برای دسترسی به این صفحه باید وارد شوید.');
            $return = rawurlencode((string) ($_SERVER['REQUEST_URI'] ?? '/'));
            header('Location: /login?return=' . $return);
            exit;
        }
    }

    /**
     * @param array<int, string> $roles
     */
    public static function requireRole(array $ctx, array $roles): void
    {
        self::requireAuth($ctx);
        $user = $ctx['user'] ?? null;
        $role = (string) ($user['role'] ?? '');
        if (!in_array($role, $roles, true)) {
            http_response_code(403);
            exit('۴۰۳ — دسترسی مجاز نیست.');
        }
    }

    /** گارد بخش مدیریت — نقش‌های staff. */
    public static function requireStaff(array $ctx): void
    {
        self::requireRole($ctx, ['manager', 'accountant', 'admin']);
    }

    /** گارد حسابدار/مدیر کل — برای امور مالی تعاونی. */
    public static function requireFinance(array $ctx): void
    {
        self::requireRole($ctx, ['accountant', 'admin']);
    }
}
