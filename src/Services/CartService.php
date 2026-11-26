<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProductModel;
use RuntimeException;

/**
 * سبد خرید — مبتنی بر نشست (بدون جدول اضافی).
 *
 * سبد در $_SESSION['cart'] به‌صورت [product_id => qty] نگه‌داری می‌شود.
 * برای هر عملیات، محصول از DB خوانده می‌شود تا قیمت/موجودی همیشه به‌روز
 * باشد (قیمت سبد در زمان تسویحه حساب نهایی می‌شود).
 *
 * @package Coop\Services
 * @version 0.1.0
 * @security-level data
 */
final class CartService
{
    private const SESSION_KEY = 'cart';

    public function __construct(private ProductModel $products)
    {
        SecureSession::start();
        if (!isset($_SESSION[self::SESSION_KEY]) || !is_array($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }
    }

    /** @return array<int, array{product: array<string, mixed>, quantity: int, line: int}> */
    public function items(): array
    {
        $out = [];
        foreach ($_SESSION[self::SESSION_KEY] as $pid => $qty) {
            $product = $this->products->find((int) $pid);
            if ($product === null || $product['status'] !== 'active') {
                continue; // محصول حذف/بایگانی شده — از سبد کنار گذاشته می‌شود
            }
            $quantity = max(1, (int) $qty);
            $out[] = [
                'product' => $product,
                'quantity' => $quantity,
                'line' => (int) $product['price'] * $quantity,
            ];
        }
        return $out;
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $product = $this->products->find($productId);
        if ($product === null || $product['status'] !== 'active') {
            throw new RuntimeException('محصول یافت نشد.');
        }
        if ($product['stock'] <= 0) {
            throw new RuntimeException('محصول ناموجود است.');
        }
        $current = (int) ($_SESSION[self::SESSION_KEY][$productId] ?? 0);
        $requested = $current + max(1, $quantity);
        if ($requested > (int) $product['stock']) {
            throw new RuntimeException('تعداد درخواستی بیشتر از موجودی است.');
        }
        $_SESSION[self::SESSION_KEY][$productId] = $requested;
    }

    public function update(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($productId);
            return;
        }
        $product = $this->products->find($productId);
        if ($product === null) {
            throw new RuntimeException('محصول یافت نشد.');
        }
        if ($quantity > (int) $product['stock']) {
            throw new RuntimeException('تعداد بیشتر از موجودی است.');
        }
        $_SESSION[self::SESSION_KEY][$productId] = $quantity;
    }

    public function remove(int $productId): void
    {
        unset($_SESSION[self::SESSION_KEY][$productId]);
    }

    public function clear(): void
    {
        $_SESSION[self::SESSION_KEY] = [];
    }

    public function count(): int
    {
        return array_sum(array_map('intval', $_SESSION[self::SESSION_KEY]));
    }

    public function subtotal(): int
    {
        $sum = 0;
        foreach ($this->items() as $item) {
            $sum += $item['line'];
        }
        return $sum;
    }

    /** تعداد اقلام متمایز (برای نمایش badge). */
    public function distinctCount(): int
    {
        return count($_SESSION[self::SESSION_KEY]);
    }
}
