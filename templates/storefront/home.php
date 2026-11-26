<?php

declare(strict_types=1);

/**
 * صفحهٔ اصلی فروشگاه — پوستهٔ PAdminLTE.
 *
 * @package Coop\Templates\Storefront
 * @version 0.2.0
 * @security-level public
 */

use App\Support\Cards;

/** @var array<string, mixed> $__data */

$featured = $__data['featured'] ?? [];
$latest = $__data['latest'] ?? [];
$settings = $__data['settings'] ?? [];
$siteName = $__data['siteName'];
$tagline = $__data['tagline'];
$money = $__data['money'];

$announcement = trim((string) ($settings['announcement'] ?? ''));
$about = trim((string) ($settings['about_text'] ?? ''));
?>
<?php if ($announcement !== ''): ?>
    <div class="pa-alert pa-alert-info">
        <span data-pa-icon="info"></span>
        <div class="pa-alert-body"><?= View::e($announcement) ?></div>
    </div>
<?php endif; ?>

<section class="pa-hero pa-anim-slide-up">
    <h1 style="font-size:clamp(1.8rem,5vw,2.6rem);margin:0 0 8px"><?= View::e($siteName) ?></h1>
    <p style="font-size:clamp(1rem,2.5vw,1.2rem);opacity:.92;margin:0 0 24px;max-width:640px"><?= View::e($tagline) ?> — فرش، گلیم، پارچه‌های دست‌بافت و صنایع‌دستی نساجی روستایی، تولید اعضای تعاونی.</p>
    <div class="pa-d-flex pa-gap-2" style="flex-wrap:wrap">
        <a class="pa-btn pa-btn-light pa-btn-lg" href="/products" data-pa-icon="shopping_cart"> مشاهده محصولات</a>
        <a class="pa-btn pa-btn-outline-light pa-btn-lg" href="/members" data-pa-icon="users_alt"> نساجان تعاونی</a>
    </div>
</section>

<?php if ($featured !== []): ?>
<section class="pa-mb-2">
    <div class="pa-d-flex pa-justify-between pa-align-center pa-mb-1">
        <h2 style="margin:0">محصولات منتخب</h2>
        <a class="pa-btn pa-btn-ghost pa-btn-sm" href="/products">همه ←</a>
    </div>
    <div class="pa-row pa-gap-2">
        <?php foreach ($featured as $p): ?>
            <?= Cards::productCard($p, $money) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($latest !== []): ?>
<section class="pa-mb-2">
    <div class="pa-d-flex pa-justify-between pa-align-center pa-mb-1">
        <h2 style="margin:0">تازه‌های تولید</h2>
        <a class="pa-btn pa-btn-ghost pa-btn-sm" href="/products?sort=new">بیشتر ←</a>
    </div>
    <div class="pa-row pa-gap-2">
        <?php foreach ($latest as $p): ?>
            <?= Cards::productCard($p, $money) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($about !== ''): ?>
<div class="pa-card pa-anim-slide-up">
    <div class="pa-card-header"><h3>درباره تعاونی</h3></div>
    <div class="pa-card-body">
        <p style="line-height:1.9"><?= nl2br(View::e($about)) ?></p>
        <a class="pa-btn pa-btn-outline pa-btn-sm" href="/about">بیشتر بدانید ←</a>
    </div>
</div>
<?php endif; ?>
