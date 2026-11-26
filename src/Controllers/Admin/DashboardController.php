<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\MemberModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\SettlementModel;

/**
 * داشبورد مدیریت — KPIها، سفارش‌های اخیر، نمودار فروش، موجودی پایین.
 *
 * @package Coop\Controllers\Admin
 * @version 0.1.0
 * @security-level admin
 */
final class DashboardController extends AdminController
{
    public function index(array $ctx): void
    {
        $orders = $this->c->get(OrderModel::class);
        $products = $this->c->get(ProductModel::class);
        $members = $this->c->get(MemberModel::class);
        $settlements = $this->c->get(SettlementModel::class);

        $this->renderAdmin('admin/dashboard', [
            'section' => 'dashboard',
            'title' => 'داشبورد',
            'kpi' => [
                'revenue' => $orders->totalRevenue(),
                'orders' => $orders->count(),
                'pendingOrders' => $orders->countByStatus('pending'),
                'products' => $products->count("status = ?", ['active']) ,
                'members' => $members->count("status = ?", ['active']),
                'pendingSettlements' => $settlements->totalPending(),
                'shareCapital' => $members->totalShareCapital(),
            ],
            'recentOrders' => $orders->recent(5),
            'dailyRevenue' => $orders->dailyRevenue(7),
            'lowStock' => $products->lowStock((int) (\App\Core\Config::get('shop.low_stock_threshold', 3))),
        ], $ctx);
    }
}
