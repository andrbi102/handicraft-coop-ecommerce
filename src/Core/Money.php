<?php

declare(strict_types=1);

namespace App\Core;

/**
 * قالب‌بندی پول — همه مبالغ عدد صحیح تومان.
 *
 * هیچ‌گاه float برای پول استفاده نمی‌شود (قید دقت داده SPEC). نمایش با
 * جداکننده هزارگان فارسی و واحد «تومان».
 *
 * @package Coop\Core
 * @version 0.1.0
 * @security-level data
 */
final class Money
{
    /** ارقام فارسی برای نمایش. */
    private const FA_DIGITS = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    /** int (تومان) → «۱٬۲۳۰٬۰۰۰ تومان». */
    public static function format(int $amount): string
    {
        $grouped = number_format(max(0, $amount), 0, '', '٬');
        return self::toFa($grouped) . ' تومان';
    }

    /** فقط عدد با جداکننده، بدون واحد. */
    public static function num(int $amount): string
    {
        return self::toFa(number_format(max(0, $amount), 0, '', '٬'));
    }

    /** تبدیل ارقام انگلیسی به فارسی. */
    public static function toFa(string $text): string
    {
        return str_replace(['0','1','2','3','4','5','6','7','8','9'], self::FA_DIGITS, $text);
    }

    /** تبدیل ارقام فارسی/عربی به انگلیسی (برای ورودی کاربر). */
    public static function toEn(string $text): string
    {
        $fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $ar = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $out = str_replace($fa, range(0, 9), $text);
        $out = str_replace($ar, range(0, 9), $out);
        return $out;
    }

    /** تبدیل ورودی پولی کاربر (ممکن است با کاما/فارسی باشد) به int تومان. */
    public static function parse(string $input): int
    {
        $clean = self::toEn($input);
        $clean = preg_replace('/[^0-9]/', '', $clean);
        if ($clean === null || $clean === '') {
            return 0;
        }
        return (int) $clean;
    }
}
