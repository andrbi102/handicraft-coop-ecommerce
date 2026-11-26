<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Core\Config;
use App\Models\MemberModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\SettlementModel;

/**
 * گزارش‌های مدیریتی — فروش، موجودی، عملکرد اعضا.
 *
 * @package App\Controllers\Admin
 * @version 0.1.0
 * @security-level admin
 */
final class ReportController extends AdminController
{
    public function index(array $ctx): void
    {
        $orders = $this->c->get(OrderModel::class);
        $this->renderAdmin('admin/reports_index', [
            'section' => 'reports',
            'title' => 'گزارش‌ها',
            'revenue' => $orders->totalRevenue(),
            'ordersCount' => $orders->count(),
            'dailyRevenue' => $orders->dailyRevenue(14),
        ], $ctx);
    }

    public function sales(array $ctx): void
    {
        $db = $this->db();
        // پرفروش‌ترین محصولات — ستون‌ها صریح (بدون SELECT *)
        $topProducts = $db->query(
            "SELECT oi.product_id, oi.product_name, SUM(oi.quantity) AS qty, SUM(oi.subtotal) AS revenue
             FROM order_items oi
             INNER JOIN orders o ON o.id = oi.order_id
             WHERE o.status IN ('paid','shipped','delivered')
             GROUP BY oi.product_id, oi.product_name
             ORDER BY qty DESC
             LIMIT 10",
        );
        // فروش بر اساس وضعیت
        $byStatus = $db->query(
            "SELECT o.status, COUNT(*) AS cnt, COALESCE(SUM(o.total), 0) AS total
             FROM orders o GROUP BY o.status ORDER BY total DESC",
        );
        $this->renderAdmin('admin/reports_sales', [
            'section' => 'reports',
            'title' => 'گزارش فروش',
            'topProducts' => $topProducts,
            'byStatus' => $byStatus,
        ], $ctx);
    }

    public function inventory(array $ctx): void
    {
        $products = $this->c->get(ProductModel::class);
        $threshold = (int) Config::get('shop.low_stock_threshold', 3);
        $this->renderAdmin('admin/reports_inventory', [
            'section' => 'reports',
            'title' => 'گزارش موجودی',
            'lowStock' => $products->lowStock($threshold),
            'threshold' => $threshold,
            'totalActive' => $products->count("status = ?", ['active']),
        ], $ctx);
    }

    public function members(array $ctx): void
    {
        $db = $this->db();
        $rows = $db->query(
            "SELECT m.id, m.full_name, m.craft, m.share_capital,
                    COALESCE(SUM(CASE WHEN s.status = 'pending' THEN s.amount ELSE 0 END), 0) AS pending,
                    COALESCE(SUM(CASE WHEN s.status = 'paid' THEN s.amount ELSE 0 END), 0) AS paid,
                    (SELECT COUNT(*) FROM products p WHERE p.member_id = m.id AND p.status = 'active') AS product_count
             FROM members m
             LEFT JOIN settlements s ON s.member_id = m.id
             WHERE m.status = 'active'
             GROUP BY m.id, m.full_name, m.craft, m.share_capital
             ORDER BY paid DESC",
        );
        $totalShare = $this->c->get(MemberModel::class)->totalShareCapital();
        $totalPending = $this->c->get(SettlementModel::class)->totalPending();
        $totalPaid = $this->c->get(SettlementModel::class)->totalPaid();
        $this->renderAdmin('admin/reports_members', [
            'section' => 'reports',
            'title' => 'عملکرد اعضا',
            'rows' => $rows,
            'totalShare' => $totalShare,
            'totalPending' => $totalPending,
            'totalPaid' => $totalPaid,
        ], $ctx);
    }
}
