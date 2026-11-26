<?php

declare(strict_types=1);

/**
 * فهرست نساجان تعاونی — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

use App\Support\Cards;

/** @var array<string, mixed> $__data */

$members = $__data['members'] ?? [];
$siteName = $__data['siteName'];
?>
<div class="pa-page-header pa-anim-slide-up">
    <div>
        <h1>نساجان تعاونی</h1>
        <div class="pa-breadcrumb"><a href="/">خانه</a><span class="pa-sep">›</span><span>نساجان</span></div>
    </div>
</div>

<section class="pa-hero pa-mb-2" style="background:linear-gradient(135deg,#1982c4,#8ac926);color:#fff;border-radius:16px;padding:32px">
    <p style="margin:0;font-size:1.1rem;opacity:.92">اعضای تعاونی صنایع‌دستی نساجی <?= View::e($siteName) ?> — تولیدکنندگان فرش، گلیم و پارچه‌های دست‌بافت روستایی.</p>
</section>

<?php if ($members === []): ?>
    <div class="pa-card pa-text-center" style="padding:40px">
        <p class="pa-text-muted">هنوز عضوی معرفی نشده است.</p>
    </div>
<?php else: ?>
    <div class="pa-row pa-gap-2">
        <?php foreach ($members as $m): ?>
            <?= Cards::memberCard($m) ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
