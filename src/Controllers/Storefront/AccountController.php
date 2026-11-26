<?php

declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Controllers\StorefrontController;
use App\Core\Auth;
use Crypto;
use App\Core\Request;
use App\Models\OrderModel;
use App\Models\UserModel;

/**
 * حساب کاربری مشتری — سفارش‌ها و پروفایل.
 *
 * @package Coop\Controllers\Storefront
 * @version 0.1.0
 * @security-level private
 */
final class AccountController extends StorefrontController
{
    public function index(array $ctx): void
    {
        $user = $ctx['user'];
        $orders = $this->c->get(OrderModel::class)->forUser((int) $user['id'], Request::queryInt('page', 1), 10);
        $this->renderStore('storefront/account_orders', [
            'orders' => $orders,
            'title' => 'سفارش‌های من',
        ], $ctx);
    }

    public function order(array $ctx): void
    {
        $user = $ctx['user'];
        $number = (string) ($ctx['params']['number'] ?? '');
        $order = $this->c->get(OrderModel::class)->findByNumber($number);
        if ($order === null || (int) $order['user_id'] !== (int) $user['id']) {
            http_response_code(404);
            $this->renderStore('errors/404', ['title' => 'سفارش یافت نشد'], $ctx);
            return;
        }
        $items = $this->c->get(OrderModel::class)->items((int) $order['id']);
        $this->renderStore('storefront/account_order', [
            'order' => $order,
            'items' => $items,
            'title' => 'جزئیات سفارش ' . $order['order_number'],
        ], $ctx);
    }

    public function profile(array $ctx): void
    {
        $user = $ctx['user'];
        // بارگذاری کامل برای نمایش
        $this->renderStore('storefront/account_profile', [
            'profile' => $user,
            'title' => 'پروفایل',
        ], $ctx);
    }

    public function updateProfile(array $ctx): void
    {
        $user = $ctx['user'];
        $name = (string) Request::post('name', '');
        $phone = (string) Request::post('phone', '');
        if (mb_strlen($name) < 3) {
            $this->flash('error', 'نام معتبر نیست.');
            $this->redirect('/account/profile');
        }
        $this->c->get(UserModel::class)->updateProfile((int) $user['id'], $name, $phone);
        $this->flash('success', 'پروفایل به‌روزرسانی شد.');
        $this->redirect('/account/profile');
    }

    public function changePassword(array $ctx): void
    {
        $user = $ctx['user'];
        $current = (string) Request::post('current_password', '');
        $new = (string) Request::post('new_password', '');
        $confirm = (string) Request::post('new_password_confirm', '');
        if (mb_strlen($new) < 8) {
            $this->flash('error', 'رمز جدید باید حداقل ۸ نویسه باشد.');
            $this->redirect('/account/profile');
        }
        if ($new !== $confirm) {
            $this->flash('error', 'رمز جدید و تکرار آن یکسان نیستند.');
            $this->redirect('/account/profile');
        }
        $users = $this->c->get(UserModel::class);
        $row = $users->findByEmail((string) $user['email']);
        if ($row === null || !Crypto::passwordVerify($current, (string) $row['password_hash'])) {
            $this->flash('error', 'رمز فعلی نادرست است.');
            $this->redirect('/account/profile');
        }
        $users->updatePassword((int) $user['id'], Crypto::passwordHash($new));
        $this->flash('success', 'رمز عبور تغییر کرد.');
        $this->redirect('/account/profile');
    }
}
