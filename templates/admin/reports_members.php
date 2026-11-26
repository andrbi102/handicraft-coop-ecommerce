<?php

declare(strict_types=1);

/**
 * گزارش عملکرد اعضا (مدیریت) — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Admin
 * @version 0.2.0
 * @security-level admin
 */

/** @var array<string, mixed> $__data */

$rows = $__data['rows'] ?? [];
$totalShare = (int) ($__data['totalShare'] ?? 0);
$totalPending = (int) ($__data['totalPending'] ?? 0);
$totalPaid = (int) ($__data['totalPaid'] ?? 0);
$money = $__data['money'];
$toFa = $__data['toFa'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>عملکرد اعضای تعاونی</h1>
        <div class="pa-breadcrumb"><a href="/admin">پنل مدیریت</a><span class="pa-sep">›</span><a href="/admin/reports">گزارش‌ها</a><span class="pa-sep">›</span><span>اعضا</span></div>
    </div>
    <a class="pa-btn pa-btn-outline pa-btn-sm" href="/admin/reports" data-pa-icon="arrow_right"> بازگشت</a>
</div>

<div class="pa-row pa-mb-2">
    <div class="pa-col-4">
        <div class="pa-small-box pa-bg-info">
            <div class="pa-inner"><h3><?= $money($totalShare) ?></h3><p>کل سرمایه سهام</p></div>
            <span class="pa-icon-bg" data-pa-icon="briefcase" data-pa-icon-size="60"></span>
        </div>
    </div>
    <div class="pa-col-4">
        <div class="pa-small-box pa-bg-warning">
            <div class="pa-inner"><h3><?= $money($totalPending) ?></h3><p>تسویه معلق کل</p></div>
            <span class="pa-icon-bg" data-pa-icon="hourglass" data-pa-icon-size="60"></span>
        </div>
    </div>
    <div class="pa-col-4">
        <div class="pa-small-box pa-bg-success">
            <div class="pa-inner"><h3><?= $money($totalPaid) ?></h3><p>تسویه پرداخت‌شده کل</p></div>
            <span class="pa-icon-bg" data-pa-icon="check_circle" data-pa-icon-size="60"></span>
        </div>
    </div>
</div>

<div class="pa-card pa-anim-slide-up pa-anim-delay-1">
    <div class="pa-card-header"><h3>عملکرد اعضا</h3></div>
    <div class="pa-card-body" style="padding:0">
        <div class="pa-table-wrap">
            <table class="pa-table pa-table-striped pa-table-hover">
                <thead><tr>
                    <th>عضو</th><th>تخصص</th><th class="pa-text-end">سهام</th><th class="pa-text-end">محصولات</th><th class="pa-text-end">معلق</th><th class="pa-text-end">پرداخت‌شده</th>
                </tr></thead>
                <tbody>
                <?php if ($rows === []): ?>
                    <tr><td colspan="6" class="pa-text-center pa-text-muted" style="padding:24px">عضوی نیست.</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><a href="/admin/members/<?= (int) $r['id'] ?>"><?= View::e($r['full_name']) ?></a></td>
                            <td class="pa-text-muted"><?= View::e($r['craft']) ?></td>
                            <td class="pa-text-end"><?= $money((int) $r['share_capital']) ?></td>
                            <td class="pa-text-end"><?= $toFa((string) $r['product_count']) ?></td>
                            <td class="pa-text-end"><?= $money((int) $r['pending']) ?></td>
                            <td class="pa-text-end"><?= $money((int) $r['paid']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
