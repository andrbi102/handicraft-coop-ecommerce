<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Core\Money;
use App\Core\Request;
use App\Core\Slug;
use App\Models\CategoryModel;
use App\Models\MemberModel;
use App\Models\ProductModel;
use RuntimeException;
use Validator;

/**
 * مدیریت محصولات — ایجاد، ویرایش، بایگانی، تنظیم موجودی.
 *
 * @package Coop\Controllers\Admin
 * @version 0.1.0
 * @security-level admin
 */
final class ProductController extends AdminController
{
    public function index(array $ctx): void
    {
        $page = Request::queryInt('page', 1);
        $products = $this->c->get(ProductModel::class)->allForAdmin($page, 15);
        $pages = (int) ceil($this->c->get(ProductModel::class)->count() / 15);
        $this->renderAdmin('admin/products_index', [
            'section' => 'products',
            'title' => 'مدیریت محصولات',
            'products' => $products,
            'page' => $page,
            'pages' => max(1, $pages),
        ], $ctx);
    }

    public function create(array $ctx): void
    {
        $this->renderForm($ctx, null, 'admin/products_form', 'محصول جدید');
    }

    public function edit(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $product = $this->c->get(ProductModel::class)->find($id);
        if ($product === null) {
            $this->flash('error', 'محصول یافت نشد.');
            $this->redirect('/admin/products');
        }
        $this->renderForm($ctx, $product, 'admin/products_form', 'ویرایش محصول');
    }

    private function renderForm(array $ctx, ?array $product, string $template, string $title): void
    {
        $categories = $this->c->get(CategoryModel::class)->all();
        $members = $this->c->get(MemberModel::class)->allActive();
        $this->renderAdmin($template, [
            'section' => 'products',
            'title' => $title,
            'product' => $product,
            'categories' => $categories,
            'members' => $members,
        ], $ctx);
    }

    public function store(array $ctx): void
    {
        $data = $this->validate($ctx, null);
        if ($data === null) {
            $this->redirect('/admin/products/create');
        }
        $this->c->get(ProductModel::class)->create($data);
        $this->flash('success', 'محصول ایجاد شد.');
        $this->redirect('/admin/products');
    }

    public function update(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $product = $this->c->get(ProductModel::class)->find($id);
        if ($product === null) {
            $this->flash('error', 'محصول یافت نشد.');
            $this->redirect('/admin/products');
        }
        $data = $this->validate($ctx, $product);
        if ($data === null) {
            $this->redirect('/admin/products/' . $id . '/edit');
        }
        $this->c->get(ProductModel::class)->update($id, $data);
        $this->flash('success', 'محصول به‌روزرسانی شد.');
        $this->redirect('/admin/products');
    }

    /** @return array<string, mixed>|null null یعنی اعتبارسنجی ناموفق */
    private function validate(array $ctx, ?array $existing): ?array
    {
        $name = (string) Request::post('name', '');
        $categoryId = (int) Request::post('category_id', '0');
        $price = Money::parse((string) Request::post('price', '0'));
        $cost = Money::parse((string) Request::post('cost', '0'));
        $stock = (int) Request::post('stock', '0');
        $v = new Validator(['name' => $name, 'category_id' => $categoryId, 'price' => $price]);
        $v->required('name')->length('name', 3, 150)
          ->required('category_id')->int('category_id', 1)
          ->required('price')->int('price', 0);
        if ($v->fails()) {
            $this->flash('error', 'لطفاً نام، دسته‌بندی و قیمت را معتبر وارد کنید.');
            return null;
        }
        $slug = Slug::make($name);
        $exceptId = $existing !== null ? (int) $existing['id'] : null;
        $products = $this->c->get(ProductModel::class);
        if ($products->slugExists($slug, $exceptId)) {
            $slug = $slug . '-' . bin2hex(random_bytes(2));
        }
        return [
            'name' => $name,
            'slug' => $slug,
            'category_id' => $categoryId,
            'member_id' => (int) Request::post('member_id', '0'),
            'description' => (string) Request::post('description', ''),
            'price' => $price,
            'cost' => $cost,
            'stock' => $stock,
            'sku' => (string) Request::post('sku', ''),
            'weight_grams' => (int) Request::post('weight_grams', '0'),
            'material' => (string) Request::post('material', ''),
            'dimensions' => (string) Request::post('dimensions', ''),
            'image' => (string) ($existing['image'] ?? ''),
            'is_featured' => Request::post('is_featured', '0') === '1' ? 1 : 0,
            'status' => (string) Request::post('status', 'active'),
        ];
    }

    public function delete(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $this->c->get(ProductModel::class)->delete($id);
        $this->flash('success', 'محصول بایگانی شد.');
        $this->redirect('/admin/products');
    }

    public function adjustStock(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $delta = (int) Request::post('delta', '0');
        $this->c->get(ProductModel::class)->adjustStock($id, $delta);
        $this->flash('success', 'موجودی به‌روزرسانی شد.');
        $this->redirect('/admin/products');
    }
}
