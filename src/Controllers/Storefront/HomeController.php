<?php

declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Controllers\StorefrontController;
use App\Models\ProductModel;

/**
 * صفحه اصلی فروشگاه — محصولات ویژه و جدیدترین کالاها.
 *
 * @package Coop\Controllers\Storefront
 * @version 0.1.0
 * @security-level public
 */
final class HomeController extends StorefrontController
{
    public function index(array $ctx): void
    {
        $products = $this->c->get(ProductModel::class);
        $this->renderStore('storefront/home', [
            'featured' => $products->featured(8),
            'latest' => $products->latest(4),
            'title' => 'فروشگاه تعاونی نساجی دست‌ساز',
        ], $ctx);
    }
}
