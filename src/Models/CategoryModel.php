<?php

declare(strict_types=1);

namespace App\Models;

/**
 * دسته‌بندی محصولات — سلسله‌مراتبی (parent_id).
 *
 * @package Coop\Models
 * @version 0.1.0
 * @security-level data
 */
final class CategoryModel extends Model
{
    protected function table(): string
    {
        return 'categories';
    }

    protected function columns(): string
    {
        return 'id, name, slug, description, parent_id, sort_order, created_at';
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): int
    {
        $this->db->execute(
            "INSERT INTO categories (name, slug, description, parent_id, sort_order, created_at)
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['name'],
                $data['slug'],
                $data['description'] ?? '',
                (int) ($data['parent_id'] ?? 0) ?: null,
                (int) ($data['sort_order'] ?? 0),
                date('c'),
            ],
        );
        return (int) $this->db->pdo()->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        return $this->db->execute(
            "UPDATE categories SET name = ?, slug = ?, description = ?, parent_id = ?, sort_order = ? WHERE id = ?",
            [
                $data['name'],
                $data['slug'],
                $data['description'] ?? '',
                (int) ($data['parent_id'] ?? 0) ?: null,
                (int) ($data['sort_order'] ?? 0),
                $id,
            ],
        );
    }

    public function all(): array
    {
        return $this->db->query(
            "SELECT id, name, slug, description, parent_id, sort_order, created_at FROM categories ORDER BY sort_order ASC, name ASC",
        );
    }

    public function roots(): array
    {
        return $this->db->query(
            "SELECT id, name, slug, description, parent_id, sort_order, created_at FROM categories WHERE parent_id IS NULL ORDER BY sort_order ASC, name ASC",
        );
    }

    public function findBySlug(string $slug): ?array
    {
        $rows = $this->db->query(
            "SELECT id, name, slug, description, parent_id, sort_order, created_at FROM categories WHERE slug = ? LIMIT 1",
            [$slug],
        );
        return $rows[0] ?? null;
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        if ($exceptId === null) {
            $rows = $this->db->query("SELECT id FROM categories WHERE slug = ?", [$slug]);
        } else {
            $rows = $this->db->query("SELECT id FROM categories WHERE slug = ? AND id <> ?", [$slug, $exceptId]);
        }
        return $rows !== [];
    }

    public function productCount(int $categoryId): int
    {
        $rows = $this->db->query(
            "SELECT COUNT(*) AS c FROM products WHERE category_id = ? AND status = ?",
            [$categoryId, 'active'],
        );
        return (int) ($rows[0]['c'] ?? 0);
    }
}
