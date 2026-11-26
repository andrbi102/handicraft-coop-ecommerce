<?php

declare(strict_types=1);

/**
 * پیگیری سفارش — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

use App\Support\Form;

/** @var array<string, mixed> $__data */

$number = $__data['number'] ?? '';
$phone = $__data['phone'] ?? '';
$order = $__data['order'] ?? null;
$items = $__data['items'] ?? [];
$searched = (bool) ($__data['searched'] ?? false);
$trackError = (string) ($__data['trackError'] ?? '');
$money = $__data['money'];
$toFa = $__data['toFa'];

$statusLabels = [
    'pending' => 'ثبت شد', 'paid' => 'پرداخت', 'processing' => 'آماده‌سازی',
    'shipped' => 'ارسال', 'delivered' => 'تحویل', 'cancelled' => 'لغو', 'refunded' => 'بازگشت',
];
// مراحل خطی برای استپر (cancelled/refunded جدا نمایش داده می‌شوند)
$stages = ['pending', 'paid', 'processing', 'shipped', 'delivered'];
$currentStatus = $order !== null ? (string) $order['status'] : '';
$currentIdx = array_search($currentStatus, $stages, true);
$isTerminalBad = in_array($currentStatus, ['cancelled', 'refunded'], true);
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>پیگیری سفارش</h1>
        <div class="pa-breadcrumb"><a href="/">خانه</a><span class="pa-sep">›</span><span>پیگیری سفارش</span></div>
    </div>
</div>

<div class="pa-row pa-mb-2">
    <div class="pa-col-6">
        <div class="pa-card">
            <div class="pa-card-header"><h3>اطلاعات سفارش</h3></div>
            <div class="pa-card-body">
                <form method="get" action="/track" data-pa-validate-form>
                    <div class="pa-form-group">
                        <label for="t-number">شماره سفارش *</label>
                        <div class="pa-input-group">
                            <span class="pa-input-addon" data-pa-icon="hashtag"></span>
                            <input id="t-number" class="pa-input" type="text" name="number" data-pa-validate="required" data-pa-minlen="4" value="<?= View::e((string) $number) ?>" required autofocus>
                        </div>
                        <div class="pa-form-feedback"></div>
                    </div>
                    <div class="pa-form-group">
                        <label for="t-phone">تلفن ثبت‌شده در سفارش *</label>
                        <div class="pa-input-group">
                            <span class="pa-input-addon" data-pa-icon="phone"></span>
                            <input id="t-phone" class="pa-input" type="tel" name="phone" data-pa-validate="required" data-pa-minlen="7" value="<?= View::e((string) $phone) ?>" required>
                        </div>
                        <div class="pa-form-feedback"></div>
                        <div class="pa-hint">برای امنیت، تلفن باید با سفارش مطابقت داشته باشد.</div>
                    </div>
                    <button type="submit" class="pa-btn pa-btn-primary" data-pa-icon="search"> پیگیری</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if ($searched && $order !== null): ?>
<?php
// استپر وضعیت
?>
<div class="pa-card pa-mb-2">
    <div class="pa-card-header"><h3>وضعیت سفارش <?= View::e((string) $order['order_number']) ?></h3></div>
    <div class="pa-card-body">
        <?php if ($isTerminalBad): ?>
            <div class="pa-alert pa-alert-danger" role="alert">
                <span data-pa-icon="x_circle"></span>
                <div class="pa-alert-body">این سفارش <?= View::e($statusLabels[$currentStatus] ?? $currentStatus) ?> است.</div>
            </div>
        <?php else: ?>
            <div class="pa-stepper">
                <?php foreach ($stages as $i => $stage): ?>
                    <?php
                    $done = $currentIdx !== false && $i <= $currentIdx;
                    $active = $i === $currentIdx;
                    ?>
                    <div class="pa-stepper-step<?= $done ? ' is-done' : '' ?><?= $active ? ' is-active' : '' ?>">
                        <div class="pa-stepper-dot">
                            <?= $done ? '<span data-pa-icon="check" data-pa-icon-size="16"></span>' : $toFa((string) ($i + 1)) ?>
                        </div>
                        <div class="pa-stepper-label"><?= View::e($statusLabels[$stage]) ?></div>
                    </div>
                    <?php if ($i < count($stages) - 1): ?>
                        <div class="pa-stepper-conn<?= $currentIdx !== false && $i < $currentIdx ? ' is-done' : '' ?>"></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <p class="pa-text-muted pa-fs-sm pa-mt-2">تاریخ ثبت: <?= $toFa((string) mb_substr((string) $order['created_at'], 0, 10, 'UTF-8')) ?></p>
    </div>
</div>

<div class="pa-row">
    <div class="pa-col-8">
        <div class="pa-card">
            <div class="pa-card-header"><h3>اقلام سفارش</h3></div>
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
                <div class="pa-d-flex pa-justify-between"><span class="pa-text-muted">قابل پرداخت</span><strong class="pa-fs-lg" style="color:var(--pa-primary)"><?= $money((int) $order['total']) ?></strong></div>
            </div>
        </div>
    </div>
    <div class="pa-col-4">
        <div class="pa-card">
            <div class="pa-card-header"><h3>تحویل</h3></div>
            <div class="pa-card-body">
                <p><strong><?= View::e((string) $order['shipping_name']) ?></strong></p>
                <p class="pa-text-muted"><?= View::e((string) $order['shipping_address']) ?>، <?= View::e((string) $order['shipping_city']) ?></p>
                <p class="pa-text-muted">کد پستی: <?= $toFa((string) $order['shipping_postal_code']) ?></p>
            </div>
        </div>
    </div>
</div>
<?php elseif ($searched): ?>
    <div class="pa-alert pa-alert-danger" role="alert">
        <span data-pa-icon="x_circle"></span>
        <div class="pa-alert-body"><?= $trackError !== '' ? View::e($trackError) : 'سفارشی با این اطلاعات یافت نشد. شماره سفارش و تلفن را بررسی کنید.' ?></div>
    </div>
<?php endif; ?>
