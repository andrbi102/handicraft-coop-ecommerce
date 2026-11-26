<?php

declare(strict_types=1);

/**
 * سبد خرید — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

/** @var array<string, mixed> $__data */

$items = $__data['items'] ?? [];
$subtotal = (int) ($__data['subtotal'] ?? 0);
$money = $__data['money'];
$toFa = $__data['toFa'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>سبد خرید</h1>
        <div class="pa-breadcrumb"><a href="/">خانه</a><span class="pa-sep">›</span><span>سبد خرید</span></div>
    </div>
</div>

<?php if ($items === []): ?>
    <div class="pa-card pa-text-center" style="padding:48px">
        <span data-pa-icon="shopping_cart" data-pa-icon-size="56" style="opacity:.4"></span>
        <h3 class="pa-mt-1">سبد خرید شما خالی است.</h3>
        <a class="pa-btn pa-btn-primary pa-mt-1" href="/products" data-pa-icon="package_alt"> شروع خرید</a>
    </div>
<?php else: ?>
<div class="pa-row">
    <div class="pa-col-8">
        <form id="cartUpdate" method="post" action="/cart/update">
            <?= Csrf::field() ?>
        </form>
        <?php foreach ($items as $item): ?>
            <?php $p = $item['product']; ?>
            <div class="pa-card pa-mb-1">
                <div class="pa-card-body pa-d-flex pa-gap-2 pa-align-center" style="flex-wrap:wrap">
                    <div class="pa-product-thumb" style="background:linear-gradient(135deg,#6a4c93,#1982c4);display:grid;place-items:center;color:#fff;width:72px;height:72px;border-radius:12px;flex-shrink:0">
                        <span data-pa-icon="package_alt" data-pa-icon-size="32"></span>
                    </div>
                    <div style="flex:1;min-width:160px">
                        <a href="/product/<?= rawurlencode((string) $p['slug']) ?>"><strong><?= View::e((string) $p['name']) ?></strong></a>
                        <div class="pa-text-muted pa-fs-sm">قیمت واحد: <?= $money((int) $p['price']) ?></div>
                    </div>
                    <div class="pa-d-flex pa-align-center pa-gap-1">
                        <input class="pa-input" type="number" name="quantities[<?= (int) $p['id'] ?>]" value="<?= $toFa((string) $item['quantity']) ?>" min="0" max="<?= (int) $p['stock'] ?>" form="cartUpdate" style="width:72px" aria-label="تعداد">
                    </div>
                    <div style="min-width:100px;text-align:left">
                        <strong><?= $money((int) $item['line']) ?></strong>
                    </div>
                    <form method="post" action="/cart/remove" onsubmit="return confirm('حذف این قلم از سبد؟')">
                        <?= Csrf::field() ?>
                        <input type="hidden" name="product_id" value="<?= (int) $p['id'] ?>">
                        <button type="submit" class="pa-btn pa-btn-ghost pa-btn-sm" data-pa-icon="trash" data-pa-icon-size="16" aria-label="حذف"></button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="pa-d-flex pa-gap-2 pa-mt-1">
            <button type="submit" form="cartUpdate" class="pa-btn pa-btn-primary" data-pa-icon="refresh"> به‌روزرسانی سبد</button>
            <a class="pa-btn pa-btn-outline" href="/products">ادامه خرید</a>
        </div>
    </div>

    <div class="pa-col-4">
        <div class="pa-card">
            <div class="pa-card-header"><h3>خلاصه سبد</h3></div>
            <div class="pa-card-body">
                <div class="pa-d-flex pa-justify-between pa-mb-1"><span class="pa-text-muted">تعداد اقلام</span><span><?= $toFa((string) count($items)) ?></span></div>
                <div class="pa-d-flex pa-justify-between" style="border-top:1px solid var(--pa-border);padding-top:12px;margin-top:12px"><strong>قابل پرداخت</strong><strong class="pa-fs-lg" style="color:var(--pa-primary)"><?= $money($subtotal) ?></strong></div>
                <a class="pa-btn pa-btn-primary pa-btn-block pa-btn-lg pa-mt-2" href="/checkout" data-pa-icon="credit_card"> تسویه حساب</a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
