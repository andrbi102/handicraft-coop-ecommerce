<?php

declare(strict_types=1);

namespace App\Support;

use App\Core\Money;

/**
 * کمک‌تابع‌های رندر کارت — HTML آماده با escape خودکار.
 *
 * جدا از View::render برای جلوگیری از سربار re-include در حلقه‌ها.
 *
 * @package Coop\Support
 * @version 0.1.0
 * @security-level public
 */
final class Cards
{
    /**
     * @param array<string, mixed> $p
     * @param callable(int): string $money
     */
    public static function productCard(array $p, callable $money): string
    {
        $name = htmlspecialchars((string) $p['name'], ENT_QUOTES, 'UTF-8');
        $slug = rawurlencode((string) $p['slug']);
        $price = $money((int) $p['price']);
        $stock = (int) $p['stock'];
        $thumb = (string) ($p['image'] ?? '');
        $thumbHtml = $thumb !== ''
            ? '<img src="' . htmlspecialchars('/assets/img/' . $thumb, ENT_QUOTES, 'UTF-8') . '" alt="' . $name . '">'
            : '🧵';

        $stockTag = $stock <= 0
            ? '<span class="tag out">ناموجود</span>'
            : ($stock <= 3 ? '<span class="tag low">موجودی کم</span>' : '');
        $disabled = $stock <= 0 ? 'disabled' : '';
        $pid = (int) $p['id'];

        return <<<HTML
<div class="product-card">
    <a href="/product/{$slug}" class="thumb">{$thumbHtml}</a>
    <div class="body">
        <a href="/product/{$slug}" class="name">{$name}</a>
        <div class="price">{$price}</div>
        <div>{$stockTag}</div>
        <div class="actions mt-1">
            <form action="/cart/add?id={$pid}" method="post">
                <input type="hidden" name="product_id" value="{$pid}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-primary btn-sm btn-block" {$disabled}>افزودن به سبد</button>
            </form>
        </div>
    </div>
</div>
HTML;
    }

    /**
     * @param array<string, mixed> $m
     */
    public static function memberCard(array $m): string
    {
        $name = htmlspecialchars((string) $m['full_name'], ENT_QUOTES, 'UTF-8');
        $craft = htmlspecialchars((string) ($m['craft'] ?? ''), ENT_QUOTES, 'UTF-8');
        $region = htmlspecialchars((string) ($m['region'] ?? ''), ENT_QUOTES, 'UTF-8');
        $id = (int) $m['id'];
        return <<<HTML
<div class="product-card">
    <a href="/member/{$id}" class="thumb" style="font-size:36px">🧑‍🌾</a>
    <div class="body">
        <a href="/member/{$id}" class="name">{$name}</a>
        <div class="member">{$craft}</div>
        <div class="text-muted" style="font-size:12px">{$region}</div>
    </div>
</div>
HTML;
    }
}
