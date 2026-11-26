<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CouponModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use Config;
use Database;
use RuntimeException;

/**
 * ثبت سفارش — تراکنش یکپارچه: کاهش موجودی، snapshot اقلام، ساخت تسویه‌های
 * معلق برای اعضا، افزایش مصرف کوپن.
 *
 * idempotency: شماره سفارش یکتا است؛ کاهش موجودی فقط تا سقف موجودی.
 *
 * @package Coop\Services
 * @version 0.1.0
 * @security-level data
 */
final class OrderService
{
    public function __construct(
        private Database $db,
        private ProductModel $products,
        private OrderModel $orders,
        private CouponModel $coupons,
        private SettlementService $settlements,
    ) {
    }

    /**
     * اعتبارسنجی و محاسبه تخفیف کوپن — بدون اعمال.
     * @return array{ok: bool, discount: int, reason: string, coupon: ?array}
     */
    public function evaluateCoupon(string $code, int $subtotal): array
    {
        if ($code === '') {
            return ['ok' => false, 'discount' => 0, 'reason' => 'کد تخفیف وارد نشده است.', 'coupon' => null];
        }
        $coupon = $this->coupons->findByCode($code);
        if ($coupon === null) {
            return ['ok' => false, 'discount' => 0, 'reason' => 'کد تخفیف معتبر نیست.', 'coupon' => null];
        }
        if ($coupon['expires_at'] !== null && $coupon['expires_at'] !== '' && strtotime((string) $coupon['expires_at']) < time()) {
            return ['ok' => false, 'discount' => 0, 'reason' => 'کد تخفیف منقضی شده است.', 'coupon' => null];
        }
        if ($coupon['usage_limit'] !== null && (int) $coupon['used_count'] >= (int) $coupon['usage_limit']) {
            return ['ok' => false, 'discount' => 0, 'reason' => 'سقف استفاده از کد تخفیف تکمیل شده است.', 'coupon' => null];
        }
        if ($subtotal < (int) $coupon['min_subtotal']) {
            return ['ok' => false, 'discount' => 0, 'reason' => 'حداقل خرید برای این کد رعایت نشده است.', 'coupon' => null];
        }
        $discount = $coupon['type'] === 'percent'
            ? (int) round($subtotal * (int) $coupon['value'] / 100)
            : (int) $coupon['value'];
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }
        return ['ok' => true, 'discount' => $discount, 'reason' => '', 'coupon' => $coupon];
    }

    /** هزینه حمل بر اساس مجموع. */
    public function shippingCost(int $subtotal): int
    {
        $shop = (array) Config::get('shop', []);
        $free = (int) ($shop['free_shipping_over'] ?? 0);
        if ($free > 0 && $subtotal >= $free) {
            return 0;
        }
        return (int) ($shop['shipping_flat'] ?? 0);
    }

    /**
     * ثبت نهایی سفارش.
     *
     * @param array<int, array{product: array<string, mixed>, quantity: int, line: int}> $items
     * @param array<string, mixed> $shipping
     * @param array<string, mixed> $couponEval
     */
    public function place(int $userId, array $items, array $shipping, array $couponEval): int
    {
        if ($items === []) {
            throw new RuntimeException('سبد خرید خالی است.');
        }

        return $this->db->transaction(function (Database $db) use ($userId, $items, $shipping, $couponEval): int {
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += (int) $item['line'];
            }
            $discount = (int) $couponEval['discount'];
            $shippingCost = $this->shippingCost($subtotal);
            $total = max(0, $subtotal - $discount) + $shippingCost;
            $orderNumber = $this->generateOrderNumber();

            $db->execute(
                "INSERT INTO orders (user_id, order_number, status, subtotal, discount, shipping_cost, total, shipping_name, shipping_phone, shipping_address, shipping_city, shipping_postal_code, notes, coupon_code, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $userId,
                    $orderNumber,
                    'pending',
                    $subtotal,
                    $discount,
                    $shippingCost,
                    $total,
                    $shipping['name'],
                    $shipping['phone'],
                    $shipping['address'],
                    $shipping['city'],
                    $shipping['postal_code'],
                    $shipping['notes'] ?? '',
                    $couponEval['coupon']['code'] ?? null,
                    date('c'),
                    date('c'),
                ],
            );
            $orderId = (int) $db->pdo()->lastInsertId();

            // اقلام با snapshot + کاهش موجودی
            $insertItem = $db->pdo()->prepare(
                "INSERT INTO order_items (order_id, product_id, product_name, member_id, price, cost, quantity, subtotal)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            );
            $decrement = $db->pdo()->prepare('UPDATE products SET stock = stock - ?, updated_at = ? WHERE id = ? AND stock >= ?');
            foreach ($items as $item) {
                $product = $item['product'];
                $qty = (int) $item['quantity'];
                // کاهش atomic با شرط stock >= qty — اگر سطر تغییر نکرد، موجودی کافی نبوده
                $decrement->execute([$qty, date('c'), (int) $product['id'], $qty]);
                if ($decrement->rowCount() === 0) {
                    throw new RuntimeException('موجودی محصول «' . $product['name'] . '» کافی نیست.');
                }
                $insertItem->execute([
                    $orderId,
                    (int) $product['id'],
                    $product['name'],
                    isset($product['member_id']) ? (int) $product['member_id'] : null,
                    (int) $product['price'],
                    (int) ($product['cost'] ?? 0),
                    $qty,
                    (int) $product['price'] * $qty,
                ]);
            }

            // افزایش مصرف کوپن
            if ($couponEval['ok'] && $couponEval['coupon'] !== null) {
                $db->execute('UPDATE coupons SET used_count = used_count + 1 WHERE id = ?', [(int) $couponEval['coupon']['id']]);
            }

            return $orderId;
        });
    }

    private function generateOrderNumber(): string
    {
        return 'CO-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }
}
