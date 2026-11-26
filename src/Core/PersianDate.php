<?php

declare(strict_types=1);

namespace App\Core;

/**
 * تاریخ شمسی — فقط سال، با الگوریتم استاندارد تبدیل میلادی→جلالی.
 *
 * بدون کتابخانه خارجی؛ الگوریتم شناخته‌شده و صحیح (Borkowski).
 *
 * @package Coop\Core
 * @version 0.1.0
 * @security-level data
 */
final class PersianDate
{
    /** سال شمسی جاری به‌صورت رشته. */
    public static function year(): string
    {
        $gy = (int) date('Y');
        $gm = (int) date('n');
        $gd = (int) date('j');
        $gDaysInMonth = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = 355666
            + (365 * $gy)
            + (int) (($gy2 + 3) / 4)
            - (int) (($gy2 + 99) / 100)
            + (int) (($gy2 + 399) / 400)
            + $gd
            + $gDaysInMonth[$gm - 1];
        $jy = -1595 + (33 * (int) ($days / 12053));
        $days %= 12053;
        $jy += 4 * (int) ($days / 1461);
        $days %= 1461;
        if ($days > 365) {
            $jy += (int) (($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        return (string) ($jy > 0 ? $jy : $gy);
    }
}
