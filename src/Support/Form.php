<?php

declare(strict_types=1);

namespace App\Support;

use View;

/**
 * کمک‌تابع‌های فرم — خروجی مقدار فیلد با escape خودکار.
 *
 * جایگزین تعریف تابع درون قالب (که در صورت include مجدد در یک پردازش
 * موجب "Cannot redeclare" می‌شد).
 *
 * @package Coop\Support
 * @version 0.1.0
 * @security-level public
 */
final class Form
{
    /**
     * مقدار یک فیلد از ردیف داده — همیشه escape شده.
     *
     * اولویت: ورودی قدیمیِ ارسالیِ کاربر (از یک اعتبارسنجی ناموفق) >
     * مقدار ذخیره‌شده در ردیف > پیش‌فرض. این رفتار ورودی کاربر را پس از
     * خطای اعتبارسنجی حفظ می‌کند (قاعدهٔ UX: Preserve Input After Failed Submit).
     *
     * @param array<string, mixed>|null $row
     */
    public static function value(?array $row, string $key, string $default = ''): string
    {
        $old = \SecureSession::old($key);
        if ($old !== null && $old !== '') {
            return View::e($old);
        }
        if ($row === null) {
            return $default;
        }
        return View::e((string) ($row[$key] ?? $default));
    }
}
