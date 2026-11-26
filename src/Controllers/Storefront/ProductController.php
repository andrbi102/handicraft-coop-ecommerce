<?php

declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Controllers\StorefrontController;
use App\Models\MemberModel;
use App\Models\ProductModel;

/**
 * صفحه جزئیات محصول.
 *
 * @package Coop\Controllers\Storefront
 * @version 0.1.0
 * @security-level public
 */
final class ProductController extends StorefrontController
{
    public function show(array $ctx): void
    {
        $slug = (string) ($ctx['params']['slug'] ?? '');
        $products = $this->c->get(ProductModel::class);
        $product = $products->findBySlug($slug);
        if ($product === null || $product['status'] !== 'active') {
            http_response_code(404);
            $this->renderStore('errors/404', ['title' => 'محصول یافت نشد'], $ctx);
            return;
        }
        $member = null;
        if (!empty($product['member_id'])) {
            $member = $this->c->get(MemberModel::class)->find((int) $product['member_id']);
        }
        $related = $products->related((int) $product['category_id'], (int) $product['id'], 4);
        $this->renderStore('storefront/product', [
            'product' => $product,
            'member' => $member,
            'related' => $related,
            'title' => $product['name'],
        ], $ctx);
    }
}
