<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CartService;

/**
 * پایه کنترلرهای فروشگاه — لایوت «store» و داده‌های مشترک (کاربر، فلش،
 * شمارنده سبد، تنظیمات، دسته‌ها).
 *
 * @package Coop\Controllers
 * @version 0.1.0
 * @security-level public
 */
abstract class StorefrontController extends Controller
{
    protected function renderStore(string $template, array $data, array $ctx): void
    {
        $data['user'] = $ctx['user'] ?? null;
        $data['flash'] = $ctx['flash'] ?? [];
        $data['cartCount'] = $this->cartCount();
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
}
