<?php

declare(strict_types=1);

namespace App\Models;

/**
 * کد تخفیف — درصدی یا مبلغ ثابت.
 *
 * @package Coop\Models
 * @version 0.1.0
 * @security-level data
 */
final class CouponModel extends Model
{
    protected function table(): string
    {
        return 'coupons';
    }

    protected function columns(): string
    {
        return 'id, code, type, value, min_subtotal, usage_limit, used_count, expires_at, status, created_at';
    }

    public function findByCode(string $code): ?array
    {
        $rows = $this->db->query(
            "SELECT id, code, type, value, min_subtotal, usage_limit, used_count, expires_at, status, created_at FROM coupons WHERE code = ? AND status = ? LIMIT 1",
            [$code, 'active'],
        );
        return $rows[0] ?? null;
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): int
    {
        $this->db->execute(
            "INSERT INTO coupons (code, type, value, min_subtotal, usage_limit, used_count, expires_at, status, created_at)
             VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?)",
            [
                strtoupper($data['code']),
                $data['type'],
                (int) $data['value'],
                (int) ($data['min_subtotal'] ?? 0),
                (int) ($data['usage_limit'] ?? 0) ?: null,
                $data['expires_at'] ?: null,
                $data['status'] ?? 'active',
                date('c'),
            ],
        );
        return (int) $this->db->pdo()->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        return $this->db->execute(
            "UPDATE coupons SET code = ?, type = ?, value = ?, min_subtotal = ?, usage_limit = ?, expires_at = ?, status = ? WHERE id = ?",
            [
                strtoupper($data['code']),
                $data['type'],
                (int) $data['value'],
                (int) ($data['min_subtotal'] ?? 0),
                (int) ($data['usage_limit'] ?? 0) ?: null,
                $data['expires_at'] ?: null,
                $data['status'] ?? 'active',
                $id,
            ],
        );
    }

    /** یک استفاده را شمارش — پس از اعمال موفق روی سفارش. */
    public function incrementUsage(int $id): int
    {
        return $this->db->execute('UPDATE coupons SET used_count = used_count + 1 WHERE id = ?', [$id]);
    }

    public function all(int $page, int $perPage): array
    {
        return $this->paginate('', [], ['id' => 'DESC'], $page, $perPage);
    }
}
