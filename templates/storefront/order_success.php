<?php

declare(strict_types=1);

/**
 * تأیید سفارش — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

/** @var array<string, mixed> $__data */

$order = $__data['order'] ?? [];
$items = $__data['items'] ?? [];
$money = $__data['money'];
$toFa = $__data['toFa'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div><h1>سفارش ثبت شد</h1></div>
</div>

<div class="pa-card pa-text-center pa-mb-2" style="padding:40px">
    <span data-pa-icon="check_circle" data-pa-icon-size="64" style="color:var(--pa-success)"></span>
    <h2 class="pa-mt-1">سفارش شما ثبت شد</h2>
    <p class="pa-text-muted">شماره سفارش: <strong><?= View::e((string) ($order['order_number'] ?? '')) ?></strong></p>
    <p class="pa-text-muted pa-fs-sm">پس از تأیید پنل مدیریت، وضعیت سفارش در «حساب من» قابل پیگیری است.</p>
</div>

<div class="pa-row">
    <div class="pa-col-8">
        <div class="pa-card">
            <div class="pa-card-header"><h3>جزئیات سفارش</h3></div>
            <div class="pa-card-body" style="padding:0">
                <div class="pa-table-wrap">
                    <table class="pa-table pa-table-striped">
                        <thead><tr><th>کالا</th><th class="pa-text-end">تعداد</th><th class="pa-text-end">مبلغ</th></tr></thead>
                        <tbody>
                        <?php foreach ($items as $it): ?>
                            <tr><td><?= View::e((string) $it['product_name']) ?></td><td class="pa-text-end"><?= $toFa((string) $it['quantity']) ?></td><td class="pa-text-end"><?= $money((int) $it['subtotal']) ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="pa-card-footer">
                <div class="pa-d-flex pa-justify-between pa-mb-1"><span class="pa-text-muted">جمع کالاها</span><span><?= $money((int) ($order['subtotal'] ?? 0)) ?></span></div>
                <div class="pa-d-flex pa-justify-between pa-mb-1"><span class="pa-text-muted">تخفیف</span><span>-<?= $money((int) ($order['discount'] ?? 0)) ?></span></div>
                <div class="pa-d-flex pa-justify-between pa-mb-1"><span class="pa-text-muted">ارسال</span><span><?= (int) ($order['shipping_cost'] ?? 0) === 0 ? 'رایگان' : $money((int) $order['shipping_cost']) ?></span></div>
                <div class="pa-d-flex pa-justify-between" style="border-top:1px solid var(--pa-border);padding-top:8px;margin-top:8px"><strong>قابل پرداخت</strong><strong class="pa-fs-lg" style="color:var(--pa-primary)"><?= $money((int) ($order['total'] ?? 0)) ?></strong></div>
            </div>
        </div>
    </div>
    <div class="pa-col-4">
        <div class="pa-card">
            <div class="pa-card-body pa-d-flex pa-gap-1" style="flex-direction:column">
                <a class="pa-btn pa-btn-outline pa-btn-block" href="/account" data-pa-icon="shopping_bag"> سفارش‌های من</a>
                <a class="pa-btn pa-btn-primary pa-btn-block" href="/products" data-pa-icon="package_alt"> ادامه خرید</a>
            </div>
        </div>
    </div>
</div>
