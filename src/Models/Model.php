<?php

declare(strict_types=1);

namespace App\Models;

use Database;

/**
 * پایه مدل‌ها — نگه‌داری Database و کمک‌تابع‌های مشترک.
 *
 * هر مدل فقط دسترسی به داده را کپسوله می‌کند؛ منطق کسب‌وکار در Services است.
 * همه کوئری‌ها Prepared Statement (قید ۵ SPEC). هرگز SELECT * — ستون‌ها
 * صریح宣言 می‌شوند (قید امنیتی + ثبات اسکیما).
 *
 * @package Coop\Models
 * @version 0.1.0
 * @security-level data
 */
abstract class Model
{
    public function __construct(protected Database $db)
    {
    }

    /** نام جدول — زیرکلاس تعریف می‌کند. */
    abstract protected function table(): string;

    /** لیست ستون‌ها (کاماآ جدا شده) — زیرکلاس تعریف می‌کند. */
    abstract protected function columns(): string;

    /** یک ردیف با id. */
    public function find(int $id): ?array
    {
        $rows = $this->db->query(
            "SELECT {$this->columns()} FROM {$this->table()} WHERE id = ?",
            [$id],
        );
        return $rows[0] ?? null;
    }

    /** شمارش کل (با فیلتر اختیاری SQL و پارامترها). */
    public function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) AS c FROM {$this->table()}";
        if ($where !== '') {
            $sql .= ' WHERE ' . $where;
        }
        $rows = $this->db->query($sql, $params);
        return (int) ($rows[0]['c'] ?? 0);
    }

    /** صفحه‌بندی ساده — page از ۱ شروع می‌شود. */
    public function paginate(string $where, array $params, array $order, int $page, int $perPage): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT {$this->columns()} FROM {$this->table()}";
        if ($where !== '') {
            $sql .= ' WHERE ' . $where;
        }
        if ($order !== []) {
            $clauses = [];
            foreach ($order as $col => $dir) {
                // سفید-لیستی جهت: فقط ASC/DESC
                $clauses[] = $col . ' ' . (strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC');
            }
            $sql .= ' ORDER BY ' . implode(', ', $clauses);
        }
        $sql .= ' LIMIT ' . $perPage . ' OFFSET ' . $offset;
        return $this->db->query($sql, $params);
    }
}
