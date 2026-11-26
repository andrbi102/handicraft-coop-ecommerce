<?php

declare(strict_types=1);

/**
 * تسویه حساب — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

use App\Support\Form;

/** @var array<string, mixed> $__data */

$items = $__data['items'] ?? [];
$subtotal = (int) ($__data['subtotal'] ?? 0);
$couponCode = (string) ($__data['couponCode'] ?? '');
$couponEval = $__data['couponEval'] ?? ['ok' => false, 'discount' => 0, 'reason' => '', 'coupon' => null];
$shipping = (int) ($__data['shipping'] ?? 0);
$total = (int) ($__data['total'] ?? 0);
$money = $__data['money'];
$toFa = $__data['toFa'];
$user = $__data['user'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>تسویه حساب</h1>
        <div class="pa-breadcrumb"><a href="/">خانه</a><span class="pa-sep">›</span><a href="/cart">سبد خرید</a><span class="pa-sep">›</span><span>تسویه حساب</span></div>
    </div>
</div>

<?php if ($items === []): ?>
    <div class="pa-card pa-text-center" style="padding:40px">
        <p>سبد خرید شما خالی است.</p>
        <a class="pa-btn pa-btn-primary pa-mt-1" href="/products">شروع خرید</a>
    </div>
<?php else: ?>
<div class="pa-row">
    <div class="pa-col-8">
        <div class="pa-card pa-mb-2">
            <div class="pa-card-header"><h3>نشانی تحویل</h3></div>
            <div class="pa-card-body">
                <form method="post" action="/checkout" data-pa-validate-form>
                    <?= Csrf::field() ?>
                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="ch-name">نام و نام خانوادگی *</label>
                            <input id="ch-name" class="pa-input" type="text" name="name" data-pa-validate="required" value="<?= Form::value($user, 'name') ?>" required>
                        </div>
                        <div class="pa-form-group">
                            <label for="ch-phone">تلفن *</label>
                            <input id="ch-phone" class="pa-input" type="tel" name="phone" data-pa-validate="required" value="<?= Form::value($user, 'phone') ?>" required>
                        </div>
                    </div>
                    <div class="pa-form-group">
                        <label for="ch-addr">نشانی *</label>
                        <textarea id="ch-addr" class="pa-textarea" name="address" rows="2" data-pa-validate="required" data-pa-minlen="5" maxlength="300" required></textarea>
                    </div>
                    <div class="pa-form-row">
                        <div class="pa-form-group">
                            <label for="ch-city">شهر *</label>
                            <input id="ch-city" class="pa-input" type="text" name="city" data-pa-validate="required" required>
                        </div>
                        <div class="pa-form-group">
                            <label for="ch-postal">کد پستی *</label>
                            <input id="ch-postal" class="pa-input" type="text" name="postal_code" data-pa-validate="required" data-pa-minlen="5" required>
                        </div>
                    </div>
                    <div class="pa-form-group">
                        <label for="ch-notes">یادداشت سفارش</label>
                        <textarea id="ch-notes" class="pa-textarea" name="notes" rows="2" maxlength="500"></textarea>
                    </div>
                    <button type="submit" class="pa-btn pa-btn-primary pa-btn-lg" data-pa-icon="check"> ثبت نهایی سفارش</button>
                </form>
            </div>
        </div>

        <div class="pa-card">
            <div class="pa-card-header"><h3>کد تخفیف</h3></div>
            <div class="pa-card-body">
                <form method="post" action="/checkout/coupon" class="pa-d-flex pa-gap-1">
                    <?= Csrf::field() ?>
                    <input class="pa-input" type="text" name="coupon_code" value="<?= View::e($couponCode) ?>" placeholder="کد تخفیف">
                    <button type="submit" class="pa-btn pa-btn-outline" data-pa-icon="tag"> اعمال</button>
                </form>
                <?php if ($couponCode !== ''): ?>
                    <?php if (!empty($couponEval['ok'])): ?>
                        <p class="pa-alert pa-alert-success pa-mt-1" style="padding:8px 12px"><span data-pa-icon="check_circle"></span> تخفیف <?= $money((int) $couponEval['discount']) ?> اعمال شد.</p>
                    <?php else: ?>
                        <p class="pa-alert pa-alert-danger pa-mt-1" style="padding:8px 12px"><span data-pa-icon="x_circle"></span> <?= View::e((string) ($couponEval['reason'] ?? 'کد نامعتبر است.')) ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="pa-col-4">
        <div class="pa-card">
            <div class="pa-card-header"><h3>خلاصه سفارش</h3></div>
            <div class="pa-card-body">
                <?php foreach ($items as $item): ?>
                    <div class="pa-d-flex pa-justify-between pa-mb-1">
                        <span class="pa-fs-sm"><?= View::e((string) $item['product']['name']) ?> × <?= $toFa((string) $item['quantity']) ?></span>
                        <span class="pa-fs-sm"><?= $money((int) $item['line']) ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="pa-d-flex pa-justify-between pa-mt-1" style="border-top:1px solid var(--pa-border);padding-top:8px"><span class="pa-text-muted">جمع کالاها</span><span><?= $money($subtotal) ?></span></div>
                <div class="pa-d-flex pa-justify-between pa-mb-1"><span class="pa-text-muted">تخفیف</span><span>-<?= $money((int) ($couponEval['discount'] ?? 0)) ?></span></div>
                <div class="pa-d-flex pa-justify-between pa-mb-1"><span class="pa-text-muted">هزینه ارسال</span><span><?= $shipping === 0 ? 'رایگان' : $money($shipping) ?></span></div>
                <div class="pa-d-flex pa-justify-between" style="border-top:1px solid var(--pa-border);padding-top:8px;margin-top:8px"><strong>قابل پرداخت</strong><strong class="pa-fs-lg" style="color:var(--pa-primary)"><?= $money($total) ?></strong></div>
                <p class="pa-text-muted pa-fs-sm pa-mt-1">پرداخت در محل یا کارت‌به‌کارت — پس از تأیید سفارش هماهنگ می‌شود.</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
