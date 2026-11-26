<?php

declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Controllers\StorefrontController;
use App\Core\Request;
use App\Models\CategoryModel;
use App\Models\ProductModel;

/**
 * کاتالوگ محصولات — فیلتر دسته، جستجو، مرتب‌سازی، صفحه‌بندی.
 *
 * @package Coop\Controllers\Storefront
 * @version 0.1.0
 * @security-level public
 */
final class CatalogController extends StorefrontController
{
    private const PER_PAGE = 12;

    public function index(array $ctx): void
    {
        $this->renderList($ctx, null, Request::query('q'), Request::query('sort', 'new'), Request::queryInt('page', 1));
    }

    public function category(array $ctx): void
    {
        $slug = (string) ($ctx['params']['slug'] ?? '');
        $category = $this->c->get(CategoryModel::class)->findBySlug($slug);
        if ($category === null) {
            http_response_code(404);
            $this->renderStore('errors/404', ['title' => 'یافت نشد'], $ctx);
            return;
        }
        $this->renderList($ctx, (int) $category['id'], Request::query('q'), Request::query('sort', 'new'), Request::queryInt('page', 1), $category);
    }

    private function renderList(array $ctx, ?int $categoryId, ?string $search, string $sort, int $page, ?array $category = null): void
    {
        $products = $this->c->get(ProductModel::class);
        $items = $products->catalog($categoryId, $search, $sort, $page, self::PER_PAGE);
        $total = $products->catalogCount($categoryId, $search);
        $pages = (int) ceil($total / self::PER_PAGE);

        $this->renderStore('storefront/catalog', [
            'products' => $items,
            'total' => $total,
            'page' => max(1, $page),
            'pages' => max(1, $pages),
            'perPage' => self::PER_PAGE,
            'sort' => $sort,
            'q' => (string) $search,
            'currentCategory' => $category,
            'categoryId' => $categoryId,
            'title' => $category !== null ? ('دسته‌بندی: ' . $category['name']) : 'کاتالوگ محصولات',
        ], $ctx);
    }
}
