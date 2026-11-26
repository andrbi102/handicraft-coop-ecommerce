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
        $phone = trim((string) Request::query('phone', ''));
        $order = null;
        $items = [];
        $error = null;

        if ($number !== '') {
            $result = $this->lookup($number, $phone);
            $order = $result['order'];
            $error = $result['error'];
            if ($order !== null) {
                $items = $this->c->get(OrderModel::class)->items((int) $order['id']);
            }
        }

        $this->renderStore('storefront/track', [
            'title' => 'پیگیری سفارش',
            'number' => $number,
            'phone' => $phone,
            'order' => $order,
            'items' => $items,
            'searched' => $number !== '',
            'trackError' => $error,
        ], $ctx);
    }

    public function lookupAction(array $ctx): void
    {
        $number = trim((string) Request::post('number', ''));
        $phone = trim((string) Request::post('phone', ''));
        $this->redirect('/track?number=' . rawurlencode($number) . '&phone=' . rawurlencode($phone));
    }

    /**
     * پیدا کردن سفارش با تطبیق شماره + تلفن.
     * خطا به‌صورت مستقیم (نه flash) برگردانده می‌شود چون این یک درخواستِ
     * GETِ هم‌زمان است و flash در همان درخواست نمایش داده نمی‌شود.
     *
     * @return array{order: array<string, mixed>|null, error: string|null}
     */
    private function lookup(string $number, string $phone): array
    {
        $v = new Validator(['number' => $number, 'phone' => $phone]);
        $v->required('number')->length('number', 4, 40)->required('phone')->length('phone', 7, 20);
        if ($v->fails()) {
            return ['order' => null, 'error' => 'شماره سفارش و تلفن را کامل وارد کنید (تلفن حداقل ۷ رقم).'];
        }
        $order = $this->c->get(OrderModel::class)->findByNumber($number);
        if ($order === null) {
            return ['order' => null, 'error' => 'سفارشی با این شماره و تلفن یافت نشد.'];
        }
        // تطبیق تلفن: تطبیق پایانِ تلفن ثبت‌شده با حداقل ۷ رقم — جلوگیری از
        // شمارش‌سازی با زیررشتهٔ کوتاه (مثلاً ۴ رقم).
        $stored = (string) ($order['shipping_phone'] ?? '');
        $needle = preg_replace('/\D/', '', $phone);
        $haystack = preg_replace('/\D/', '', $stored);
        if ($needle === '' || $haystack === '' || strlen($needle) < 7 || !str_ends_with($haystack, $needle)) {
            return ['order' => null, 'error' => 'سفارشی با این شماره و تلفن یافت نشد.'];
        }
        return ['order' => $order, 'error' => null];
    }
}
