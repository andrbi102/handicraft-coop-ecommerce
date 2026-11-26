<?php

declare(strict_types=1);

namespace App\Core;

/**
 * ساخت slug امن از عنوان فارسی/انگلیسی.
 *
 * @package Coop\Core
 * @version 0.1.0
 * @security-level data
 */
final class Slug
{
    public static function make(string $text): string
    {
        // تبدیل ارقام فارسی به انگلیسی و نرمال‌سازی فاصله
        $text = Money::toEn($text);
        $text = trim($text);
        // نگه‌داشتن حروف الفبا، اعداد، خط‌تیره و زیرخط؛ بقیه به خط‌تیره
        $text = preg_replace('/[^\p{L}\p{N}\s\-]+/u', '', $text) ?? '';
        $text = preg_replace('/[\s]+/u', '-', $text) ?? '';
        $text = mb_strtolower($text, 'UTF-8');
        $text = trim($text, '-');
        return $text === '' ? 'item-' . date('Ymd-His') : $text;
    }

    /** slug یکتا تولید می‌کند — اگر تکراری بود، پسوند عددی می‌گیرد. */
    public static function unique(callable $exists, string $base): string
    {
        $slug = $base;
        $i = 1;
        while ($exists($slug)) {
            $slug = $base . '-' . (++$i);
        }
        return $slug;
    }
}
