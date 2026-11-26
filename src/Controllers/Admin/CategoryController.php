<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Core\Request;
use App\Core\Slug;
use App\Models\CategoryModel;
use Validator;

/**
 * مدیریت دسته‌بندی‌ها.
 *
 * @package Coop\Controllers\Admin
 * @version 0.1.0
 * @security-level admin
 */
final class CategoryController extends AdminController
{
    public function index(array $ctx): void
    {
        $this->renderAdmin('admin/categories_index', [
            'section' => 'categories',
            'title' => 'دسته‌بندی‌ها',
            'categories' => $this->c->get(CategoryModel::class)->all(),
        ], $ctx);
    }

    public function store(array $ctx): void
    {
        $name = (string) Request::post('name', '');
        $v = new Validator(['name' => $name]);
        $v->required('name')->length('name', 2, 80);
        if ($v->fails()) {
                    $this->flashOld();
$this->flash('error', 'نام دسته معتبر نیست.');$this->flash('error', 'نام دسته معتبر نیست.');
            $this->redirect('/admin/categories');
        }
        $slug = Slug::make($name);
        $model = $this->c->get(CategoryModel::class);
        if ($model->slugExists($slug)) {
            $slug = $slug . '-' . bin2hex(random_bytes(2));
        }
        $model->create([
            'name' => $name,
            'slug' => $slug,
            'description' => (string) Request::post('description', ''),
            'parent_id' => (int) Request::post('parent_id', '0'),
            'sort_order' => (int) Request::post('sort_order', '0'),
        ]);
        $this->flash('success', 'دسته‌بندی ایجاد شد.');
        $this->redirect('/admin/categories');
    }

    public function edit(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $category = $this->c->get(CategoryModel::class)->find($id);
        if ($category === null) {
            $this->flash('error', 'دسته یافت نشد.');
            $this->redirect('/admin/categories');
        }
        $this->renderAdmin('admin/categories_form', [
            'section' => 'categories',
            'title' => 'ویرایش دسته',
            'category' => $category,
            'categories' => $this->c->get(CategoryModel::class)->all(),
        ], $ctx);
    }

    public function update(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $category = $this->c->get(CategoryModel::class)->find($id);
        if ($category === null) {
            $this->flash('error', 'دسته یافت نشد.');
            $this->redirect('/admin/categories');
        }
        $name = (string) Request::post('name', '');
        $slug = Slug::make($name);
        $model = $this->c->get(CategoryModel::class);
        if ($model->slugExists($slug, $id)) {
            $slug = $slug . '-' . bin2hex(random_bytes(2));
        }
        $model->update($id, [
            'name' => $name,
            'slug' => $slug,
            'description' => (string) Request::post('description', ''),
            'parent_id' => (int) Request::post('parent_id', '0'),
            'sort_order' => (int) Request::post('sort_order', '0'),
        ]);
        $this->flash('success', 'دسته‌بندی به‌روزرسانی شد.');
        $this->redirect('/admin/categories');
    }

    public function delete(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        // جلوگیری از حذف دسته دارای محصول
        $model = $this->c->get(CategoryModel::class);
        if ($model->productCount($id) > 0) {
            $this->flash('error', 'این دسته دارای محصول است و قابل حذف نیست.');
            $this->redirect('/admin/categories');
        }
        $this->db()->execute('DELETE FROM categories WHERE id = ?', [$id]);
        $this->flash('success', 'دسته‌بندی حذف شد.');
        $this->redirect('/admin/categories');
    }
}
