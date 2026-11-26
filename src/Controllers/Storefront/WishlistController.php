<?php

declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Controllers\StorefrontController;
use App\Core\Request;
use App\Services\WishlistService;
use RuntimeException;

/**
 * علاقه‌مندی‌ها — افزودن/حذف/مشاهده (مبتنی بر نشست).
 *
 * @package Coop\Controllers\Storefront
 * @version 0.1.0
 * @security-level public
 */
final class WishlistController extends StorefrontController
{
    public function index(array $ctx): void
    {
        $wishlist = $this->c->get(WishlistService::class);
        $this->renderStore('storefront/wishlist', [
            'items' => $wishlist->items(),
            'count' => $wishlist->count(),
            'title' => 'علاقه‌مندی‌ها',
        ], $ctx);
    }

    public function add(array $ctx): void
    {
        $productId = Request::queryInt('id', 0);
        if ($productId <= 0) {
            $productId = (int) Request::post('product_id', '0');
        }
        try {
            $this->c->get(WishlistService::class)->add($productId);
            $this->flash('success', 'به علاقه‌مندی‌ها اضافه شد.');
        } catch (RuntimeException $e) {
            $this->flash('error', $e->getMessage());
        }
        // بازگشت به صفحهٔ مبدأ (مثلاً صفحهٔ محصول) اگر return داده شده
        $this->redirectBack('/wishlist');
    }

    public function remove(array $ctx): void
    {
        $productId = (int) Request::post('product_id', '0');
        if ($productId > 0) {
            $this->c->get(WishlistService::class)->remove($productId);
            $this->flash('success', 'از علاقه‌مندی‌ها حذف شد.');
        }
        $this->redirect('/wishlist');
    }
}
