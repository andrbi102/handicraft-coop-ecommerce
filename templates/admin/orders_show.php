<?php

declare(strict_types=1);

/**
 * جزئیات سفارش (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$order = $__data['order'] ?? [];
$items = $__data['items'] ?? [];
$statusLabels = $__data['statusLabels'] ?? [];
$money = $__data['money'];
$toFa = $__data['toFa'];
$oid = (int) ($order['id'] ?? 0);

$statusBadge = [
    'pending' => 'pa-badge-warning', 'paid' => 'pa-badge-info', 'processing' => 'pa-badge-primary',
    'shipped' => 'pa-badge-primary', 'delivered' => 'pa-badge-success', 'cancelled' => 'pa-badge-danger', 'refunded' => 'pa-badge-danger',
];
$status = (string) ($order['status'] ?? '');
$canDeliver = !in_array($status, ['delivered', 'cancelled'], true);
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>سفارش <?= View::e((string) ($order['order_number'] ?? '')) ?></h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><a href="/admin/orders">سفارش‌ها</a><span class="pa-sep">›</span><span><?= View::e((string) ($order['order_number'] ?? '')) ?></span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/admin/orders" data-pa-icon="arrow_right"> بازگشت</a>
</div>

<div class="pa-row">
    <div class="pa-col-8">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1 pa-mb-2">
            <div class="pa-card-header"><h3>اقلام سفارش</h3></div>
            <div class="pa-card-body" style="padding:0">
                <div class="pa-table-wrap">
                    <table class="pa-table pa-table-striped">
                        <thead><tr><th>کالا</th><th class="pa-text-end">تعداد</th><th class="pa-text-end">مبلغ واحد</th><th class="pa-text-end">مبلغ کل</th><th>سازنده</th></tr></thead>
                        <tbody>
                        <?php if ($items === []): ?>
                            <tr><td colspan="5" class="pa-text-center pa-text-muted" style="padding:24px">موردی نیست.</td></tr>
                        <?php else: ?>
                            <?php foreach ($items as $it): ?>
                                <tr>
                                    <td><?= View::e($it['product_name']) ?></td>
                                    <td class="pa-text-end"><?= $toFa((string) $it['quantity']) ?></td>
                                    <td class="pa-text-end"><?= $money((int) $it['price']) ?></td>
                                    <td class="pa-text-end"><?= $money((int) $it['subtotal']) ?></td>
                                    <td class="pa-text-muted"><?= $it['member_id'] ? ('#' . $toFa((string) $it['member_id'])) : '—' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="pa-card pa-anim-slide-up pa-anim-delay-2">
            <div class="pa-card-header"><h3>جمع‌بندی</h3></div>
            <div class="pa-card-body">
                <div class="pa-d-flex pa-justify-between pa-mb-1"><span class="pa-text-muted">جمع کالاها</span><span><?= $money((int) ($order['subtotal'] ?? 0)) ?></span></div>
                <div class="pa-d-flex pa-justify-between pa-mb-1"><span class="pa-text-muted">تخفیف</span><span>-<?= $money((int) ($order['discount'] ?? 0)) ?></span></div>
                <div class="pa-d-flex pa-justify-between pa-mb-1"><span class="pa-text-muted">ارسال</span><span><?= (int) ($order['shipping_cost'] ?? 0) === 0 ? 'رایگان' : $money((int) $order['shipping_cost']) ?></span></div>
                <div class="pa-d-flex pa-justify-between" style="border-top:1px solid var(--pa-border);padding-top:8px;margin-top:8px"><strong>کل</strong><strong class="pa-fs-lg"><?= $money((int) ($order['total'] ?? 0)) ?></strong></div>
            </div>
        </div>
    </div>

    <div class="pa-col-4">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1 pa-mb-2">
            <div class="pa-card-header"><h3>وضعیت سفارش</h3></div>
            <div class="pa-card-body">
                <p class="pa-mb-2">وضعیت فعلی: <span class="pa-badge <?= View::e($statusBadge[$status] ?? 'pa-badge-secondary') ?>"><?= View::e($statusLabels[$status] ?? $status) ?></span></p>
                <form method="post" action="/admin/orders/<?= $oid ?>/status" class="pa-mb-2">
                    <?= Csrf::field() ?>
                    <div class="pa-form-group">
                        <label for="o-status">تغییر وضعیت</label>
                        <select id="o-status" class="pa-select" name="status">
                            <?php foreach ($statusLabels as $k => $lbl): ?>
                                <option value="<?= View::e($k) ?>" <?= $status === $k ? 'selected' : '' ?>><?= View::e($lbl) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="pa-btn pa-btn-outline pa-btn-block pa-btn-sm" data-pa-icon="refresh"> تغییر وضعیت</button>
                </form>
                <?php if ($canDeliver): ?>
                    <form method="post" action="/admin/orders/<?= $oid ?>/deliver" onsubmit="return confirm('تحویل نهایی؟ تسویه‌های اعضا ایجاد می‌شود.')">
                        <?= Csrf::field() ?>
                        <button type="submit" class="pa-btn pa-btn-success pa-btn-block" data-pa-icon="truck"> تحویل و ایجاد تسویه</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="pa-card pa-anim-slide-up pa-anim-delay-2">
            <div class="pa-card-header"><h3>اطلاعات ارسال</h3></div>
            <div class="pa-card-body">
                <p><strong><?= View::e((string) ($order['shipping_name'] ?? '')) ?></strong></p>
                <p class="pa-text-muted"><?= View::e((string) ($order['shipping_address'] ?? '')) ?></p>
                <p class="pa-text-muted"><?= View::e((string) ($order['shipping_city'] ?? '')) ?> — کد پستی: <?= $toFa((string) ($order['shipping_postal_code'] ?? '')) ?></p>
                <p class="pa-text-muted">تلفن: <?= View::e((string) ($order['shipping_phone'] ?? '')) ?></p>
                <?php if (!empty($order['notes'])): ?><p class="pa-text-muted">یادداشت: <?= View::e((string) $order['notes']) ?></p><?php endif; ?>
                <?php if (!empty($order['coupon_code'])): ?><p>کد تخفیف: <code><?= View::e((string) $order['coupon_code']) ?></code></p><?php endif; ?>
            </div>
        </div>
    </div>
</div>
