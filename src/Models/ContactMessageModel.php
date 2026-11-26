<?php

declare(strict_types=1);

namespace App\Models;

/**
 * پیام تماس با تعاونی.
 *
 * @package Coop\Models
 * @version 0.1.0
 * @security-level data
 */
final class ContactMessageModel extends Model
{
    protected function table(): string
    {
        return 'contact_messages';
    }

    protected function columns(): string
    {
        return 'id, name, email, subject, message, is_read, created_at';
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): int
    {
        $this->db->execute(
            "INSERT INTO contact_messages (name, email, subject, message, is_read, created_at)
             VALUES (?, ?, ?, ?, 0, ?)",
            [
                $data['name'],
                $data['email'],
                $data['subject'] ?? '',
                $data['message'],
                date('c'),
            ],
        );
        return (int) $this->db->pdo()->lastInsertId();
    }

    public function markRead(int $id): int
    {
        return $this->db->execute('UPDATE contact_messages SET is_read = 1 WHERE id = ?', [$id]);
    }

    public function unreadCount(): int
    {
        return $this->count('is_read = 0');
    }

    public function all(int $page, int $perPage): array
    {
        return $this->paginate('', [], ['id' => 'DESC'], $page, $perPage);
    }
}
