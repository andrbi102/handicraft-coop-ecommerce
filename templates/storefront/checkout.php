<?php
declare(strict_types=1);
/**
 * تسویحه حساب.
 * @package Coop\Templates\Storefront
 * @version 0.1.0
 * @security-level private
 */
$items = $__data['items'] ?? [];
$subtotal = (int) ($__data['subtotal'] ?? 0);
$couponCode = $__data['couponCode'] ?? '';
$couponEval = $__data['couponEval'] ?? ['ok' => false, 'discount' => 0, 'reason' => '', 'coupon' => null];
$shipping = (int) ($__data['shipping'] ?? 0);
$total = (int) ($__data['total'] ?? 0);
$money = $__data['money'];
$user = $__data['user'] ?? null;
?>
<div class="section-title"><h2>تسویحه حساب</h2></div>
<div class="checkout-layout">
    <div>
        <div class="admin-card">
            <h2>نشانی تحویل</h2>
            <form action="/checkout" method="post">
                <?= Csrf::field() ?>
                <div class="form-grid">
                    <div class="field"><label>نام و نام خانوادگی گیرنده *</label>
                        <input type="text" name="name" required value="<?= View::e($user['name'] ?? '') ?>"></div>
                    <div class="field"><label>تلفن تماس *</label>
                        <input type="tel" name="phone" required value="<?= View::e($user['phone'] ?? '') ?>"></div>
                    <div class="field" style="grid-column:1/-1"><label>نشانی کامل *</label>
                        <textarea name="address" required minlength="5" maxlength="300"></textarea></div>
                    <div class="field"><label>شهر *</label><input type="text" name="city" required></div>
                    <div class="field"><label>کد پستی *</label><input type="text" name="postal_code" required minlength="5"></div>
                    <div class="field" style="grid-column:1/-1"><label>یادداشت سفارش (اختیاری)</label>
                        <textarea name="notes" maxlength="500" style="min-height:60px"></textarea></div>
                </div>
                <button class="btn btn-primary btn-block mt-2">ثبت نهایی سفارش</button>
            </form>
        </div>
        <div class="admin-card">
            <h2>کد تخفیف</h2>
            <form action="/checkout/coupon" method="post">
                <?= Csrf::field() ?>
                <div style="display:flex;gap:8px">
                    <input type="text" name="coupon_code" value="<?= View::e($couponCode) ?>" placeholder="کد تخفیف">
                    <button class="btn btn-outline">اعمال</button>
                </div>
            </form>
            <?php if ($couponCode !== ''): ?>
                <?php if ($couponEval['ok']): ?>
                    <p class="text-muted mt-1">تخفیف اعمال شد: <?= $money((int) $couponEval['discount']) ?></p>
                <?php else: ?>
                    <p class="text-muted mt-1" style="color:var(--c-danger)"><?= View::e($couponEval['reason']) ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <aside class="summary-card">
        <h3 style="margin-bottom:12px">خلاصه سفارش</h3>
        <?php foreach ($items as $item): $p = $item['product']; ?>
            <div class="row-line" style="font-size:13px">
                <span><?= View::e($p['name']) ?> ×<?= $toFa((string) $item['quantity']) ?></span>
                <span><?= $money((int) $item['line']) ?></span>
            </div>
        <?php endforeach; ?>
        <div class="row-line" style="border-top:1px solid var(--c-border);margin-top:8px;padding-top:8px"><span>جمع کالاها</span><span><?= $money($subtotal) ?></span></div>
        <div class="row-line"><span>تخفیف</span><span>-<?= $money((int) $couponEval['discount']) ?></span></div>
        <div class="row-line"><span>هزینه ارسال</span><span><?= $shipping === 0 ? 'رایگان' : $money($shipping) ?></span></div>
        <div class="total row-line"><span>قابل پرداخت</span><span><?= $money($total) ?></span></div>
        <p class="text-muted" style="font-size:12px;margin-top:10px">پرداخت در محل / کارت‌به‌کارت — شماره حساب در فوتر. سفارش پس از ثبت در پنل مدیریت تأیید می‌شود.</p>
    </aside>
</div>
