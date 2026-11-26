<?php

declare(strict_types=1);

namespace App\Models;

/**
 * عضو تعاونی — نساج/هنرمند تولیدکننده محصولات.
 *
 * جدا از users: عضو تعاونی لزوماً حساب کاربری ندارد. سهام (share_capital)
 * و craft (تخصص نساجی) را نگه‌داری می‌کند. مبلغ تسویه به عضو از طریق
 * Settlement محاسبه می‌شود.
 *
 * @package Coop\Models
 * @version 0.1.0
 * @security-level data
 */
final class MemberModel extends Model
{
    protected function table(): string
    {
        return 'members';
    }

    protected function columns(): string
    {
        return 'id, full_name, craft, region, joined_at, share_capital, phone, bio, status, created_at';
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): int
    {
        $this->db->execute(
            "INSERT INTO members (full_name, craft, region, joined_at, share_capital, phone, bio, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['full_name'],
                $data['craft'] ?? '',
                $data['region'] ?? '',
                $data['joined_at'] ?? date('Y-m-d'),
                (int) ($data['share_capital'] ?? 0),
                $data['phone'] ?? '',
                $data['bio'] ?? '',
                $data['status'] ?? 'active',
                date('c'),
            ],
        );
        return (int) $this->db->pdo()->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        return $this->db->execute(
            "UPDATE members SET full_name = ?, craft = ?, region = ?, joined_at = ?, share_capital = ?, phone = ?, bio = ?, status = ? WHERE id = ?",
            [
                $data['full_name'],
                $data['craft'] ?? '',
                $data['region'] ?? '',
                $data['joined_at'] ?? date('Y-m-d'),
                (int) ($data['share_capital'] ?? 0),
                $data['phone'] ?? '',
                $data['bio'] ?? '',
                $data['status'] ?? 'active',
                $id,
            ],
        );
    }

    public function allActive(): array
    {
        return $this->db->query(
            "SELECT id, full_name, craft, region, joined_at, share_capital, phone, bio, status, created_at FROM members WHERE status = ? ORDER BY full_name ASC",
            ['active'],
        );
    }

    /** مجموع سهام اعضا — برای ترازنامه تعاونی. */
    public function totalShareCapital(): int
    {
        $rows = $this->db->query("SELECT COALESCE(SUM(share_capital), 0) AS s FROM members WHERE status = ?", ['active']);
        return (int) ($rows[0]['s'] ?? 0);
    }

    /** تعداد محصولات فعال یک عضو. */
    public function productCount(int $memberId): int
    {
        $rows = $this->db->query(
            "SELECT COUNT(*) AS c FROM products WHERE member_id = ? AND status = ?",
            [$memberId, 'active'],
        );
        return (int) ($rows[0]['c'] ?? 0);
    }
}
