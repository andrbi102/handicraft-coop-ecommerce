<?php

declare(strict_types=1);

namespace App\Core;

/**
 * کمک‌تابع‌های درخواست — فیلتر ورودی از $_POST/$_GET/$_SERVER.
 *
 * ورودی همیشه به‌صورت رشته فیلتر می‌شود؛ تولیدکننده با Validator اعتبارسنجی می‌کند.
 *
 * @package Coop\Core
 * @version 0.1.0
 * @security-level security
 */
final class Request
{
    public static function post(string $key, mixed $default = null): ?string
    {
        if (!array_key_exists($key, $_POST)) {
            return $default;
        }
        $value = $_POST[$key];
        if (is_array($value)) {
            return null; // آرایه‌ها از کانال جداگانه خوانده می‌شوند
        }

        return trim((string) $value);
    }

    /** @return array<int, string> */
    public static function postArray(string $key): array
    {
        $value = $_POST[$key] ?? [];
        if (!is_array($value)) {
            return [];
        }
        return array_values(array_map(
            static fn (mixed $v): string => trim((string) $v),
            array_filter($value, static fn (mixed $v): bool => is_scalar($v)),
        ));
    }

    public static function query(string $key, mixed $default = null): ?string
    {
        if (!array_key_exists($key, $_GET)) {
            return $default;
        }
        return trim((string) $_GET[$key]);
    }

    public static function queryInt(string $key, int $default = 0): int
    {
        $raw = self::query($key);
        if ($raw === null || $raw === '') {
            return $default;
        }
        $val = filter_var($raw, FILTER_VALIDATE_INT);
        return $val === false ? $default : (int) $val;
    }

    public static function path(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url((string) $uri, PHP_URL_PATH) ?: '/';

        return $path === false ? '/' : (string) $path;
    }

    public static function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    public static function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        self::redirect(is_string($referer) && $referer !== '' ? $referer : '/');
    }
}
