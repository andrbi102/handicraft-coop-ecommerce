<?php

declare(strict_types=1);

/**
 * فهرست کدهای تخفیف + فرم افزودن (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$coupons = $__data['coupons'] ?? [];
$page = (int) ($__data['page'] ?? 1);
$pages = (int) ($__data['pages'] ?? 1);
$money = $__data['money'];
$toFa = $__data['toFa'];

$statusLabel = ['active' => 'فعال', 'inactive' => 'غیرفعال'];
$statusBadge = ['active' => 'pa-badge-success', 'inactive' => 'pa-badge-secondary'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>کدهای تخفیف</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><span>تخفیف</span></div>
    </div>
</div>

<div class="pa-row">
    <div class="pa-col-8">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>کدها</h3></div>
            <div class="pa-card-body" style="padding:0">
                <div class="pa-table-wrap">
                    <table class="pa-table pa-table-striped pa-table-hover">
                        <thead><tr>
                            <th>کد</th><th>نوع</th><th class="pa-text-end">مقدار</th><th class="pa-text-end">حداقل</th>
                            <th class="pa-text-end">استفاده</th><th>انقضا</th><th>وضعیت</th><th data-no-export></th>
                        </tr></thead>
                        <tbody>
                        <?php if ($coupons === []): ?>
                            <tr><td colspan="8" class="pa-text-center pa-text-muted" style="padding:30px 0">کد تخفیفی ثبت نشده است.</td></tr>
                        <?php else: ?>
                            <?php foreach ($coupons as $cp): ?>
                                <tr>
                                    <td><code><strong><?= View::e($cp['code']) ?></strong></code></td>
                                    <td><?= $cp['type'] === 'percent' ? 'درصدی' : 'ثابت' ?></td>
                                    <td class="pa-text-end"><?= $cp['type'] === 'percent' ? $toFa((string) $cp['value']) . '٪' : $money((int) $cp['value']) ?></td>
                                    <td class="pa-text-end pa-text-muted"><?= $money((int) $cp['min_subtotal']) ?></td>
                                    <td class="pa-text-end"><?= $toFa((string) $cp['used_count']) . ' / ' . ($cp['usage_limit'] === null ? '∞' : $toFa((string) $cp['usage_limit'])) ?></td>
                                    <td class="pa-text-muted"><?= View::e($cp['expires_at'] ?: '—') ?></td>
                                    <td><span class="pa-badge <?= View::e($statusBadge[$cp['status']] ?? 'pa-badge-secondary') ?>"><?= View::e($statusLabel[$cp['status']] ?? $cp['status']) ?></span></td>
                                    <td data-no-export><a class="pa-btn pa-btn-ghost pa-btn-sm" href="/admin/coupons/<?= (int) $cp['id'] ?>/edit" data-pa-icon="edit" data-pa-icon-size="16" aria-label="ویرایش"></a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include __DIR__ . '/../partials/pagination.php'; ?>
    </div>

    <div class="pa-col-4">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>کد جدید</h3></div>
            <div class="pa-card-body">
                <form method="post" action="/admin/coupons" data-pa-validate-form>
                    <?= Csrf::field() ?>
                    <div class="pa-form-group">
                        <label for="cp-code">کد تخفیف</label>
                        <input id="cp-code" class="pa-input" type="text" name="code" data-pa-validate="required" data-pa-minlen="3" required>
                        <div class="pa-form-feedback"></div>
                    </div>
                    <div class="pa-form-group">
                        <label for="cp-type">نوع</label>
                        <select id="cp-type" class="pa-select" name="type">
                            <option value="fixed">مبلغ ثابت</option>
                            <option value="percent">درصدی</option>
                        </select>
                    </div>
                    <div class="pa-form-group">
                        <label for="cp-value">مقدار</label>
                        <input id="cp-value" class="pa-input" type="number" name="value" min="1" data-pa-validate="required" required>
                    </div>
                    <div class="pa-form-group">
                        <label for="cp-min">حداقل مبلغ سبد</label>
                        <input id="cp-min" class="pa-input" type="number" name="min_subtotal" value="0" min="0">
                    </div>
                    <div class="pa-form-group">
                        <label for="cp-limit">سقف استفاده (۰ = نامحدود)</label>
                        <input id="cp-limit" class="pa-input" type="number" name="usage_limit" value="0" min="0">
                    </div>
                    <div class="pa-form-group">
                        <label for="cp-exp">تاریخ انقضا</label>
                        <input id="cp-exp" class="pa-input" type="date" name="expires_at">
                    </div>
                    <button type="submit" class="pa-btn pa-btn-primary pa-btn-block" data-pa-icon="plus"> ایجاد کد</button>
                </form>
            </div>
        </div>
    </div>
</div>
