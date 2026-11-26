<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Core\Request;
use App\Models\OrderModel;
use App\Models\UserModel;

/**
 * مدیریت مشتریان — فهرست و جزئیات (با سفارش‌ها).
 *
 * @package App\Controllers\Admin
 * @version 0.1.0
 * @security-level admin
 */
final class CustomerController extends AdminController
{
    public function index(array $ctx): void
    {
        $page = Request::queryInt('page', 1);
        $model = $this->c->get(UserModel::class);
        $customers = $model->customers($page, 20);
        $total = $model->countByRole('customer');
        $this->renderAdmin('admin/customers_index', [
            'section' => 'customers',
            'title' => 'مشتریان',
            'customers' => $customers,
            'page' => $page,
            'pages' => max(1, (int) ceil($total / 20)),
        ], $ctx);
    }

    public function show(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $customer = $this->c->get(UserModel::class)->find($id);
        if ($customer === null) {
            $this->flash('error', 'مشتری یافت نشد.');
            $this->redirect('/admin/customers');
        }
        $orders = $this->c->get(OrderModel::class)->forUser($id, 1, 50);
        $this->renderAdmin('admin/customers_show', [
            'section' => 'customers',
            'title' => 'مشتری: ' . $customer['name'],
            'customer' => $customer,
            'orders' => $orders,
        ], $ctx);
    }
}
