<?php

declare(strict_types=1);

namespace App\Models;

/**
 * تسویه تعاونی — پرداخت سهم عضو (مزد نساجی / سود / پیش‌پرداخت).
 *
 * هنگام تحویل سفارش، به‌ازای هر قلم متعلق به یک عضو، یک ردیف تسویه با
 * وضعیت pending ساخته می‌شود. حسابدار پس از واریز، وضعیت را به paid
 * تغییر می‌دهد. این جدول قلب بخش مالی تعاونی است.
 *
 * @package Coop\Models
 * @version 0.1.0
 * @security-level data
 */
final class SettlementModel extends Model
{
    protected function table(): string
    {
        return 'settlements';
    }

    protected function columns(): string
    {
        return 'id, member_id, order_item_id, order_id, amount, type, status, period, note, created_at, settled_at';
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): int
    {
        $this->db->execute(
            "INSERT INTO settlements (member_id, order_item_id, order_id, amount, type, status, period, note, created_at, settled_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                (int) $data['member_id'],
                isset($data['order_item_id']) ? (int) $data['order_item_id'] : null,
                isset($data['order_id']) ? (int) $data['order_id'] : null,
                (int) $data['amount'],
                $data['type'] ?? 'craft_wage',
                $data['status'] ?? 'pending',
                $data['period'] ?? date('Y-m'),
                $data['note'] ?? '',
                date('c'),
                null,
            ],
        );
        return (int) $this->db->pdo()->lastInsertId();
    }

    public function forMember(int $memberId, int $page, int $perPage): array
    {
        return $this->paginate('member_id = ?', [$memberId], ['id' => 'DESC'], $page, $perPage);
    }

    public function allForAdmin(int $page, int $perPage, string $status = ''): array
    {
        $where = $status !== '' ? 'status = ?' : '';
        $params = $status !== '' ? [$status] : [];
        return $this->paginate($where, $params, ['id' => 'DESC'], $page, $perPage);
    }

    public function markPaid(int $id, string $note): int
    {
        return $this->db->execute(
            'UPDATE settlements SET status = ?, note = ?, settled_at = ? WHERE id = ?',
            ['paid', $note, date('c'), $id],
        );
    }

    public function pendingForMember(int $memberId): int
    {
        $rows = $this->db->query(
            "SELECT COALESCE(SUM(amount), 0) AS s FROM settlements WHERE member_id = ? AND status = ?",
            [$memberId, 'pending'],
        );
        return (int) ($rows[0]['s'] ?? 0);
    }

    public function paidForMember(int $memberId): int
    {
        $rows = $this->db->query(
            "SELECT COALESCE(SUM(amount), 0) AS s FROM settlements WHERE member_id = ? AND status = ?",
            [$memberId, 'paid'],
        );
        return (int) ($rows[0]['s'] ?? 0);
    }

    /** مجموع تسویه معلق کل تعاونی — برای داشبورد. */
    public function totalPending(): int
    {
        $rows = $this->db->query("SELECT COALESCE(SUM(amount), 0) AS s FROM settlements WHERE status = ?", ['pending']);
        return (int) ($rows[0]['s'] ?? 0);
    }

    public function totalPaid(): int
    {
        $rows = $this->db->query("SELECT COALESCE(SUM(amount), 0) AS s FROM settlements WHERE status = ?", ['paid']);
        return (int) ($rows[0]['s'] ?? 0);
    }

    /** تسویه‌های یک دوره (ماه) — برای گزارش دوره‌ای. */
    public function forPeriod(string $period): array
    {
        return $this->db->query(
            "SELECT id, member_id, order_item_id, order_id, amount, type, status, period, note, created_at, settled_at FROM settlements WHERE period = ? ORDER BY id ASC",
            [$period],
        );
    }
}
