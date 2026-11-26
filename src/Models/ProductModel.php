<?php

declare(strict_types=1);

namespace App\Models;

/**
 * محصول — کالای دست‌ساز تعاونی.
 *
 * قیمت و هزینه (cost) عدد صحیح تومان. cost برای محاسبه سهم عضو (مزد نساجی)
 * در تسویه تعاونی استفاده می‌شود.
 *
 * @package Coop\Models
 * @version 0.1.0
 * @security-level data
 */
final class ProductModel extends Model
{
    protected function table(): string
    {
        return 'products';
    }

    protected function columns(): string
    {
        return 'id, category_id, member_id, name, slug, description, price, cost, stock, sku, '
            . 'weight_grams, material, dimensions, image, is_featured, status, created_at, updated_at';
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): int
    {
        $now = date('c');
        $this->db->execute(
            "INSERT INTO products (category_id, member_id, name, slug, description, price, cost, stock, sku, weight_grams, material, dimensions, image, is_featured, status, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                (int) $data['category_id'],
                (int) ($data['member_id'] ?? 0) ?: null,
                $data['name'],
                $data['slug'],
                $data['description'] ?? '',
                (int) $data['price'],
                (int) ($data['cost'] ?? 0),
                (int) ($data['stock'] ?? 0),
                $data['sku'] ?? '',
                (int) ($data['weight_grams'] ?? 0),
                $data['material'] ?? '',
                $data['dimensions'] ?? '',
                $data['image'] ?? '',
                (int) ($data['is_featured'] ?? 0),
                $data['status'] ?? 'active',
                $now,
                $now,
            ],
        );
        return (int) $this->db->pdo()->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        return $this->db->execute(
            "UPDATE products SET category_id = ?, member_id = ?, name = ?, slug = ?, description = ?, price = ?, cost = ?, stock = ?, sku = ?, weight_grams = ?, material = ?, dimensions = ?, image = ?, is_featured = ?, status = ?, updated_at = ? WHERE id = ?",
            [
                (int) $data['category_id'],
                (int) ($data['member_id'] ?? 0) ?: null,
                $data['name'],
                $data['slug'],
                $data['description'] ?? '',
                (int) $data['price'],
                (int) ($data['cost'] ?? 0),
                (int) ($data['stock'] ?? 0),
                $data['sku'] ?? '',
                (int) ($data['weight_grams'] ?? 0),
                $data['material'] ?? '',
                $data['dimensions'] ?? '',
                $data['image'] ?? '',
                (int) ($data['is_featured'] ?? 0),
                $data['status'] ?? 'active',
                date('c'),
                $id,
            ],
        );
    }

    public function findBySlug(string $slug): ?array
    {
        $rows = $this->db->query(
            "SELECT id, category_id, member_id, name, slug, description, price, cost, stock, sku, weight_grams, material, dimensions, image, is_featured, status, created_at, updated_at FROM products WHERE slug = ? LIMIT 1",
            [$slug],
        );
        return $rows[0] ?? null;
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        if ($exceptId === null) {
            $rows = $this->db->query("SELECT id FROM products WHERE slug = ?", [$slug]);
        } else {
            $rows = $this->db->query("SELECT id FROM products WHERE slug = ? AND id <> ?", [$slug, $exceptId]);
        }
        return $rows !== [];
    }

    /** محصولات ویژه صفحه اصلی. */
    public function featured(int $limit = 8): array
    {
        return $this->db->query(
            "SELECT id, category_id, member_id, name, slug, description, price, cost, stock, sku, weight_grams, material, dimensions, image, is_featured, status, created_at, updated_at FROM products WHERE is_featured = 1 AND status = ? ORDER BY updated_at DESC LIMIT ?",
            ['active', $limit],
        );
    }

    /** جدیدترین محصولات. */
    public function latest(int $limit = 8): array
    {
        return $this->db->query(
            "SELECT id, category_id, member_id, name, slug, description, price, cost, stock, sku, weight_grams, material, dimensions, image, is_featured, status, created_at, updated_at FROM products WHERE status = ? ORDER BY created_at DESC LIMIT ?",
            ['active', $limit],
        );
    }

    /** کاتالوگ با فیلتر دسته/جستجو/مرتب‌سازی — صفحه‌بندی. */
    public function catalog(?int $categoryId, ?string $search, string $sort, int $page, int $perPage): array
    {
        $where = ['status = ?'];
        $params = ['active'];
        if ($categoryId !== null && $categoryId > 0) {
            $where[] = 'category_id = ?';
            $params[] = $categoryId;
        }
        if ($search !== null && $search !== '') {
            $where[] = '(name LIKE ? OR description LIKE ? OR material LIKE ?)';
            $like = '%' . $search . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }
        $order = match ($sort) {
            'price_asc' => ['price' => 'ASC'],
            'price_desc' => ['price' => 'DESC'],
            'name' => ['name' => 'ASC'],
            default => ['created_at' => 'DESC'],
        };
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT id, category_id, member_id, name, slug, description, price, cost, stock, sku, weight_grams, material, dimensions, image, is_featured, status, created_at, updated_at FROM products WHERE "
            . implode(' AND ', $where);
        $clauses = [];
        foreach ($order as $col => $dir) {
            $clauses[] = $col . ' ' . (strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC');
        }
        $sql .= ' ORDER BY ' . implode(', ', $clauses) . ' LIMIT ' . $perPage . ' OFFSET ' . $offset;
        return $this->db->query($sql, $params);
    }

    public function catalogCount(?int $categoryId, ?string $search): int
    {
        $where = ['status = ?'];
        $params = ['active'];
        if ($categoryId !== null && $categoryId > 0) {
            $where[] = 'category_id = ?';
            $params[] = $categoryId;
        }
        if ($search !== null && $search !== '') {
            $where[] = '(name LIKE ? OR description LIKE ? OR material LIKE ?)';
            $like = '%' . $search . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }
        $rows = $this->db->query("SELECT COUNT(*) AS c FROM products WHERE " . implode(' AND ', $where), $params);
        return (int) ($rows[0]['c'] ?? 0);
    }

    /** محصولات مرتبط در همان دسته. */
    public function related(int $categoryId, int $exceptId, int $limit = 4): array
    {
        return $this->db->query(
            "SELECT id, category_id, member_id, name, slug, description, price, cost, stock, sku, weight_grams, material, dimensions, image, is_featured, status, created_at, updated_at FROM products WHERE category_id = ? AND id <> ? AND status = ? LIMIT ?",
            [$categoryId, $exceptId, 'active', $limit],
        );
    }

    /** محصولات یک عضو تعاونی. */
    public function byMember(int $memberId): array
    {
        return $this->db->query(
            "SELECT id, category_id, member_id, name, slug, description, price, cost, stock, sku, weight_grams, material, dimensions, image, is_featured, status, created_at, updated_at FROM products WHERE member_id = ? AND status = ? ORDER BY name ASC",
            [$memberId, 'active'],
        );
    }

    /** همه محصولات برای پنل مدیریت (شامل draft). */
    public function allForAdmin(int $page, int $perPage): array
    {
        return $this->paginate('1=1', [], ['id' => 'DESC'], $page, $perPage);
    }

    public function adjustStock(int $id, int $delta): int
    {
        return $this->db->execute(
            'UPDATE products SET stock = stock + ?, updated_at = ? WHERE id = ?',
            [$delta, date('c'), $id],
        );
    }

    public function setStock(int $id, int $stock): int
    {
        return $this->db->execute(
            'UPDATE products SET stock = ?, updated_at = ? WHERE id = ?',
            [$stock, date('c'), $id],
        );
    }

    public function delete(int $id): int
    {
        // بایگانی به‌جای حذف فیزیکی — حفظ یکپارچگی سفارش‌های قبلی.
        return $this->db->execute(
            'UPDATE products SET status = ?, updated_at = ? WHERE id = ?',
            ['archived', date('c'), $id],
        );
    }

    /** محصولات با موجودی پایین. */
    public function lowStock(int $threshold): array
    {
        return $this->db->query(
            "SELECT id, category_id, member_id, name, slug, description, price, cost, stock, sku, weight_grams, material, dimensions, image, is_featured, status, created_at, updated_at FROM products WHERE status = ? AND stock <= ? ORDER BY stock ASC",
            ['active', $threshold],
        );
    }
}
