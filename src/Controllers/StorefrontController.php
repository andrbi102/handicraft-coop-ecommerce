<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Services\CartService;
use App\Services\WishlistService;

/**
 * پایه کنترلرهای فروشگاه — لایوت «store» و داده‌های مشترک (کاربر، فلش،
 * شمارنده سبد و علاقه‌مندی، تنظیمات، دسته‌ها).
 *
 * @package Coop\Controllers
 * @version 0.2.0
 * @security-level public
 */
abstract class StorefrontController extends Controller
{
    protected function renderStore(string $template, array $data, array $ctx): void
    {
        $data['user'] = $ctx['user'] ?? null;
        $data['flash'] = $ctx['flash'] ?? [];
        $data['cartCount'] = $this->cartCount();
        $data['wishlistCount'] = $this->wishlistCount();
        $data['title'] = $data['title'] ?? (string) ($this->commonData()['siteName']);
        $this->render('store', $template, $data);
    }

    /**
     * رندر با لایوت احراز هویت (pa-auth-page) — کارت مرکزی، بدون نوار/فوتر.
     */
    protected function renderAuth(string $template, array $data, array $ctx): void
    {
        $data['user'] = $ctx['user'] ?? null;
        $data['flash'] = $ctx['flash'] ?? [];
        $data['title'] = $data['title'] ?? (string) ($this->commonData()['siteName']);
        $this->render('auth', $template, $data);
    }

    protected function cartCount(): int
    {
        return $this->c->get(CartService::class)->count();
    }

    protected function wishlistCount(): int
    {
        return $this->c->get(WishlistService::class)->count();
    }

    /**
     * مسیر بازگشت امن از پارامتر return (GET یا POST).
     * فقط مسیرهای محلیِ نسبی پذیرفته می‌شوند تا از Open Redirect جلوگیری شود.
     */
    protected function intendedReturn(string $fallback = '/'): string
    {
        $raw = (string) (Request::query('return', '') ?: Request::post('return', ''));
        $raw = trim($raw);
        if ($raw === '' || !str_starts_with($raw, '/')) {
            return $fallback;
        }
        // ردکردن // (protocol-relative) و scheme:// (open redirect)
        if (preg_match('#^//#', $raw) || preg_match('#^[a-z][a-z0-9+.-]*://#i', $raw)) {
            return $fallback;
        }
        return $raw;
    }

    /** redirect به مسیر بازگشت امن، یا fallback. */
    protected function redirectBack(string $fallback): void
    {
        $this->redirect($this->intendedReturn($fallback));
    }
}
