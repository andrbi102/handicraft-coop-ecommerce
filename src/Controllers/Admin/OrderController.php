<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Core\Config;
use App\Core\Request;
use App\Models\OrderModel;
use App\Services\SettlementService;
use Validator;

/**
 * مدیریت سفارش‌ها — فهرست، جزئیات، تغییر وضعیت، تحویل (با تولید تسویه).
 *
 * @package Coop\Controllers\Admin
 * @version 0.1.0
 * @security-level admin
 */
final class OrderController extends AdminController
{
    public function index(array $ctx): void
    {
        $status = (string) Request::query('status', '');
        $page = Request::queryInt('page', 1);
        $model = $this->c->get(OrderModel::class);
        $orders = $model->allForAdmin($page, 20, $status);
        $total = $status !== '' ? $model->countByStatus($status) : $model->count();
        $this->renderAdmin('admin/orders_index', [
            'section' => 'orders',
            'title' => 'مدیریت سفارش‌ها',
            'orders' => $orders,
            'status' => $status,
            'page' => $page,
            'pages' => max(1, (int) ceil($total / 20)),
            'statusLabels' => $this->statusLabels(),
        ], $ctx);
    }

    public function show(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $model = $this->c->get(OrderModel::class);
        $order = $model->find($id);
        if ($order === null) {
            $this->flash('error', 'سفارش یافت نشد.');
            $this->redirect('/admin/orders');
        }
        $this->renderAdmin('admin/orders_show', [
            'section' => 'orders',
            'title' => 'سفارش ' . $order['order_number'],
            'order' => $order,
            'items' => $model->items($id),
            'statusLabels' => $this->statusLabels(),
        ], $ctx);
    }

    public function updateStatus(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $status = (string) Request::post('status', '');
        $v = new Validator(['status' => $status]);
        $v->in('status', array_keys($this->statusLabels()));
        if ($v->fails()) {
            $this->flash('error', 'وضعیت نامعتبر است.');
            $this->redirect('/admin/orders/' . $id);
        }
        $this->c->get(OrderModel::class)->updateStatus($id, $status);
        $this->flash('success', 'وضعیت سفارش به‌روزرسانی شد.');
        $this->redirect('/admin/orders/' . $id);
    }

    public function deliver(array $ctx): void
    {
        $id = (int) ($ctx['params']['id'] ?? 0);
        $order = $this->c->get(OrderModel::class)->find($id);
        if ($order === null) {
            $this->flash('error', 'سفارش یافت نشد.');
            $this->redirect('/admin/orders');
        }
        $this->c->get(OrderModel::class)->updateStatus($id, 'delivered');
        $created = $this->c->get(SettlementService::class)->generateForOrder($id);
        $this->flash('success', 'سفارش تحویل داده شد و ' . $created . ' تسویه معلق ایجاد شد.');
        $this->redirect('/admin/orders/' . $id);
    }

    /** @return array<string, string> */
    private function statusLabels(): array
    {
        return [
            'pending' => 'در انتظار',
            'paid' => 'پرداخت‌شده',
            'processing' => 'در حال آماده‌سازی',
            'shipped' => 'ارسال‌شده',
            'delivered' => 'تحویل‌شده',
            'cancelled' => 'لغو‌شده',
            'refunded' => 'بازگشت‌دار',
        ];
    }
}
