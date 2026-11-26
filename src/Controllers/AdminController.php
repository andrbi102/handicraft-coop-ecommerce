<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Config;
use App\Models\ContactMessageModel;
use App\Models\OrderModel;

/**
 * پایه کنترلرهای مدیریت — لایوت «admin» و داده‌های مشترک پنل
 * (کاربر، تعداد سفارش‌های جاری، پیام‌های خوانده‌نشده، نقش).
 *
 * @package Coop\Controllers
 * @version 0.1.0
 * @security-level admin
 */
abstract class AdminController extends Controller
{
    protected function renderAdmin(string $template, array $data, array $ctx): void
    {
        $user = $ctx['user'] ?? null;
        $data['user'] = $user;
        $data['flash'] = $ctx['flash'] ?? [];
        $data['adminPrefix'] = (string) Config::get('admin_prefix', '/admin');
        $data['roles'] = (array) Config::get('roles', []);
        $data['pendingOrders'] = $this->c->get(OrderModel::class)->countByStatus('pending');
        $data['unreadMessages'] = $this->c->get(ContactMessageModel::class)->unreadCount();
        $data['title'] = $data['title'] ?? 'پنل مدیریت';
        $data['section'] = $data['section'] ?? '';
        $this->render('admin', $template, $data);
    }
}
