<?php

declare(strict_types=1);

/**
 * بارگذار پیکربندی — یک بار می‌خواند و کش می‌کند.
 *
 * @package Coop\Core
 * @version 0.1.0
 * @security-level config
 */
final class Config
{
    /** @var array<string, mixed>|null */
    private static ?array $cache = null;

    /** @return array<string, mixed> */
    public static function all(): array
    {
        if (self::$cache === null) {
            self::$cache = require dirname(__DIR__, 2) . '/config/app.php';
        }

        return self::$cache;
    }

    /** @return mixed */
    public static function get(string $key, mixed $default = null): mixed
    {
        $config = self::all();
        $segments = explode('.', $key);
        $value = $config;
        foreach ($segments as $seg) {
            if (!is_array($value) || !array_key_exists($seg, $value)) {
                return $default;
            }
            $value = $value[$seg];
        }

        return $value;
    }
}
