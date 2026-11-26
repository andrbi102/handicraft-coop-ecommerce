<?php

declare(strict_types=1);

namespace App\Support;

use App\Core\Money;

/**
 * کمک‌تابع‌های رندر کارت — نشانه‌گذاری PAdminLTE با escape خودکار.
 *
 * کارت‌ها درون pa-row قرار می‌گیرند؛ هر کارت خود pa-col-N را هم
 * شامل می‌شود تا یک حلقهٔ ساده کافی باشد.
 *
 * @package Coop\Support
 * @version 0.2.0
 * @security-level public
 */
final class Cards
{
    /** طیف رنگی برای تصویرسازی تصادفی (CSS gradient). */
    private const GRADIENTS = [
        ['#6a4c93', '#1982c4'],
        ['#1982c4', '#8ac926'],
        ['#ff595e', '#ffca3a'],
        ['#ffca3a', '#8ac926'],
        ['#6a4c93', '#ff595e'],
        ['#1982c4', '#6a4c93'],
    ];

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
        $pid = (int) $p['id'];

        $grad = self::GRADIENTS[crc32((string) $pid) % count(self::GRADIENTS)];
        $csrfToken = \Csrf::token();

        // نشان موجودی
        if ($stock <= 0) {
            $badge = '<span class="pa-product-badge pa-discount" style="background:#dc3545">ناموجود</span>';
            $disabled = 'disabled';
        } elseif ($stock <= 3) {
            $badge = '<span class="pa-product-badge pa-discount">موجودی کم</span>';
            $disabled = '';
        } else {
            $badge = '';
            $disabled = '';
        }

        return <<<HTML
<div class="pa-col-4">
    <div class="pa-product-card pa-hover-lift">
        <div class="pa-product-thumb" style="background:linear-gradient(135deg,{$grad[0]},{$grad[1]});display:grid;place-items:center;color:#fff">
            <a href="/product/{$slug}" style="display:grid;place-items:center;width:100%;height:100%;color:inherit">
                <span data-pa-icon="package_alt" data-pa-icon-size="56"></span>
            </a>
            {$badge}
        </div>
        <div class="pa-product-body">
            <h3 class="pa-product-name"><a href="/product/{$slug}">{$name}</a></h3>
            <div class="pa-product-price"><span class="pa-now">{$price}</span></div>
            <form action="/cart/add?id={$pid}" method="post">
                <input type="hidden" name="_csrf" value="{$csrfToken}">
                <input type="hidden" name="product_id" value="{$pid}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="pa-btn pa-btn-outline pa-btn-sm pa-btn-block" {$disabled} data-pa-icon="shopping_cart" data-pa-icon-size="14"> افزودن به سبد</button>
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
        $grad = self::GRADIENTS[crc32((string) $id) % count(self::GRADIENTS)];

        return <<<HTML
<div class="pa-col-3">
    <div class="pa-product-card pa-hover-lift">
        <div class="pa-product-thumb" style="background:linear-gradient(135deg,{$grad[0]},{$grad[1]});display:grid;place-items:center;color:#fff">
            <a href="/member/{$id}" style="display:grid;place-items:center;width:100%;height:100%;color:inherit">
                <span data-pa-icon="users_alt" data-pa-icon-size="56"></span>
            </a>
        </div>
        <div class="pa-product-body">
            <h3 class="pa-product-name"><a href="/member/{$id}">{$name}</a></h3>
            <div class="pa-product-cat">{$craft}</div>
            <div class="pa-text-muted pa-fs-sm">{$region}</div>
        </div>
    </div>
</div>
HTML;
    }
}
