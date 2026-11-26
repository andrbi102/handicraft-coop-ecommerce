<?php

declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Controllers\StorefrontController;
use App\Core\Request;
use App\Services\CartService;
use App\Services\OrderService;
use RuntimeException;
use Validator;

/**
 * تسویحه حساب — فرم، اعمال کوپن، ثبت سفارش، تأیید نهایی.
 *
 * @package Coop\Controllers\Storefront
 * @version 0.1.0
 * @security-level private
 */
final class CheckoutController extends StorefrontController
{
    public function index(array $ctx): void
    {
        $cart = $this->c->get(CartService::class);
        $items = $cart->items();
        if ($items === []) {
            $this->flash('info', 'سبد خرید شما خالی است.');
            $this->redirect('/cart');
        }
        $subtotal = $cart->subtotal();
        $service = $this->c->get(OrderService::class);
        $couponCode = (string) ($_SESSION['checkout_coupon'] ?? '');
        $couponEval = ['ok' => false, 'discount' => 0, 'reason' => '', 'coupon' => null];
        if ($couponCode !== '') {
            $couponEval = $service->evaluateCoupon($couponCode, $subtotal);
        }
        $shipping = $service->shippingCost($subtotal);
        $total = max(0, $subtotal - $couponEval['discount']) + $shipping;
        $this->renderStore('storefront/checkout', [
            'items' => $items,
            'subtotal' => $subtotal,
            'couponCode' => $couponCode,
            'couponEval' => $couponEval,
            'shipping' => $shipping,
            'total' => $total,
            'title' => 'تسویحه حساب',
        ], $ctx);
    }

    public function applyCoupon(array $ctx): void
    {
        $code = (string) Request::post('coupon_code', '');
        $_SESSION['checkout_coupon'] = $code;
        if ($code !== '') {
            $this->flash('success', 'کد تخفیف اعمال شد.');
        } else {
            $this->flash('info', 'کد تخفیف حذف شد.');
        }
        $this->redirect('/checkout');
    }

    public function place(array $ctx): void
    {
        $user = $ctx['user'];
        $cart = $this->c->get(CartService::class);
        $items = $cart->items();
        if ($items === []) {
            $this->flash('error', 'سبد خرید خالی است.');
            $this->redirect('/cart');
        }

        $name = (string) Request::post('name', '');
        $phone = (string) Request::post('phone', '');
        $address = (string) Request::post('address', '');
        $city = (string) Request::post('city', '');
        $postal = (string) Request::post('postal_code', '');
        $notes = (string) Request::post('notes', '');

        $v = new Validator([
            'name' => $name, 'phone' => $phone, 'address' => $address,
            'city' => $city, 'postal_code' => $postal,
        ]);
        $v->required('name')->length('name', 3, 100)
          ->required('phone')->length('phone', 4, 20)
          ->required('address')->length('address', 5, 300)
          ->required('city')->length('city', 2, 60)
          ->required('postal_code')->length('postal_code', 5, 20);
        if ($v->fails()) {
            $this->flash('error', 'لطفاً نشانی تحویل را کامل و معتبر وارد کنید.');
            $this->redirect('/checkout');
        }

        $subtotal = $cart->subtotal();
        $service = $this->c->get(OrderService::class);
        $couponCode = (string) ($_SESSION['checkout_coupon'] ?? '');
        $couponEval = $couponCode !== ''
            ? $service->evaluateCoupon($couponCode, $subtotal)
            : ['ok' => false, 'discount' => 0, 'reason' => '', 'coupon' => null];

        try {
            $orderId = $service->place((int) $user['id'], $items, [
                'name' => $name, 'phone' => $phone, 'address' => $address,
                'city' => $city, 'postal_code' => $postal, 'notes' => $notes,
            ], $couponEval);
        } catch (RuntimeException $e) {
            $this->flash('error', $e->getMessage());
            $this->redirect('/checkout');
        }

        $cart->clear();
        unset($_SESSION['checkout_coupon']);
        $order = $this->c->get(\App\Models\OrderModel::class)->find($orderId);
        $this->flash('success', 'سفارش شما با موفقیت ثبت شد.');
        $this->redirect('/order/' . rawurlencode((string) ($order['order_number'] ?? '')) . '/success');
    }

    public function success(array $ctx): void
    {
        $user = $ctx['user'];
        $number = (string) ($ctx['params']['number'] ?? '');
        $order = $this->c->get(\App\Models\OrderModel::class)->findByNumber($number);
        if ($order === null || (int) $order['user_id'] !== (int) $user['id']) {
            http_response_code(404);
            $this->renderStore('errors/404', ['title' => 'سفارش یافت نشد'], $ctx);
            return;
        }
        $items = $this->c->get(\App\Models\OrderModel::class)->items((int) $order['id']);
        $this->renderStore('storefront/order_success', [
            'order' => $order,
            'items' => $items,
            'title' => 'سفارش ثبت شد',
        ], $ctx);
    }
}
