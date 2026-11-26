<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProductModel;
use SecureSession;
use RuntimeException;

/**
 * علاقه‌مندی‌ها — مبتنی بر نشست (بدون جدول اضافی).
 *
 * فهرست شناسهٔ محصولات در $_SESSION['wishlist'] نگه‌داری می‌شود.
 * برای نمایش، محصولات از DB خوانده می‌شوند تا فقط فعال‌ها نمایش داده شوند.
 *
 * @package Coop\Services
 * @version 0.1.0
 * @security-level data
 */
final class WishlistService
{
    private const SESSION_KEY = 'wishlist';

    public function __construct(private ProductModel $products)
    {
        SecureSession::start();
        if (!isset($_SESSION[self::SESSION_KEY]) || !is_array($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }
    }

    /** افزودن محصول به علاقه‌مندی‌ها (idempotent). */
    public function add(int $productId): void
    {
        $product = $this->products->find($productId);
        if ($product === null) {
            throw new RuntimeException('محصول یافت نشد.');
        }
        $_SESSION[self::SESSION_KEY][$productId] = true;
    }

    public function remove(int $productId): void
    {
        unset($_SESSION[self::SESSION_KEY][$productId]);
    }

    public function has(int $productId): bool
    {
        return isset($_SESSION[self::SESSION_KEY][$productId]);
    }

    public function clear(): void
    {
        $_SESSION[self::SESSION_KEY] = [];
    }

    /** @return list<int> */
    public function ids(): array
    {
        return array_map('intval', array_keys($_SESSION[self::SESSION_KEY]));
    }

    /** @return array<int, array<string, mixed>> فهرست ردیف‌های محصول فعال */
    public function items(): array
    {
        $out = [];
        foreach (array_keys($_SESSION[self::SESSION_KEY]) as $pid) {
            $product = $this->products->find((int) $pid);
            if ($product === null) {
                continue;
            }
            $out[] = $product;
        }
        return $out;
    }

    public function count(): int
    {
        return count($_SESSION[self::SESSION_KEY]);
    }
}
