<?php

declare(strict_types=1);

namespace App\Models;

/**
 * تنظیمات کلید-مقدار — اطلاعات قابل ویرایش تعاونی (نشانی، تلفن، درباره ما،
 * شماره حساب، متن‌های صفحه اصلی و ...).
 *
 * @package Coop\Models
 * @version 0.1.0
 * @security-level data
 */
final class SettingModel extends Model
{
    protected function table(): string
    {
        return 'settings';
    }

    protected function columns(): string
    {
        return '`key`, value';
    }

    /** @return array<string, string> */
    public function allAsMap(): array
    {
        $rows = $this->db->query('SELECT `key`, value FROM settings');
        $map = [];
        foreach ($rows as $row) {
            $map[(string) $row['key']] = (string) $row['value'];
        }
        return $map;
    }

    public function get(string $key, string $default = ''): string
    {
        $rows = $this->db->query('SELECT value FROM settings WHERE `key` = ? LIMIT 1', [$key]);
        return $rows[0]['value'] ?? $default;
    }

    public function set(string $key, string $value): int
    {
        // UPSERT — SQLite و MySQL هر دو از این قاعده پشتیبانی می‌کنند
        // (SQLite: INSERT OR REPLACE؛ یکسان رفتار می‌کند برای کلید یکتا).
        return $this->db->execute(
            'INSERT INTO settings (`key`, value) VALUES (?, ?) ON CONFLICT(`key`) DO UPDATE SET value = excluded.value',
            [$key, $value],
        );
    }

    /** @param array<string, string> $pairs */
    public function setMany(array $pairs): void
    {
        $this->db->transaction(function (Database $db) use ($pairs): void {
            foreach ($pairs as $k => $v) {
                $db->execute(
                    'INSERT INTO settings (`key`, value) VALUES (?, ?) ON CONFLICT(`key`) DO UPDATE SET value = excluded.value',
                    [$k, $v],
                );
            }
        });
    }
}
