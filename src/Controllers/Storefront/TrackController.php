<?php

declare(strict_types=1);

namespace App\Controllers\Storefront;

use App\Controllers\StorefrontController;
use App\Core\Request;
use App\Models\OrderModel;
use Validator;

/**
 * پیگیری سفارش — عمومی، بدون نیاز به ورود.
 *
 * با شماره سفارش + تلفن ثبت‌شده تطبیق داده می‌شود تا از شمارش‌سازی جلوگیری شود.
 *
 * @package Coop\Controllers\Storefront
 * @version 0.1.0
 * @security-level public
 */
final class TrackController extends StorefrontController
{
    public function index(array $ctx): void
    {
        $number = trim((string) Request::query('number', ''));
        $order = null;
        $items = [];
        $phone = '';

        if ($number !== '') {
            $order = $this->lookup($number, (string) Request::query('phone', ''));
            if ($order !== null) {
                $items = $this->c->get(OrderModel::class)->items((int) $order['id']);
            }
        }

        $this->renderStore('storefront/track', [
            'title' => 'پیگیری سفارش',
            'number' => $number,
            'phone' => $phone !== '' ? $phone : (string) Request::query('phone', ''),
            'order' => $order,
            'items' => $items,
            'searched' => $number !== '',
        ], $ctx);
    }

    public function lookupAction(array $ctx): void
    {
        $number = trim((string) Request::post('number', ''));
        $phone = trim((string) Request::post('phone', ''));
        $this->redirect('/track?number=' . rawurlencode($number) . '&phone=' . rawurlencode($phone));
    }

    /** پیدا کردن سفارش با تطبیق شماره + تلفن. */
    private function lookup(string $number, string $phone): ?array
    {
        $v = new Validator(['number' => $number, 'phone' => $phone]);
        $v->required('number')->length('number', 4, 40)->required('phone')->length('phone', 4, 20);
        if ($v->fails()) {
            $this->flash('error', 'شماره سفارش و تلفن را کامل وارد کنید.');
            return null;
        }
        $order = $this->c->get(OrderModel::class)->findByNumber($number);
        if ($order === null) {
            $this->flash('error', 'سفارشی با این شماره یافت نشد.');
            return null;
        }
        // تطبیق تلفن (پایانی یا کامل) برای جلوگیری از شمارش‌سازی
        $stored = (string) ($order['shipping_phone'] ?? '');
        $needle = preg_replace('/\D/', '', $phone);
        $haystack = preg_replace('/\D/', '', $stored);
        if ($needle === '' || $haystack === '' || !str_contains($haystack, $needle)) {
            $this->flash('error', 'تلفن با سفارش مطابقت ندارد.');
            return null;
        }
        return $order;
    }
}
