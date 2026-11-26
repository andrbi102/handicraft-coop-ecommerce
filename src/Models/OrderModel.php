<?php

declare(strict_types=1);

namespace App\Models;

/**
 * سفارش و اقلام سفارش.
 *
 * هنگام ثبت سفارش، مشخصات هر قلم (نام، قیمت، هزینه، عضو) به‌صورت snapshot
 * در order_items ذخیره می‌شود تا تغییر后来的 محصول روی سفارش‌های قبلی اثر نگذارد.
 *
 * @package Coop\Models
 * @version 0.1.0
 * @security-level data
 */
final class OrderModel extends Model
{
    protected function table(): string
    {
        return 'orders';
    }

    protected function columns(): string
    {
        return 'id, user_id, order_number, status, subtotal, discount, shipping_cost, total, '
            . 'shipping_name, shipping_phone, shipping_address, shipping_city, shipping_postal_code, '
            . 'notes, coupon_code, created_at, updated_at';
    }

    public function findByNumber(string $number): ?array
    {
        $rows = $this->db->query(
            "SELECT id, user_id, order_number, status, subtotal, discount, shipping_cost, total, shipping_name, shipping_phone, shipping_address, shipping_city, shipping_postal_code, notes, coupon_code, created_at, updated_at FROM orders WHERE order_number = ? LIMIT 1",
            [$number],
        );
        return $rows[0] ?? null;
    }

    public function forUser(int $userId, int $page, int $perPage): array
    {
        return $this->paginate('user_id = ?', [$userId], ['id' => 'DESC'], $page, $perPage);
    }

    public function allForAdmin(int $page, int $perPage, string $status = ''): array
    {
        $where = $status !== '' ? 'status = ?' : '';
        $params = $status !== '' ? [$status] : [];
        return $this->paginate($where, $params, ['id' => 'DESC'], $page, $perPage);
    }

    public function items(int $orderId): array
    {
        return $this->db->query(
            "SELECT id, order_id, product_id, product_name, member_id, price, cost, quantity, subtotal FROM order_items WHERE order_id = ? ORDER BY id ASC",
            [$orderId],
        );
    }

    public function recent(int $limit = 5): array
    {
        return $this->db->query(
            "SELECT id, user_id, order_number, status, subtotal, discount, shipping_cost, total, shipping_name, shipping_phone, shipping_address, shipping_city, shipping_postal_code, notes, coupon_code, created_at, updated_at FROM orders ORDER BY id DESC LIMIT ?",
            [$limit],
        );
    }

    public function updateStatus(int $id, string $status): int
    {
        return $this->db->execute(
            'UPDATE orders SET status = ?, updated_at = ? WHERE id = ?',
            [$status, date('c'), $id],
        );
    }

    public function countByStatus(string $status): int
    {
        return $this->count('status = ?', [$status]);
    }

    /** مجموع فروش تأییدشده (paid/shipped/delivered). */
    public function totalRevenue(): int
    {
        $rows = $this->db->query(
            "SELECT COALESCE(SUM(total), 0) AS s FROM orders WHERE status IN (?, ?, ?)",
            ['paid', 'shipped', 'delivered'],
        );
        return (int) ($rows[0]['s'] ?? 0);
    }

    /** فروش روزانه ۷ روز اخیر — برای نمودار داشبورد. */
    public function dailyRevenue(int $days): array
    {
        if ($this->db->isSqlite()) {
            $sql = "SELECT substr(created_at, 1, 10) AS d, COALESCE(SUM(total), 0) AS s FROM orders WHERE status IN (?, ?, ?) AND created_at >= date('now', ?) GROUP BY d ORDER BY d ASC";
        } else {
            $sql = "SELECT DATE(created_at) AS d, COALESCE(SUM(total), 0) AS s FROM orders WHERE status IN (?, ?, ?) AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY) GROUP BY d ORDER BY d ASC";
        }
        $neg = '-' . $days . ' days';
        $params = $this->db->isSqlite()
            ? ['paid', 'shipped', 'delivered', $neg]
            : ['paid', 'shipped', 'delivered', $days];
        return $this->db->query($sql, $params);
    }
}
