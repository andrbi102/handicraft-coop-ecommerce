<?php
declare(strict_types=1);
/**
 * سبد خرید.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level public
 */
$items = $__data['items'] ?? [];
$subtotal = (int) ($__data['subtotal'] ?? 0);
$money = $__data['money'];
?>
<div class="section-title"><h2>سبد خرید</h2></div>
<?php if ($items === []): ?>
<div class="admin-card text-center">
    <p style="font-size:18px">🛒 سبد خرید شما خالی است.</p>
    <p class="mt-2"><a href="/products" class="btn btn-primary">شروع خرید</a></p>
</div>
<?php else: ?>
<div class="checkout-layout">
    <div>
        <?php foreach ($items as $item): $p = $item['product']; ?>
        <div class="cart-item">
            <div class="thumb">🧵</div>
            <div>
                <a href="/product/<?= rawurlencode((string) $p['slug']) ?>" style="font-weight:700"><?= View::e($p['name']) ?></a>
                <div class="text-muted"><?= $money((int) $p['price']) ?></div>
            </div>
            <div style="display:flex;align-items:center;gap:6px">
                <input type="number" name="quantities[<?= (int) $p['id'] ?>]" value="<?= (int) $item['quantity'] ?>" min="0" max="<?= (int) $p['stock'] ?>" class="qty" form="cartUpdate">
            </div>
            <div style="text-align:left;min-width:130px">
                <div style="font-weight:700"><?= $money((int) $item['line']) ?></div>
                <form action="/cart/remove" method="post" style="display:inline" data-confirm="حذف این قلم از سبد؟">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="product_id" value="<?= (int) $p['id'] ?>">
                    <button class="btn btn-outline btn-sm" style="color:var(--c-danger)">حذف</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
        <!-- فرم واحد به‌روزرسانی: ورودی‌های تعداد با form="cartUpdate" به آن متصل‌اند -->
        <form id="cartUpdate" action="/cart/update" method="post" class="mt-2">
            <?= Csrf::field() ?>
            <button class="btn btn-primary btn-sm">به‌روزرسانی سبد</button>
            <a href="/products" class="btn btn-outline btn-sm">ادامه خرید</a>
        </form>
    </div>
    <aside class="summary-card">
        <div class="row-line"><span>تعداد اقلام</span><strong><?= $toFa((string) count($items)) ?></strong></div>
        <div class="row-line"><span>جمع کل</span><strong><?= $money($subtotal) ?></strong></div>
        <div class="total row-line"><span>قابل پرداخت</span><span><?= $money($subtotal) ?></span></div>
        <a href="/checkout" class="btn btn-primary btn-block mt-2">تسویحه حساب →</a>
    </aside>
</div>
<?php endif; ?>
