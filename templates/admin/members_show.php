<?php

declare(strict_types=1);

/**
 * جزئیات عضو تعاونی (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$member = $__data['member'] ?? [];
$products = $__data['products'] ?? [];
$settlements = $__data['settlements'] ?? [];
$pending = (int) ($__data['pending'] ?? 0);
$paid = (int) ($__data['paid'] ?? 0);
$productCount = (int) ($__data['productCount'] ?? 0);
$money = $__data['money'];
$toFa = $__data['toFa'];
$mid = (int) ($member['id'] ?? 0);
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1><?= View::e((string) ($member['full_name'] ?? '')) ?></h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><a href="/admin/members">نساجان</a><span class="pa-sep">›</span><span><?= View::e((string) ($member['full_name'] ?? '')) ?></span></div>
    </div>
    <div class="pa-d-flex pa-gap-2">
        <a class="pa-btn pa-btn-outline pa-btn-sm" href="/admin/members/<?= $mid ?>/edit" data-pa-icon="edit"> ویرایش</a>
        <a class="pa-btn pa-btn-outline pa-btn-sm" href="/admin/members" data-pa-icon="arrow_right"> بازگشت</a>
    </div>
</div>

<div class="pa-row pa-mb-2">
    <div class="pa-col-3"><div class="pa-small-box pa-bg-info"><div class="pa-inner"><h3><?= $money((int) ($member['share_capital'] ?? 0)) ?></h3><p>سرمایه سهام</p></div><span class="pa-icon-bg" data-pa-icon="briefcase" data-pa-icon-size="60"></span></div></div>
    <div class="pa-col-3"><div class="pa-small-box pa-bg-success"><div class="pa-inner"><h3><?= $toFa((string) $productCount) ?></h3><p>محصولات فعال</p></div><span class="pa-icon-bg" data-pa-icon="cube" data-pa-icon-size="60"></span></div></div>
    <div class="pa-col-3"><div class="pa-small-box pa-bg-warning"><div class="pa-inner"><h3><?= $money($pending) ?></h3><p>تسویه معلق</p></div><span class="pa-icon-bg" data-pa-icon="hourglass" data-pa-icon-size="60"></span></div></div>
    <div class="pa-col-3"><div class="pa-small-box pa-bg-primary"><div class="pa-inner"><h3><?= $money($paid) ?></h3><p>تسویه پرداخت‌شده</p></div><span class="pa-icon-bg" data-pa-icon="check_circle" data-pa-icon-size="60"></span></div></div>
</div>

<div class="pa-row">
    <div class="pa-col-4">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1">
            <div class="pa-card-header"><h3>پروفایل</h3></div>
            <div class="pa-card-body">
                <p><strong><?= View::e((string) ($member['full_name'] ?? '')) ?></strong></p>
                <p class="pa-text-muted">تخصص: <?= View::e((string) ($member['craft'] ?? '')) ?></p>
                <p class="pa-text-muted">منطقه: <?= View::e((string) ($member['region'] ?? '')) ?></p>
                <p class="pa-text-muted">عضویت: <?= $toFa((string) mb_substr((string) ($member['joined_at'] ?? ''), 0, 10, 'UTF-8')) ?></p>
                <p class="pa-text-muted">تلفن: <?= View::e((string) ($member['phone'] ?? '')) ?></p>
                <?php if (!empty($member['bio'])): ?><p style="margin-top:8px"><?= nl2br(View::e((string) $member['bio'])) ?></p><?php endif; ?>
            </div>
        </div>
    </div>

    <div class="pa-col-8">
        <div class="pa-card pa-anim-slide-up pa-anim-delay-1 pa-mb-2">
            <div class="pa-card-header"><h3>محصولات (<?= $toFa((string) $productCount) ?>)</h3></div>
            <div class="pa-card-body" style="padding:0">
                <div class="pa-table-wrap">
                    <table class="pa-table pa-table-striped">
                        <thead><tr><th>نام</th><th class="pa-text-end">قیمت</th><th class="pa-text-end">موجودی</th></tr></thead>
                        <tbody>
                        <?php if ($products === []): ?>
                            <tr><td colspan="3" class="pa-text-center pa-text-muted" style="padding:24px">محصولی نیست.</td></tr>
                        <?php else: ?>
                            <?php foreach ($products as $p): ?>
                                <tr><td><a href="/admin/products/<?= (int) $p['id'] ?>/edit"><?= View::e($p['name']) ?></a></td><td class="pa-text-end"><?= $money((int) $p['price']) ?></td><td class="pa-text-end"><?= $toFa((string) $p['stock']) ?></td></tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="pa-card pa-anim-slide-up pa-anim-delay-2">
            <div class="pa-card-header"><h3>تسویه‌ها</h3></div>
            <div class="pa-card-body" style="padding:0">
                <div class="pa-table-wrap">
                    <table class="pa-table pa-table-striped">
                        <thead><tr><th>دوره</th><th>نوع</th><th class="pa-text-end">مبلغ</th><th>وضعیت</th></tr></thead>
                        <tbody>
                        <?php if ($settlements === []): ?>
                            <tr><td colspan="4" class="pa-text-center pa-text-muted" style="padding:24px">تسویه‌ای نیست.</td></tr>
                        <?php else: ?>
                            <?php foreach ($settlements as $s): ?>
                                <tr>
                                    <td><?= View::e($s['period']) ?></td>
                                    <td class="pa-text-muted"><?= View::e($s['type']) ?></td>
                                    <td class="pa-text-end"><?= $money((int) $s['amount']) ?></td>
                                    <td><span class="pa-badge <?= $s['status'] === 'paid' ? 'pa-badge-success' : 'pa-badge-warning' ?>"><?= $s['status'] === 'paid' ? 'پرداخت‌شده' : 'معلق' ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
