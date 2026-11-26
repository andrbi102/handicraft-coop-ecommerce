<?php

declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Controllers\StorefrontController;
use App\Core\Request;
use App\Services\CartService;
use RuntimeException;

/**
 * سبد خرید — افزودن/به‌روزرسانی/حذف/مشاهده.
 *
 * @package Coop\Controllers\Storefront
 * @version 0.1.0
 * @security-level public
 */
final class CartController extends StorefrontController
{
    public function index(array $ctx): void
    {
        $cart = $this->c->get(CartService::class);
        $this->renderStore('storefront/cart', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
            'title' => 'سبد خرید',
        ], $ctx);
    }

    public function add(array $ctx): void
    {
        $productId = Request::queryInt('id', 0);
        if ($productId <= 0) {
            $productId = (int) Request::post('product_id', '0');
        }
        $quantity = Request::queryInt('qty', 1);
        if ($quantity <= 0) {
            $quantity = (int) Request::post('quantity', '1') ?: 1;
        }
        try {
            $this->c->get(CartService::class)->add($productId, $quantity);
            $this->flash('success', 'محصول به سبد خرید اضافه شد.');
        } catch (RuntimeException $e) {
            $this->flash('error', $e->getMessage());
        }
        $this->redirect('/cart');
    }

    public function update(array $ctx): void
    {
        $items = Request::postArray('quantities');
        // quantities به‌صورت [product_id => qty] از فرم نمی‌آید چون postArray
        // فقط آرایه‌های عددی می‌دهد؛ پس از $_POST مستقیم با کلید عددی می‌خوانیم.
        $raw = $_POST['quantities'] ?? [];
        try {
            $cart = $this->c->get(CartService::class);
            if (is_array($raw)) {
                foreach ($raw as $pid => $qty) {
                    if (!ctype_digit((string) $pid)) {
                        continue;
                    }
                    $cart->update((int) $pid, (int) $qty);
                }
            }
            $this->flash('success', 'سبد خرید به‌روزرسانی شد.');
        } catch (RuntimeException $e) {
            $this->flash('error', $e->getMessage());
        }
        $this->redirect('/cart');
    }

    public function remove(array $ctx): void
    {
        $productId = (int) Request::post('product_id', '0');
        if ($productId > 0) {
            $this->c->get(CartService::class)->remove($productId);
            $this->flash('success', 'قلم از سبد حذف شد.');
        }
        $this->redirect('/cart');
    }
}
