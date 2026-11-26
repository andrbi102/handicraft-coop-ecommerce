<?php

declare(strict_types=1);

namespace App\Models;

/**
 * کاربر — مشتری و کارکنان (مدیر/حسابدار/مدیرکل).
 *
 * @package Coop\Models
 * @version 0.1.0
 * @security-level data
 */
final class UserModel extends Model
{
    protected function table(): string
    {
        return 'users';
    }

    protected function columns(): string
    {
        return 'id, name, email, phone, role, status, created_at';
    }

    public function findByEmail(string $email): ?array
    {
        $rows = $this->db->query(
            "SELECT id, name, email, phone, role, status, password_hash, created_at FROM users WHERE email = ? LIMIT 1",
            [$email],
        );
        return $rows[0] ?? null;
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): int
    {
        $this->db->execute(
            "INSERT INTO users (name, email, phone, password_hash, role, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $data['name'],
                $data['email'],
                $data['phone'] ?? '',
                $data['password_hash'],
                $data['role'] ?? 'customer',
                $data['status'] ?? 'active',
                date('c'),
            ],
        );
        return (int) $this->db->pdo()->lastInsertId();
    }

    /** تعداد کاربران بر اساس نقش. */
    public function countByRole(string $role): int
    {
        return $this->count('role = ?', [$role]);
    }

    /** مشتریان (نقش customer) — صفحه‌بندی. */
    public function customers(int $page, int $perPage): array
    {
        return $this->paginate('role = ?', ['customer'], ['id' => 'DESC'], $page, $perPage);
    }

    public function updateRole(int $id, string $role): int
    {
        return $this->db->execute('UPDATE users SET role = ? WHERE id = ?', [$role, $id]);
    }

    public function updateStatus(int $id, string $status): int
    {
        return $this->db->execute('UPDATE users SET status = ? WHERE id = ?', [$status, $id]);
    }

    public function updateProfile(int $id, string $name, string $phone): int
    {
        return $this->db->execute(
            'UPDATE users SET name = ?, phone = ? WHERE id = ?',
            [$name, $phone, $id],
        );
    }

    public function updatePassword(int $id, string $hash): int
    {
        return $this->db->execute('UPDATE users SET password_hash = ? WHERE id = ?', [$hash, $id]);
    }
}
